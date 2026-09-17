# About Theme

* Theme Name: Activello
* Theme URI: https://colorlib.com/wp/Activello/
* Version: 1.6.2
* Tested up to: WP 7.0

```
* Author: Colorlib
* Author URI: https://colorlib.com/
* License: GNU General Public License v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Activello theme, Copyright 2015-2026 Colorlib
* Activello WordPress theme is distributed under the terms of the GNU GPL
* Activello is based on Underscores https://underscores.me/, (C) 2012-2026 Automattic, Inc.
```

---

## Credits
Unless otherwise specified, all the theme files, scripts and images are licensed under GPLv2 license

Activello theme uses:
* FontAwesome (https://fontawesome.com/) licensed under the SIL OFL 1.1 (https://scripts.sil.org/OFL)
* Bootstrap 3.4.1 and GLYPHICONS Halflings (http://getbootstrap.com/) licensed under MIT license (https://github.com/twbs/bootstrap/blob/master/LICENSE)
* WP-Bootstrap-NavWalker licensed under the GPLv2 license (https://www.gnu.org/licenses/gpl-2.0.html)
* FlexSlider 2.7.0 by WooThemes licensed under the GPLv2 license (https://www.gnu.org/licenses/gpl-2.0.html)
* Unless otherwise specified, all images are created by Colorlib

### Description

Activello is a clean and minimal WordPress blog theme with premium look and feel well suited for food, fashion, travel, lifestyle, sports and any other awesome blogs. This theme features WooCommerce integration that allows you to create fully functional eCommerce website side by side with your blog. This theme has several customization options that are available WordPress Theme Customizer. Theme is also multilingual ready and translated in several languages. This awesome blog theme is also SEO friendly helping you to achieve the highest positions on Google. Activello is the only WordPress blog theme you will ever need.

For questions, comments or bug reports, visit Colorlib support forum (http://colorlib.com/wp/forums).

### Installation

You can install the theme through the WordPress installer under "Themes" > "Install themes" by searching for "Activello".

Alternatively you can download the file, unzip it and move the unzipped contents to the "wp-content/themes" folder of your WordPress installation. You will then be able to activate the theme.

Afterwards you can continue theme setup and customization via WordPress Dashboard - Appearance - Theme Options. For detailed theme documentation, please visit http://colorlib.com/wp/support/activello

### Theme Features

* Bootstrap 3 integration
* Responsive design
* Unlimited color variations
* SEO friendly
* WordPress Customizer
* Image centric approach
* Internationalized & localization
* Drop-down Menu
* Cross-browser compatibility
* Threaded Comments
* Gravatar ready
* Featured slider
* Font Awesome icons

### Documentation

Theme documentation is available on https://colorlib.com/wp/support/activello

#### Changelog
= 1.6.2 =
* Removed KB Support from the recommended plugins. WordPress.org closed it on 2025-04-03 over a security issue
* Corrected the capitalisation of WordPress in the French and Romanian translation files

= 1.6.1 =
* The no-js class swap is now printed from a wp_head hook (priority 0, still ahead of the stylesheets) instead of being hardcoded in header.php, so child themes and plugins can remove it
* Footer credit links use https

= 1.6.0 =
* Removed the Epsilon framework: the Customizer toggles are now a small theme-owned control with identical appearance, and saved settings are untouched (Epsilon's upstream repository no longer exists, which also made fresh git clones of this theme unusable)
* The repository no longer uses git submodules -- cloning or downloading from GitHub now just works
* Rebuilt the About Activello screen on core admin markup; the Recommended Plugins tab now renders WordPress' own plugin cards with details modals
* Removed the never-satisfiable "required actions" importer nag and its notification system
* Regenerated the translation template (181 strings, zero extraction warnings) and refreshed all 26 bundled translations
* Passed Theme Check: development files no longer ship with the theme, the 1.1 MB screenshot is now an optimized 318 KB JPG and the welcome logo shrank from 778 KB to 11 KB
* Verified WooCommerce shop, product, cart, checkout and my-account templates on WordPress 7.0 / PHP 8.5
* Removed the obsolete Grunt/Travis toolchain
* Corrected the style.css theme headers for the WordPress.org directory: added the missing "Requires at least" header, removed tags the theme cannot back up (rtl-language-support without an rtl.css, footer-widgets with no footer widget areas, accessibility-ready), added the e-commerce tag, refreshed the description and aligned the licence statement (GPL v2 or later) across style.css and the readme

= 1.5.0 =
* Security: added nonce and capability checks to the welcome-screen AJAX handlers, which previously ran without either
* Security: removed unused plugin activate/deactivate handlers that ran on admin_init, and moved the demo front-page setter onto a proper authenticated AJAX action
* Fixed the Bootstrap version mix: the theme now ships genuine Bootstrap 3.4.1 CSS and JS (includes the CVE-2019-8331 fix) instead of 3.3.7 CSS with 4.x JS and a hand-patched mobile menu
* Fixed the front-page slider failing to initialise when jQuery Migrate is disabled; updated FlexSlider from 2.6.3 to 2.7.0
* Rewrote theme JavaScript in plain DOM APIs -- theme scripts no longer depend on jQuery, load in the footer, and honour prefers-reduced-motion
* Removed Modernizr and the legacy IE conditional-comment markup
* Escaped remaining output across templates, widgets and admin screens; colours are re-validated at output time
* Fixed PHP 8.5 deprecations and the WordPress 6.7+ early-translation notice; verified with zero notices on WordPress 7.0 / PHP 8.5
* Added block editor support: wp-block-styles, align-wide, responsive-embeds and an editor stylesheet matching the front end
* All theme assets are now versioned from the theme version for reliable cache busting

= 1.4.9 =
* Fixed Epsilon_Section_Recommended_Actions class loading in customizer
* Added missing required header information in style.css
* Fixed CSS syntax error in style.css
* Updated theme version and compatibility information
* Improved theme stability and performance

= 1.4.8 =
* Fixed translation loading issue by properly initializing translations after WordPress init
* Moved welcome screen setup to load after init hook
* Fixed Epsilon_Control_Toggle class loading in customizer
* Improved theme compatibility with WordPress 6.8
* Added support for block styles and wide blocks
* Enhanced accessibility features
* Updated theme tags to reflect new features

= 1.4.7 =
* Fixed customizer controls
* Improved theme compatibility with WordPress 6.7
* Enhanced security features
* Updated theme dependencies

= 1.4.6 =
* Fixed responsive issues
* Improved theme compatibility with WordPress 6.6
* Enhanced performance
* Updated theme dependencies

= 1.4.5 = 
* Improved Escaping

= 1.4.4 = 
* Improved Escaping

= 1.4.3 =
* Compatibility with jQuery 3.0

= 1.4.2 =
* Sanitization fix

= 1.4.1 =
* Security Fix

= 1.4.0 =
* Improved accesibility with keyboard navigation
* Updated list of recommended plugins

= 1.3.8 =
* Removed subject tags, only kept 3

= 1.3.4
* Structured data missing hatom author
* Allow theme to display more than 2 sub level menus

= 1.3.3
* Fixed search functionality

= 1.3.2
* Added a new Blog Layout
* Added option to show all categories in the blog page
* Fixed mobile menu
* Integrated with Travis
* Added a notice inside admin dashboard so users know they need to regenerate thumbnails

= 1.3.0 - 16.05.2017

* Fixed slider & JetPack Photon integration
* Added Epsilon Framework as a git sub-module
* Fixed image serving - we were serving larger images than necessary

= 1.2.0 - 08.03.2017

* Added Welcome Screen
* Added Customizer Documentation Section
* Fixed search menu css
* Added for customizer colors defaults
* Fixed Social Widget title bug
* Fixed responsive menu css
* Added logo max height
* Added native WordPress Additional CSS section
* Activello functions now are pluggable.
* Fixed Woocommerce related tab issue
* Added dates for comments
* Fixed WordPress gallery css issue
* Fixed categories html bug

= 1.1.0 - 11.10.2016 =

* Updated Bootstrap to 3.3.7
* Updated Font Awesome to 4.6.3
* Updated FlexSlider to 2.6.3

= 1.1.0 - 11.10.2016 =

* Added Link to documentation
* Fixed compatibility errors with the WP 4.6 and PHP 7.
* Now you can use unlimited number of slides on Slider
* Other minor code tweaks and improvements
* Added French translation thanks to Eddy Lelièvre-Berna
* Added Greek translation thanks to Tsakman
* Added Slovak translation thanks to Marek

= 1.0.3 - 28.06.2016 =

* Added TGMPA & Kiwi Social Share Plugin
* Updated theme tags

= 1.0.2 - 17.02.2016 =

* Prefixed functions
* Added missing translations
* Escaped translation strings
* Updated libraries
* Added missing untouched libraries and scripts
* Added licensing information
* Theme Documentation now available on https://colorlib.com/wp/support/activello

= 1.0.1 - 11.02.2016 =

* Removed Instagram widget which where no longer in use.
* Improved theme translation

= 1.0 - 06.11.2015 =

* Initial release
