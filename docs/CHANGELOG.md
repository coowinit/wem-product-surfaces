## Unreleased

- Frontend surface cards with non-empty labels now display before unlabeled cards. Order within each group remains stable. No shortcode or data migration required.

## v1.1.0 — 2026-10-21

- 新增可选单行 Surface Label 字段，独立保存到 Post Meta；旧色卡无需迁移。
- 前端标签绝对定位于图片左上角（10px / 10px），无标签时不输出元素。
- 默认桌面两列，保留 `columns` 自定义和移动端两列；标题居中、图片维持原图比例。
- 添加不依赖 Elementor 的原图弹窗（原图 URL、标题说明、Esc/按钮/遮罩关闭）。
- 更新后台 Usage、列表标签列、README 与测试清单。

# Changelog

## v1.0.0 — Stable release candidate

- Finalized site-wide `[wem_surfaces]` API and PHP renderer, retaining all existing Surface IDs and images.
- Improved ID validation so malformed tokens are ignored instead of coerced into other IDs.
- Hardened the admin filename fallback against malformed URL encoding.
- Refreshed README, regression checklist and release documentation.
- No schema changes, no product CPT settings, and no change to the existing swatch HTML/CSS.

## v0.1.4

- Added **Surface Library → Usage** with copyable Shortcode and PHP examples.

## v0.1.3

- `[wem_surfaces]` displays all published Surfaces with valid images; explicit IDs preserve listed order.

## v0.1.2

- Simplified to direct Surface ID Shortcode / PHP calls; removed product-association UI and API.

## v0.1.1

- Added image filename beside the preview, improved card aspect ratios, cleaned Surface list columns.

## v0.1.0

- First development version of the Surface Library.
