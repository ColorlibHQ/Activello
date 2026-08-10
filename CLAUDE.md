# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

Activello — a free classic (non-block) WordPress blog theme by Colorlib, v1.6.0, derived from Underscores (_s) and built on **Bootstrap 3.4.1**. Text domain: `activello`. Verified against **WordPress 7.0 on PHP 8.5**. WooCommerce-compatible.

This is a WordPress theme folder, not an application project: there is no build toolchain committed (the old Grunt 0.4 / Travis stack was deleted in 1.6.0 — Travis no longer exists and that Grunt stack cannot run on modern Node). Files ship exactly as they sit on disk.

## Working on the theme

- **No build step.** `style.css` and `assets/css/*.css` are edited directly — there is no SCSS source. `assets/js/functions.js` ships unminified and is what's enqueued; vendor files (`bootstrap.*`, `flexslider.*`, `font-awesome.*`) are stock upstream builds — never hand-edit them.
- **Version** lives in the `style.css` header and is read once into the `ACTIVELLO_VERSION` constant (functions.php), which cache-busts every theme asset. Bump the header and every asset URL follows — never hardcode a version in an enqueue. Version must also be bumped in `readme.md`/`readme.txt` (kept identical; both carry the changelog).
- **To run it**, symlink or copy this folder into a WordPress install's `wp-content/themes/`. The verification lab used for the 1.5.0/1.6.0 work is `/Users/silkalns/Projects/colorlib-theme-lab/wp` (SQLite drop-in, `php -S 127.0.0.1:8099` from `wp/`, current wp-cli phar needed for PHP 8.5 — the lab's own `wp-cli.phar` is too old). This theme is symlinked there as `wp-content/themes/activello`.
- **i18n**: regenerate with `wp i18n make-pot . languages/activello.pot --domain=activello`, then `msgmerge --update --backup=none <locale>.po languages/activello.pot` and `msgfmt` each locale (26 bundled). Every placeholder string needs a `translators:` comment *inside the PHP block* or extraction warns.
- **Release zip**: `zip -r activello.zip activello/ -x "activello/.git/*" "activello/CLAUDE.md"` from the parent directory — everything else in the folder is meant to ship. Theme Check passes as of 1.6.0 (0 required, 0 warnings); keep it that way — no dev files (shell scripts, rulesets, CI configs) in the theme root, images compressed (`screenshot.jpg` quality 92, 1200x900).

## Security model (hardened in 1.5.0 — keep these invariants)

- **Every AJAX handler needs a capability check *and* `check_ajax_referer()`.** The welcome screen's nonce is theme-owned (`activello_welcome_nonce`, localized into `activelloWelcomeScreenObject`). Never hook privileged work to `admin_init` — admin-ajax.php fires it *before* authentication.
- **Colours printed into the wp_head `<style>` block go through `activello_css_color()`** (inc/extras.php), which re-validates at output time; stored mods from old versions may hold arbitrary text. The Customizer sanitizer `activello_sanitize_hexcolor()` returns `''` (never the raw input) on failure.
- Anything rendering user-supplied data (tag names, titles, plugin-API fields) must be escaped — that sweep is done; keep new output escaped.

## Architecture

- **functions.php is the hub**: `ACTIVELLO_VERSION`, theme supports (including block-editor supports + `assets/css/editor-style.css`), image sizes (`activello-featured/slider/thumbnail/medium/big`), enqueues, and requires for everything in `inc/`.
- **Option-list globals load on `init`** (`activello_setup_globals()`): `$site_layout` and `$header_show` translate their labels, and translating earlier trips WP 6.7+'s `_load_textdomain_just_in_time` notice on every request. Anything reading them must run on `init` or later.
- **Theme mods** (`activello_*`, plus `accent_color`/`social_color`/`social_hover_color`) are declared in `inc/customizer.php` and take effect in `inc/extras.php`: body classes for sidebar layout, blog-layout selection (`template-parts/content.php` vs `content-grid.php`), the FlexSlider markup (`activello_featured_slider()`), and the inline colour CSS on `wp_head`. Per-post layout override comes from the `site_layout` meta (inc/metaboxes.php), resolved in header.php.
- **Gotcha:** `activello_featured_hide == 1` means the slider is *enabled* (despite the name). Slider assets (flexslider CSS/JS + `assets/js/flexslider-custom.js`) load only on the front page with that mod on.
- **Customizer toggles are first-party** (1.6.0): `Activello_Customize_Toggle_Control` (inc/class-activello-customize-toggle-control.php) is presentation-only — sanitization lives in each setting's `sanitize_callback`, never in the control. `Epsilon_Control_Toggle` survives as a deprecated alias for child themes; don't use it in new code or remove it without a major version. Switch CSS: `assets/css/customizer.css`, loaded only on `customize_controls_enqueue_scripts`. The Epsilon framework itself is gone and its upstream (MachoThemes) deleted — never reintroduce it.
- **Welcome screen** (`inc/welcome-screen/`): "About Activello" under Appearance. Tabs render via `get_template_part()` with a tab allowlist; the Recommended Plugins tab uses core's plugin-card markup (core plugin-install styles — the theme adds no CSS for it) and renders in a bare `.wrap` because `about.css` inflates type inside `.about-wrap`. The required-actions list ships empty (`welcome-page-setup.php`) — its tab exists only by URL.
- **Navigation**: `Activello_Wp_Bootstrap_Navwalker` emits BS3 markup; `.activello-dropdown` spans are the mobile sub-menu toggles handled in functions.js; `activello_make_top_level_menu_clickable()` (inc/extras.php) injects a small vanilla-JS snippet on `wp_footer` for desktop.
- **Widgets**: Social, Recent Posts, Categories in `inc/widgets/`, registered in `activello_widgets_init()`. One sidebar (`sidebar-1`).

## Front-end JavaScript

`assets/js/functions.js` is **plain DOM APIs, no jQuery**, enqueued in the footer with no jQuery dependency. It keeps `window.ActivelloIsMobile` and `window.generateMobileMenu` as globals because child themes may call them, reproduces jQuery's `swing` easing for scroll-to-top, and honours `prefers-reduced-motion`. The `no-js`→`js` class swap is a one-line inline script in header.php (Modernizr is gone).

FlexSlider is a jQuery plugin, so `flexslider-custom.js` keeps its jQuery dependency and uses `$( window ).on( 'load' )` — the removed-in-jQuery-3 `.load()` shorthand only worked via jQuery Migrate, so the slider silently died wherever Migrate was dequeued. Bootstrap's JS also still requires jQuery; only theme-owned code dropped it.

**Do not mix Bootstrap majors.** Until 1.5.0 the theme shipped BS 3.3.7 CSS with BS 4.x JS against BS3 markup, held together by a hand-patched `.navbar-collapse.collapse:not(.show)` rule. All four Bootstrap files are now stock 3.4.1 (byte-identical to the official dist, includes the CVE-2019-8331 fix).

## Verifying changes

No committed test suite; regressions are visual and silent. The bar set by 1.5.0/1.6.0:

- WP 7.0 / PHP 8.5 with `WP_DEBUG` + `WP_DEBUG_LOG` on: sweep home, single (incl. password-protected), page, category, tag, author, search, empty search, 404, feed, attachment — plus admin: all welcome tabs, Customizer, widgets, block editor. Target is an **empty debug.log**.
- Exercise option-driven paths: slider on/off, blog layout, sidebar positions, all colour pickers.
- `phpcs --standard=PHPCompatibilityWP --runtime-set testVersion 8.5` reports 0 errors/0 warnings.
- Theme Check: 0 REQUIRED / 0 WARNING (remaining advisories: block styles/patterns suggestions, the intentional inline no-js script, credit links).
- WooCommerce: shop, product, cart, checkout, my-account render clean (declared supports incl. gallery zoom/lightbox/slider).

## Conventions

- Every function is prefixed `activello_`; template-level ones are wrapped in `if ( ! function_exists() )` so child themes can override. Preserve both.
- WordPress coding standards: tabs, spaces inside parens, `esc_html__()`/`esc_attr()` at output.
- Assets are conditionally enqueued (slider assets only when rendering) and versioned from `ACTIVELLO_VERSION` or the real library version.
