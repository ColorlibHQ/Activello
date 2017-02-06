<?php

// Load the system checks ( used for notifications )
require get_template_directory() . '/inc/welcome-screen/notify-system-checks.php';

// Welcome screen
if ( is_admin() ) {
	global $activello_required_actions, $activello_recommended_plugins;
	$activello_recommended_plugins = array(
		'kiwi-social-share' => array( 'recommended' => false ),
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
			"id"          => 'activello-req-ac-install-wp-import-plugin',
			"title"       => MT_Notify_System::wordpress_importer_title(),
			"description" => MT_Notify_System::wordpress_importer_description(),
			"check"       => MT_Notify_System::has_import_plugin( 'wordpress-importer' ),
			"plugin_slug" => 'wordpress-importer'
		),
		array(
			"id"          => 'activello-req-ac-install-wp-import-widget-plugin',
			"title"       => MT_Notify_System::widget_importer_exporter_title(),
			'description' => MT_Notify_System::widget_importer_exporter_description(),
			"check"       => MT_Notify_System::has_import_plugin( 'widget-importer-exporter' ),
			"plugin_slug" => 'widget-importer-exporter'
		),
		array(
			"id"          => 'activello-req-ac-download-data',
			"title"       => esc_html__( 'Download theme sample data', 'activello' ),
			"description" => esc_html__( 'Head over to our website and download the sample content data.', 'activello' ),
			"help"        => '<a target="_blank"  href="https://www.machothemes.com/sample-data/activello-lite-posts.xml">' . __( 'Posts', 'activello' ) . '</a>, 
							   <a target="_blank"  href="https://www.machothemes.com/sample-data/activello-lite-widgets.wie">' . __( 'Widgets', 'activello' ) . '</a>',
			"check"       => MT_Notify_System::has_content(),
		),
		array(
			"id"    => 'activello-req-ac-install-data',
			"title" => esc_html__( 'Import Sample Data', 'activello' ),
			"help"  => '<a class="button button-primary" target="_blank"  href="' . self_admin_url( 'admin.php?import=wordpress' ) . '">' . __( 'Import Posts', 'activello' ) . '</a> 
							   <a class="button button-primary" target="_blank"  href="' . self_admin_url( 'tools.php?page=widget-importer-exporter' ) . '">' . __( 'Import Widgets', 'activello' ) . '</a>',
			"check" => MT_Notify_System::has_import_plugins(),
		),
	);
	require get_template_directory() . '/inc/welcome-screen/welcome-screen.php';
}