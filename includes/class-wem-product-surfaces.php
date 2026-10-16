<?php
if (!defined('ABSPATH')) { exit; }
final class WEM_Product_Surfaces {
    const CPT = 'wem_surface';
    const IMG_META = '_wem_ps_image_id';
    private static $instance = null;
    private static $usage_hook = '';
    public static function boot() {
        if (null === self::$instance) { self::$instance = new self(); }
        return self::$instance;
    }
    private function __construct() {
        add_action('init', array($this, 'register_cpt'));
        add_action('add_meta_boxes', array($this, 'add_boxes'));
        add_action('save_post_' . self::CPT, array($this, 'save_surface'));
        add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
        add_action('admin_menu', array($this, 'register_usage_page'));
        add_action('wp_enqueue_scripts', array($this, 'register_frontend_assets'));
        add_shortcode('wem_surfaces', array($this, 'shortcode_ids'));
        add_filter('manage_' . self::CPT . '_posts_columns', array($this, 'columns'));
        add_action('manage_' . self::CPT . '_posts_custom_column', array($this, 'column_content'), 10, 2);
    }
    /** Informational page only: surface rendering is site-wide and needs no CPT settings. */
    public function register_usage_page() {
        self::$usage_hook = add_submenu_page(
            'edit.php?post_type=' . self::CPT,
            'Surface Usage',
            'Usage',
            'edit_posts',
            'wem-ps-usage',
            array($this, 'usage_page')
        );
    }
    public function usage_page() {
        if (!current_user_can('edit_posts')) { wp_die(esc_html__('Sorry, you are not allowed to access this page.')); }
        $examples = array(
            array('Show all surfaces', '[wem_surfaces]', 'All published Surfaces with valid images; ascending Surface ID.'),
            array('Show one surface', '[wem_surfaces ids="3310"]', 'Replace 3310 with the Surface ID you want.'),
            array('Show selected surfaces', '[wem_surfaces ids="3310,3300"]', 'Displays selected Surfaces in the listed order.'),
            array('Set columns', '[wem_surfaces columns="4"]', 'All Surfaces, four desktop columns (supported: 1–6).'),
            array('Combine IDs and columns', '[wem_surfaces ids="3310,3300" columns="2"]', 'Selected Surfaces in two desktop columns.'),
        );
        $php_examples = array(
            array('Show all', 'echo wem_render_surfaces();'),
            array('Show selected', 'echo wem_render_surfaces([\'ids\' => [3310, 3300], \'columns\' => 2]);'),
        );
        echo '<div class="wrap wem-ps-usage"><h1>WEM Product Surfaces — Usage</h1>';
        echo '<p>Use the same shortcode across WordPress pages, posts, custom post types and Elementor Shortcode widgets. <strong>No post type selection is required.</strong></p>';
        echo '<h2>Shortcode examples</h2>';
        foreach ($examples as $example) {
            echo '<div class="wem-ps-usage-example"><h3>' . esc_html($example[0]) . '</h3><div class="wem-ps-copy-row"><code>' . esc_html($example[1]) . '</code><button type="button" class="button wem-ps-copy" data-copy="' . esc_attr($example[1]) . '">Copy</button></div><p class="description">' . esc_html($example[2]) . '</p></div>';
        }
        echo '<h2>PHP templates</h2><p>Call these functions inside WordPress theme PHP files:</p>';
        foreach ($php_examples as $example) {
            echo '<div class="wem-ps-usage-example"><h3>' . esc_html($example[0]) . '</h3><div class="wem-ps-copy-row"><code>' . esc_html($example[1]) . '</code><button type="button" class="button wem-ps-copy" data-copy="' . esc_attr($example[1]) . '">Copy</button></div></div>';
        }
        echo '<h2>How to find a Surface ID</h2><p>Go to <strong>Surface Library → All Surfaces</strong> and copy the numeric value in the <strong>Surface ID</strong> column. To create a new Surface, use <strong>Add New Surface</strong>, fill in the title, select one image, and publish.</p>';
        echo '<h2>Elementor</h2><p>Drag in the <strong>Shortcode</strong> widget, paste a shortcode from above, and preview or publish the page. Elementor does not need a dedicated widget from this plugin.</p>';
        echo '<h2>Behavior and notes</h2><p>Only published Surfaces with valid images are displayed. Without <code>ids</code>, Surfaces are ordered by numeric Surface ID ascending; with <code>ids</code>, their input order is retained. Duplicate or invalid IDs are skipped. The front-end layout is responsive, and image proportions are preserved.</p>';
        echo '<p>The Surface title is the front-end caption; the media filename is shown only in the Surface editor. Existing Surface IDs and images are not changed by this Usage page.</p>';
        echo '</div>';
    }
    public function register_cpt() {
        register_post_type(self::CPT, array(
            'labels' => array('name' => 'Surface Library', 'singular_name' => 'Surface', 'add_new_item' => 'Add New Surface', 'edit_item' => 'Edit Surface', 'all_items' => 'All Surfaces'),
            'public' => false, 'show_ui' => true, 'show_in_menu' => true,
            'menu_icon' => 'dashicons-art', 'show_in_rest' => false,
            'supports' => array('title'), 'capability_type' => 'post',
            'exclude_from_search' => true, 'publicly_queryable' => false,
        ));
    }
    public function add_boxes() {
        add_meta_box('wem-ps-image', 'Surface Image', array($this, 'image_box'), self::CPT, 'normal', 'high');
    }
    public function image_box($post) {
        wp_nonce_field('wem_ps_save_image', 'wem_ps_image_nonce');
        $id = absint(get_post_meta($post->ID, self::IMG_META, true));
        $filename = $id && wp_attachment_is_image($id) ? wp_basename((string) get_attached_file($id)) : '';
        echo '<div class="wem-ps-image-wrap"><div class="wem-ps-image-details"><div class="wem-ps-preview">';
        if ($id && wp_attachment_is_image($id)) { echo wp_get_attachment_image($id, 'medium'); }
        echo '</div><span class="wem-ps-filename" aria-live="polite">' . esc_html($filename) . '</span></div><input type="hidden" name="wem_ps_image_id" class="wem-ps-image-id" value="' . esc_attr($id) . '">';
        echo '<p><button type="button" class="button wem-ps-upload">Choose / Replace image</button> <button type="button" class="button wem-ps-remove">Remove image</button></p></div>';
        echo '<p class="description">One image per Surface. Edit the title above to change its name. The Surface post ID remains stable when the image changes.</p>';
    }
    private function surface_image($id) {
        $img = absint(get_post_meta($id, self::IMG_META, true));
        return $img && wp_attachment_is_image($img) ? wp_get_attachment_image($img, 'thumbnail') : '<span class="wem-ps-no-image">No image</span>';
    }
    private function valid_save($post_id, $nonce_key, $nonce_action, $post_type) {
        if (!isset($_POST[$nonce_key]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_key])), $nonce_action)) { return false; }
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) { return false; }
        if (!current_user_can('edit_post', $post_id) || get_post_type($post_id) !== $post_type) { return false; }
        return true;
    }
    public function save_surface($post_id) {
        if (!$this->valid_save($post_id, 'wem_ps_image_nonce', 'wem_ps_save_image', self::CPT)) { return; }
        $id = isset($_POST['wem_ps_image_id']) ? absint(wp_unslash($_POST['wem_ps_image_id'])) : 0;
        if ($id && !wp_attachment_is_image($id)) { return; }
        if ($id) { update_post_meta($post_id, self::IMG_META, $id); }
        else { delete_post_meta($post_id, self::IMG_META); }
    }
    public function admin_assets($hook) {
        $screen = get_current_screen();
        if ($hook === self::$usage_hook) {
            wp_enqueue_style('wem-ps-admin', WEM_PS_URL . 'assets/css/admin.css', array(), WEM_PS_VERSION);
            wp_enqueue_script('wem-ps-usage', WEM_PS_URL . 'assets/js/usage.js', array(), WEM_PS_VERSION, true);
            return;
        }
        if (!$screen || $screen->id !== self::CPT) { return; }
        if (!in_array($hook, array('post.php', 'post-new.php'), true)) { return; }
        wp_enqueue_style('wem-ps-admin', WEM_PS_URL . 'assets/css/admin.css', array(), WEM_PS_VERSION);
        wp_enqueue_media();
        wp_enqueue_script('wem-ps-admin', WEM_PS_URL . 'assets/js/admin.js', array('jquery'), WEM_PS_VERSION, true);
    }
    public function columns($columns) {
        $ordered = array();
        if (isset($columns['cb'])) { $ordered['cb'] = $columns['cb']; }
        $ordered['wem_ps_img'] = 'Image';
        $ordered['title'] = isset($columns['title']) ? $columns['title'] : 'Surface Name';
        $ordered['wem_ps_id'] = 'Surface ID';
        if (isset($columns['date'])) { $ordered['date'] = $columns['date']; }
        foreach ($columns as $key => $label) {
            if (!isset($ordered[$key]) && !in_array($key, array('thumbnail', 'featured_image', 'post_thumbnail'), true)) { $ordered[$key] = $label; }
        }
        return $ordered;
    }
    public function column_content($column, $post_id) {
        if ($column === 'wem_ps_id') { echo esc_html($post_id); }
        if ($column === 'wem_ps_img') { echo $this->surface_image($post_id); }
    }
    public function register_frontend_assets() {
        wp_register_style('wem-ps-frontend', WEM_PS_URL . 'assets/css/frontend.css', array(), WEM_PS_VERSION);
    }
    public static function normalize_ids($raw) {
        if (is_string($raw)) { $raw = explode(',', $raw); }
        if (!is_array($raw)) { return array(); }
        $ids = array();
        foreach ($raw as $value) {
            if (!is_scalar($value)) { continue; }
            // Reject malformed values rather than silently converting them to another ID.
            $value = trim((string) $value);
            if (!preg_match('/^[0-9]+$/D', $value)) { continue; }
            $id = absint($value);
            if ($id && !in_array($id, $ids, true)) { $ids[] = $id; }
        }
        return $ids;
    }
    public static function render_ids($ids, $args = array()) {
        $ids = self::normalize_ids($ids);
        if (!$ids) { return ''; }
        $columns = isset($args['columns']) ? max(1, min(6, absint($args['columns']))) : 3;
        $cards = array();
        foreach ($ids as $id) {
            if (get_post_type($id) !== self::CPT || get_post_status($id) !== 'publish') { continue; }
            $image_id = absint(get_post_meta($id, self::IMG_META, true));
            if (!$image_id || !wp_attachment_is_image($image_id)) { continue; }
            $image = wp_get_attachment_image($image_id, 'medium_large', false, array('loading' => 'lazy', 'class' => 'wem-ps-card-image'));
            if (!$image) { continue; }
            $cards[] = '<figure class="wem-ps-card"><div class="wem-ps-card-media">' . $image . '</div><figcaption class="wem-ps-card-title">' . esc_html(get_the_title($id)) . '</figcaption></figure>';
        }
        if (!$cards) { return ''; }
        wp_enqueue_style('wem-ps-frontend');
        return '<div class="wem-ps-grid" style="--wem-ps-columns:' . esc_attr($columns) . '">' . implode('', $cards) . '</div>';
    }
    /**
     * Published surfaces ordered by stable numeric Surface ID (ascending).
     * The renderer checks image validity, so drafts and missing images are omitted.
     */
    public static function all_surface_ids() {
        return get_posts(array(
            'post_type'              => self::CPT,
            'post_status'            => 'publish',
            'posts_per_page'         => -1,
            'fields'                 => 'ids',
            'orderby'                => 'ID',
            'order'                  => 'ASC',
            'no_found_rows'          => true,
            'ignore_sticky_posts'    => true,
            'suppress_filters'       => false,
        ));
    }
    public function shortcode_ids($atts) {
        $atts = shortcode_atts(array('ids' => '', 'columns' => 3), $atts, 'wem_surfaces');
        $raw_ids = is_scalar($atts['ids']) ? trim((string) $atts['ids']) : '';
        $ids = $raw_ids === '' ? self::all_surface_ids() : $raw_ids;
        return self::render_ids($ids, array('columns' => $atts['columns']));
    }
}
if (!function_exists('wem_render_surfaces')) {
    function wem_render_surfaces($args = array()) {
        $args = wp_parse_args($args, array('ids' => null, 'columns' => 3));
        // Omit ids or pass an empty value to render all published surfaces.
        $ids = $args['ids'];
        if ($ids === null || $ids === '' || $ids === array()) {
            $ids = WEM_Product_Surfaces::all_surface_ids();
        }
        return WEM_Product_Surfaces::render_ids($ids, $args);
    }
}
