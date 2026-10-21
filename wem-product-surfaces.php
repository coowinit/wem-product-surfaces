<?php
/**
 * Plugin Name: WEM Product Surfaces
 * Description: Reusable surface image library with direct ID-based shortcode and PHP rendering for Elementor and WordPress.
 * Version: 1.1.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: WEM
 * Text Domain: wem-product-surfaces
 */
if (!defined('ABSPATH')) { exit; }
define('WEM_PS_VERSION', '1.1.0');
define('WEM_PS_FILE', __FILE__);
define('WEM_PS_URL', plugin_dir_url(__FILE__));
require_once __DIR__ . '/includes/class-wem-product-surfaces.php';
add_action('plugins_loaded', array('WEM_Product_Surfaces', 'boot'));
