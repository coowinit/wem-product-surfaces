# WEM Product Surfaces — v1.0.0 release acceptance

Run on a staging site with the same WordPress, Elementor, theme, caching and image environment as production. **Mark each item after testing; static checks alone are insufficient.**

## Upgrade and preservation

- [ ] Back up database and `wp-content/uploads` before upgrading from v0.1.4.
- [ ] Replace old plugin with v1.0.0, without activating two copies.
- [ ] Existing Surface IDs, Titles and image assignments remain unchanged.
- [ ] No new product CPT selection or association options have appeared.

## Admin and data

- [ ] Surface Library list contains one Image column, Surface Title, Surface ID and Date.
- [ ] Add, publish, edit, draft, trash, and restore a Surface.
- [ ] Choose an image: preview and filename update immediately.
- [ ] Replace image and save: the Surface ID stays unchanged, filename persists.
- [ ] Remove image and save: front-end card is skipped.
- [ ] Usage page opens; all Shortcode/PHP Copy controls paste exactly the displayed values.
- [ ] Test with an editor-role account to verify only authorized edits work.

## Shortcodes and rendering

- [ ] `[wem_surfaces]` shows all published Surfaces with valid images, in increasing ID order.
- [ ] `[wem_surfaces ids="3310,3300"]` shows only those IDs, in that order (replace with actual valid IDs).
- [ ] `[wem_surfaces ids="3310,3310,3300"]` displays no duplicate card.
- [ ] `[wem_surfaces ids="abc,3310,1foo"]` ignores malformed tokens.
- [ ] `[wem_surfaces columns="4"]` uses four desktop columns; out-of-range values clamp to 1–6.
- [ ] Drafts, trashed records, unknown IDs, wrong post types and missing-image Surfaces are skipped.
- [ ] Gallery is absent (without notices) when no valid Surface exists.
- [ ] Surface Title (not attachment filename) appears below the photo.
- [ ] Natural image proportions are preserved, with no large blank backgrounds.
- [ ] Phone, tablet and desktop layouts have no unexpected horizontal overflow.
- [ ] Elementor editor preview and **published front end** both display the Shortcode.
- [ ] A normal page, custom product and Elementor template can use the same Shortcode.
- [ ] `wem_render_surfaces()` and explicit `ids` PHP calls render correctly inside WordPress.
- [ ] Clear WP Rocket / Cloudflare caches when CSS or results appear stale.

## Legacy and release checks

- [ ] Search all pages, Elementor templates and theme PHP for removed `wem_product_surfaces` / `wem_render_product_surfaces` calls and replace them where found.
- [ ] Verify old `_wem_surface_ids` metadata is left untouched and unused.
- [ ] Run PHP lint, JS syntax checks, ZIP integrity check, and inspect archive paths.
- [ ] Publish v1.0.0 only after the above target-site tests pass.
