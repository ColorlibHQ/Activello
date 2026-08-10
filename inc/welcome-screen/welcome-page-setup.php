<?php
/**
 * Note: the Epsilon-powered "Recomended Actions" and "Pro" Customizer sections
 * were removed along with the Epsilon framework in 1.6.0. The same content
 * (recommended actions, recommended plugins, documentation links) lives on the
 * About Activello screen under Appearance.
 */

// Load the system checks ( used for notifications )
require get_template_directory() . '/inc/welcome-screen/class-mt-notify-system.php';

// Welcome screen
if ( is_admin() ) {
	global $activello_required_actions, $activello_recommended_plugins;
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
	 * id - unique id; required
	 * title
	 * description
	 * check - check for plugins (if installed)
	 * plugin_slug - the plugin's slug (used for installing the plugin)
	 *
	 */


	$activello_required_actions = array(
		array(
			'id'          => 'activello-req-ac-install-wp-import-plugin',
			'title'       => MT_Notify_System::wordpress_importer_title(),
			'description' => MT_Notify_System::wordpress_importer_description(),
			'check'       => MT_Notify_System::has_import_plugin( 'wordpress-importer' ),
			'plugin_slug' => 'wordpress-importer',
		),
		array(
			'id'          => 'activello-req-ac-install-wp-import-widget-plugin',
			'title'       => MT_Notify_System::widget_importer_exporter_title(),
			'description' => MT_Notify_System::widget_importer_exporter_description(),
			'check'       => MT_Notify_System::has_import_plugin( 'widget-importer-exporter' ),
			'plugin_slug' => 'widget-importer-exporter',
		),
	);
	require get_template_directory() . '/inc/welcome-screen/class-activello-welcome.php';
}// End if().
