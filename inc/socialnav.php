<?php

/**
 * Social Navigation Menu
 */

/**
 * Register Social Icon menu
 */
add_action( 'init', 'activello_register_social_menu' );

function activello_register_social_menu() {
	register_nav_menu( 'social-menu', _x( 'Social Menu', 'nav menu location', 'activello' ) );
}

if ( ! function_exists( 'activello_social_icons' ) ) :
	/**
 * Display social links in footer and widgets
 *
 * @package activello
 */
	function activello_social_icons() {
		if ( has_nav_menu( 'social-menu' ) ) {
			wp_nav_menu(
				array(
					'theme_location'  => 'social-menu',
					'container'       => 'nav',
					'container_id'    => 'social',
					'container_class' => 'social-icons',
					'menu_id'         => 'menu-social-items',
					'menu_class'      => 'social-menu',
					'depth'           => 1,
					'fallback_cb'     => '',
					'link_before'     => '<i class="social_icon fa"><span>',
					'link_after'      => '</span></i>',
				)
			);
		}
	}
endif;

/* Activello Social Nav CSS */
function activello_social_css() {
	?>
	<style type="text/css">
		#social li{
			display: inline-block;
		}
		#social li,
		#social ul {
			border: 0!important;
			list-style: none;
			padding-left: 0;
			text-align: center;
		}
		#social li a[href*="twitter.com"] .fa:before,
		.fa-twitter:before {
			content: "\f099";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="facebook.com"] .fa:before,
		.fa-facebook-f:before,
		.fa-facebook:before {
			content: "\f09a";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="github.com"] .fa:before,
		.fa-github:before {
			content: "\f09b";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="/feed"] .fa:before,
		.fa-rss:before {
			content: "\f09e";
			font-family: "Font Awesome 7 Free";
			font-weight: 900;
		}
		#social li a[href*="pinterest.com"] .fa:before,
		.fa-pinterest:before {
			content: "\f0d2";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="plus.google.com"] .fa:before,
		.fa-google-plus:before {
			content: "\f0d5";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="linkedin.com"] .fa:before,
		.fa-linkedin:before {
			content: "\f0e1";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="youtube.com"] .fa:before,
		.fa-youtube:before {
			content: "\f167";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="instagram.com"] .fa:before,
		.fa-instagram:before {
			content: "\f16d";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="flickr.com"] .fa:before,
		.fa-flickr:before {
			content: "\f16e";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="tumblr.com"] .fa:before,
		.fa-tumblr:before {
			content: "\f173";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="dribbble.com"] .fa:before,
		.fa-dribbble:before {
			content: "\f17d";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="skype.com"] .fa:before,
		.fa-skype:before {
			content: "\f17e";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="foursquare.com"] .fa:before,
		.fa-foursquare:before {
			content: "\f180";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="vimeo.com"] .fa:before,
		.fa-vimeo-square:before {
			content: "\f194";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="weheartit.com"] .fa:before,
		.fa-heart:before {
			content: "\f004";
			font-family: "Font Awesome 7 Free";
			font-weight: 900;
		}
		#social li a[href*="spotify.com"] .fa:before,
		.fa-spotify:before {
			content: "\f1bc";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
		#social li a[href*="soundcloud.com"] .fa:before,
		.fa-soundcloud:before {
			content: "\f1be";
			font-family: "Font Awesome 7 Brands";
			font-weight: 400;
		}
	</style><?php
}
add_action( 'wp_head', 'activello_social_css', 10 );
