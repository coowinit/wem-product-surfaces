# WEM Product Surfaces

**Stable release:** `v1.0.0`  
**Requirements:** WordPress 6.0+, PHP 7.4+  
**Purpose:** Lightweight, reusable image swatches/surface finishes for WordPress and Elementor.

Each Surface is an independent `wem_surface` WordPress post with a stable **Surface ID**, a **Title** and one **Media Library image**. The front end displays the Title as its caption; the admin editor shows the actual image filename beside the preview. Changing an image or title does not change the Surface ID.

## Features

- Central **Surface Library** menu: add, edit, publish, unpublish or trash Surfaces.
- Choose/replace/remove one image per Surface; view its filename immediately in the editor.
- Surface list shows preview, title, Surface ID and date.
- One site-wide Shortcode: **`[wem_surfaces]`**, with optional `ids` and `columns`.
- PHP theme API: `wem_render_surfaces()`.
- Responsive grid, original image proportions, WordPress image sizing and lazy loading.
- **Surface Library → Usage** includes copyable examples. No CPT selection, product association, dedicated Elementor widget or new database tables.

## Install or upgrade

1. Back up the WordPress site and database; preferably test on staging first.
2. Upload the ZIP under **Plugins → Add New → Upload Plugin**. When upgrading, replace the existing plugin (do not run two copies).
3. Activate the plugin, then open **Surface Library → All Surfaces**.
4. Add a Surface with **Add New Surface**, enter a Title, choose a Media Library image, and **Publish**.
5. Copy the numeric **Surface ID** shown in the list, or open **Usage** for examples.
6. Add a WordPress **Shortcode** block or an Elementor **Shortcode** widget wherever the gallery should appear. Verify the published front end and clear caches as needed.

**Data preservation:** This update does not migrate or delete Surface records, media, IDs, legacy `_wem_surface_ids` product post meta or the unused `wem_ps_product_types` option. The latter two are not read by this plugin.

## Shortcode reference

| Code | Result |
| --- | --- |
| `[wem_surfaces]` | All published Surfaces with valid images, ordered by increasing Surface ID |
| `[wem_surfaces ids="3310"]` | One specific Surface |
| `[wem_surfaces ids="3310,3300"]` | These Surfaces, in exactly this input order |
| `[wem_surfaces columns="4"]` | All Surfaces, four desktop columns |
| `[wem_surfaces ids="3310,3300" columns="2"]` | Selected Surfaces, two desktop columns |

- `ids`: optional comma-separated numeric **Surface IDs**, not image attachment IDs or product IDs. Duplicates, invalid IDs, drafts, trashed items and Surfaces without valid images are ignored. An omitted or empty `ids` parameter means **all** published Surfaces.
- `columns`: desktop grid columns, clamped to **1–6** (default **3**); mobile grid defaults to **2** columns. Custom site CSS may override the grid.
- For selected IDs, display order follows the shortcode; for all, order is ascending by numeric Surface ID.
- The displayed caption is **Surface Title**, not the image filename. Actual filenames appear **only in the admin editor**.
- Shortcodes work where the host content supports WordPress shortcodes. Elementor editor previews may differ in width from the published site.

## PHP template API

```php
// All published Surfaces with a valid image.
echo wem_render_surfaces();

// Selected Surface IDs, in the given order.
echo wem_render_surfaces([
    'ids'     => [3310, 3300],
    'columns' => 2,
]);
```

Calls must run inside WordPress after the plugin is loaded. If your theme is also used without this plugin, guard direct calls with `function_exists('wem_render_surfaces')`.

## Elementor

Open a page, post, custom product or Theme Builder template. Drag the **Shortcode** widget into the desired section and paste `[wem_surfaces]` or `[wem_surfaces ids="..."]`. No product CPT registration or setting is required; the same Surface Library can be used across the site.

## File layout

```text
wem-product-surfaces/
├── wem-product-surfaces.php                 # Bootstrap and version
├── includes/class-wem-product-surfaces.php # CPT, admin, shortcode and PHP renderer
├── assets/css/admin.css                    # Surface editor / Usage
├── assets/css/frontend.css                 # Responsive grid
├── assets/js/admin.js                      # Media Library selection + filename
├── assets/js/usage.js                      # Copy examples
├── docs/TESTING.md                         # Release regression checklist
├── docs/CHANGELOG.md                       # Version history
└── README.md
```

## Compatibility and security

Uses standard WordPress CPT/post-meta/media APIs; image saving requires a nonce and `edit_post` capability, and checks the attachment is an image. Front-end entries are limited to published Surface posts with valid image attachments. Rendering escapes captions and column values; WordPress generates image markup. Files are not deleted when a Surface is trashed or the plugin is deactivated.

The plugin does **not** make its Surface CPT publicly queryable, and does **not** register new permalink routes. Image file URLs still follow your Media Library's existing access policy; this plugin does not add media access restrictions.

## Upgrading from experimental versions

- v0.1.2 removed `[wem_product_surfaces]` and `wem_render_product_surfaces()`; these do **not** return in v1.0.0. Replace old usages manually with `[wem_surfaces ids="..."]` or `wem_render_surfaces(['ids' => [...]])`.
- v0.1.3 added the all-Surfaces behavior for `[wem_surfaces]` without `ids`.
- v0.1.4 restored the read-only **Usage** page without CPT selection.
- v1.0.0 keeps the documented behavior and adds input-validation hardening and release documentation.

## Verification and known limitations

The v0.1.4 workflow was verified by the site owner in WordPress/Elementor. The v1.0.0 release package has been checked for PHP/JS syntax and archive integrity; **v1.0.0 should also be regression-tested on the target site before a public Release**. Refer to [docs/TESTING.md](docs/TESTING.md).

The plugin deliberately does not provide search/filter UI, color variation groups, automatic product association, image lightbox, automatic swatch sorting, or pagination. All-Surfaces output may be large for an extensive library; use `ids` to limit display when appropriate.

## License

No license file is included; choose and add an explicit license before distributing to third parties if needed.
