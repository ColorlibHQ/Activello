<?php
/**
 * Welcome Page Setup
 *
 * Note: the Epsilon-powered "Recomended Actions" and "Pro" Customizer sections
 * were removed along with the Epsilon framework in 1.6.0. The same content
 * (recommended plugins, documentation links) lives on the About Activello
 * screen under Appearance.
 */

// Include the Activello_Welcome class
require_once get_template_directory() . '/inc/welcome-screen/class-activello-welcome.php';

// Initialize the welcome screen
if ( is_admin() ) {
	global $activello_required_actions, $activello_recommended_plugins;

	// Define recommended plugins
	$activello_recommended_plugins = array(
		'kali-forms'                       => array( 'recommended' => true ),
		'modula-best-grid-gallery'         => array( 'recommended' => true ),
		'fancybox-for-wordpress'           => array( 'recommended' => false ),
		'simple-custom-post-order'         => array( 'recommended' => false ),
		'colorlib-404-customizer'          => array( 'recommended' => false ),
		'colorlib-coming-soon-maintenance' => array( 'recommended' => false ),
		'colorlib-login-customizer'        => array( 'recommended' => false ),
		'kb-support'                       => array( 'recommended' => false ),
		'rsvp'                             => array( 'recommended' => false ),
	);

	/*
	 * Required actions used to push the WordPress/widget importers via the
	 * MT_Notify_System checks, which keyed off widget areas and demo posts this
	 * theme never shipped -- so the "required" badge nagged forever. The
	 * importers are only useful during a demo import, so the list is now empty
	 * and the importers can be installed from the demo documentation instead.
	 */
	$activello_required_actions = array();

	// Initialize the welcome screen
	new Activello_Welcome();
}
