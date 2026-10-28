# WEM Product Surfaces

> 轻量级 WordPress 产品表面色卡管理插件：集中维护木塑（WPC）等产品的表面纹理图片，通过一个 Shortcode 或 PHP 函数，在全站任意支持简码的位置展示全部或指定 Surface。

**最新 GitHub Release：** `v1.1.1`（插件主文件版本号仍需同步）  
**运行要求：** WordPress 6.0+、PHP 7.4+  
**适用场景：** 木塑地板、墙板、围栏等产品的表面花纹、色卡和饰面展示

## 1. 功能概览

- **统一管理：** 后台 `Surface Library` 集中新增、编辑、发布、取消发布或移入回收站。
- **单图色卡：** 每个 Surface 包含一个独立的 **Surface ID**、一个名称（文章标题 Title）、一张媒体库图片和可选的单行标签（例如 NEW）。
- **点击放大：** 点击色卡图片弹出媒体库原始图片，支持 Esc、关闭按钮和遮罩关闭；不依赖 Elementor 或第三方弹窗库。
- **图片角标：** 可填写 NEW、HOT 等短标签，前台位于图片左上角（左 10px、上 10px），不填写则不显示。
- **标签优先展示：** v1.1.1 起，带非空标签的色卡自动排在无标签色卡之前；两组内部仍保持原始顺序，无需手动重排 Shortcode ID。
- **文件名识别：** Surface 编辑页面的图片预览旁显示真实图片文件名；更换图片时同步更新。
- **灵活调用：** 一个 `[wem_surfaces]` 简码同时支持展示全部色卡、指定 ID 和指定列数。
- **全站通用：** 可用于文章、页面、自定义内容类型、Elementor 页面及支持 Shortcode 的模板位置；**无需选择产品 CPT**。
- **响应式展示：** 图片按原始比例展示；默认桌面两列，列数可配置，移动端使用响应式布局。
- **内置帮助：** 后台 `Surface Library → Usage` 提供简码和 PHP 示例以及复制按钮。

### 核心概念

| 名称 | 含义 | 是否用于调用 |
| --- | --- | --- |
| **Surface ID** | WordPress 为每条 Surface 分配的唯一文章 ID | **是**，填入简码 `ids` |
| **Surface Title** | 管理员输入的色卡/表面名称 | 前台显示在图片下方 |
| **Surface Label** | 可选单行文本，如 NEW，最多 40 个字符 | 显示在图片左上角，并作为前端优先排序依据 |
| **Image Attachment ID** | WordPress 媒体库中的图片 ID | 不用于 `ids` 参数 |
| **图片文件名** | 如 `Bold-Grain.webp` | 仅在后台图片预览旁帮助辨认 |

例如：Surface `#3300` 的标题为 `Bold-Grain`，当前图片文件名为 `Bold-Grain.webp`。更换图片或修改标题后，**Surface ID 仍不变**，已有的简码无需修改。

## 2. 安装与升级

1. 建议先备份 WordPress 数据库及 `wp-content/uploads/`，并优先在测试网站验证。
2. 进入 **插件 → 安装插件 → 上传插件**，上传与当前版本对应的插件 ZIP 包。
3. 如果已有旧版本，选择**替换现有插件**，不要同时启用两份同名插件。
4. 启用后进入 **Surface Library → All Surfaces**，确认原有色卡仍在。
5. 按照下方步骤创建色卡，并到 `Usage` 页面查看调用示例。

**数据兼容：** 从 v0.1.x 升级不要求重新创建 Surface。历史产品关联元数据 `_wem_surface_ids` 及旧选项 `wem_ps_product_types` 不会被本插件迁移或主动删除，但当前版本也**不再使用**它们。

## 3. 后台使用方法

### 3.1 创建色卡

1. 打开 **Surface Library → Add New Surface**。
2. 在标题栏输入 **Surface Title**，例如 `Starshine`。
3. 在 **Surface Label (Optional)** 中按需填写 `NEW` 等标签，不需要可留空。
4. 在 **Surface Image** 区域点击 **Choose / Replace Image**，从媒体库选择或上传一张图片。
5. 核对图片预览及旁边显示的实际文件名。
6. 点击 **Publish** 发布。
7. 回到 **All Surfaces**，在列表中查看对应的 **Surface ID**。

### 3.2 修改、替换和删除

- **修改标签：** 编辑 Surface Label 并更新，清空则隐藏前台角标；旧色卡无需迁移。
- **修改名称：** 编辑标题后更新；前台展示名称随之变化。
- **替换图片：** 选择新图后更新；只要 Surface ID 不变，引用该 ID 的简码会显示新图片。
- **移除图片：** 保存后，此 Surface 不会生成有效的前台图片卡片。
- **取消发布或移入回收站：** 该 Surface 不再参与前台展示。
- **删除 Surface：** 不会连带删除媒体库中的图片文件。

建议采用易于识别的文件名（例如 `Bold-Grain.webp`），但前台显示名称仍以 **Surface Title** 为准。

## 4. Shortcode 简码说明

| 使用目的 | 示例 |
| --- | --- |
| 展示所有有效色卡 | `[wem_surfaces]` |
| 展示一张指定色卡 | `[wem_surfaces ids="3310"]` |
| 展示指定的多张色卡 | `[wem_surfaces ids="3310,3300"]` |
| 全部色卡按桌面端 4 列展示 | `[wem_surfaces columns="4"]` |
| 指定色卡并按桌面端 2 列展示 | `[wem_surfaces ids="3310,3300" columns="2"]` |

**参数说明：**

- `ids`：可选，使用逗号分隔的 **Surface ID**。不填写或留空时，显示所有已发布且图片有效的色卡。指定 ID 时会去重并忽略无效 ID、草稿、回收站及缺失有效图片的 Surface。
- `columns`：可选，桌面端列数为 **1–6**，默认 **2**；超出范围会限制到允许区间。当前移动端为 **2 列**，实际效果可能受主题 CSS 影响。

### 4.1 v1.1.1 标签优先排序

前端按照以下规则自动调整**展示顺序**，不会更改后台数据或修改 Shortcode：

1. `wem_ps_label` 非空（去除首尾空格后）的色卡优先展示，例如 NEW、HOT。
2. 有标签的色卡之间，保持输入 `ids` 时的先后顺序。
3. 无标签的色卡排在后面，彼此仍保持原有顺序。
4. 未指定 `ids` 时，先按 Surface ID 升序获取全部已发布色卡，再执行相同的标签优先分组。
5. 所有色卡都没有标签时，展示顺序不变；无效或无图片的色卡仍不会显示。

**示例：** 假设 Surface `8701` 和 `8703` 填写了 `NEW` 标签：

```text
输入：[wem_surfaces ids="8707,8706,8705,8704,8703,8702,8701"]
显示顺序：8703 → 8701 → 8707 → 8706 → 8705 → 8704 → 8702
```

> 注意：`ids` 指 **Surface ID**，不是产品文章 ID，也不是媒体库图片 ID。插件不提供 `[wem_product_surfaces]` 简码，也不会自动根据产品关联色卡。

## 5. Elementor 中使用

1. 打开 Elementor 编辑器，可编辑普通页面、产品内容页面或 Theme Builder 模板。
2. 在需要展示色卡的位置添加 **Shortcode（简码）** 小工具。
3. 输入 `[wem_surfaces]`，或输入带 `ids` 的简码。
4. 保存并检查**实际前台页面**。Elementor 编辑器画布宽度与正式前台可能不同。

**无需设置内容类型。** 插件的表面库与产品 CPT 相互独立，放在哪个可执行 Shortcode 的页面上，就在该位置展示。

## 6. PHP 主题模板调用

展示全部已发布且图片有效的色卡：

```php
if (function_exists('wem_render_surfaces')) {
    echo wem_render_surfaces();
}
```

只展示指定 Surface，并设置桌面端列数：

```php
if (function_exists('wem_render_surfaces')) {
    echo wem_render_surfaces([
        'ids'     => [3310, 3300],
        'columns' => 2,
    ]);
}
```

PHP 函数必须在 WordPress 加载插件后调用；`function_exists()` 判断可避免停用插件时主题报错。

## 7. 项目结构

```text
wem-product-surfaces/
├── wem-product-surfaces.php                  # 插件入口、版本信息
├── includes/
│   └── class-wem-product-surfaces.php       # CPT、后台管理、查询及渲染
├── assets/
│   ├── css/
│   │   ├── admin.css                        # 后台及 Usage 样式
│   │   └── frontend.css                     # 前台响应式色卡
│   └── js/
│       ├── admin.js                         # 媒体库选图及文件名更新
│       ├── lightbox.js                      # 无依赖图片弹窗
│       └── usage.js                         # Usage 示例复制
├── docs/
│   ├── CHANGELOG.md                         # 版本记录
│   └── TESTING.md                           # 发布前验收清单
└── README.md
```

## 8. 常见问题

**Q：为什么 `[wem_surfaces]` 没有显示任何图片？**  
A：检查是否已经发布 Surface、是否选择有效媒体库图片、Shortcode 是否放在支持解析简码的区域；必要时清理网站/页面缓存。

**Q：为什么 `ids="3300"` 没有显示？**  
A：确认 `3300` 是 **Surface ID** 而不是产品 ID 或图片 ID；对应 Surface 必须已发布且有有效图片。

**Q：后台上传的是 `Bold-Grain.webp`，前台显示什么名称？**  
A：前台显示 Surface 的 **Title**；文件名仅在后台用于识别当前图片。

**Q：为什么修改了色卡，前台仍显示旧图？**  
A：先确认 Surface 已更新，再清理页面缓存与 CDN 缓存；使用 WP Rocket、Cloudflare 等缓存时尤其需要注意。

**Q：可否在任意产品中显示不同色卡？**  
A：可以。直接在每个产品的 Elementor 页面填写各自的 `ids`，无需事先建立产品关联。

**Q：是否提供筛选、自动关联产品或分页？**  
A：v1.1.1 保留了点击图片查看原图的轻量弹窗，但暂不提供筛选、自动关联产品或分页。全量调用在色卡特别多时可能使页面变长，建议使用 `ids` 选择所需色卡。

**Q：为什么色卡顺序与 Shortcode 中的 ID 顺序不同？**  
A：从 v1.1.1 起，填写非空 Surface Label 的色卡会自动优先显示；同组内部仍保持原始顺序。清空标签并更新后，该色卡会回到无标签组。

## 9. 技术、安全与维护说明

- 使用 WordPress 原生 CPT、文章元数据和媒体库接口，**不创建自定义数据库表**。
- 保存图片关联时检查编辑权限、Nonce 和图片附件类型；输出时使用必要的转义与 WordPress 图片 API。
- 仅输出已发布并关联有效图片的 Surface。该插件不会额外限制媒体库图片 URL 的公开访问权限。
- Surface CPT 仅用于后台数据管理，不主动创建对外可访问的 Surface 详情页或新固定链接规则。
- 停用插件不会自动删除 Surface 记录和媒体库文件。
- 旧版 `[wem_product_surfaces]` 已移除，升级时应手动替换页面和模板中的旧调用。
- 正式发布前应按 [docs/TESTING.md](docs/TESTING.md) 完成目标站点回归测试；语法检查不能替代真实环境验证。

## 10. 版本记录

| 版本 | 主要变化 |
| --- | --- |
| v1.1.1 | 带标签色卡自动优先显示，同组稳定排序；无需变更既有短代码或数据 |
| v1.1.0 | 新增 Surface Label 角标、无依赖图片放大弹窗，并优化响应式展示 |
| v1.0.0 | 首个稳定版；加强 ID 输入校验、后台图片文件名容错，并完善发布文档 |
| v0.1.4 | 恢复 Usage 使用说明页，不再选择产品 CPT |
| v0.1.3 | `[wem_surfaces]` 支持无 `ids` 显示全部色卡 |
| v0.1.2 | 删除产品自动关联与 `[wem_product_surfaces]`，精简为 Surface ID 直接调用 |
| v0.1.1 | 图片文件名展示、图片比例与后台列表优化 |
| v0.1.0 | 初版 Surface Library 与前台渲染 |

详细变更参阅 [docs/CHANGELOG.md](docs/CHANGELOG.md)，正式版验收参阅 [docs/TESTING.md](docs/TESTING.md)。

## 11. 许可说明

当前安装包未附带独立的 `LICENSE` 文件。如计划对外开源或分发，请在仓库中明确添加所选许可证并同步相关说明。
