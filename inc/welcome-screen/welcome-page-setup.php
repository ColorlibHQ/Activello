<?php

add_action( 'customize_register', 'activello_ws_customize_register' );

function activello_ws_customize_register($wp_customize){

	require_once get_template_directory() . '/inc/welcome-screen/custom-recommend-action-section.php';
		$wp_customize->register_section_type( 'Activello_Customize_Section_Recommend' );

		// Recomended Actions
		$wp_customize->add_section(
			new Activello_Customize_Section_Recommend(
				$wp_customize,
				'activello_recomended-section',
				array(
					'title'    => esc_html__( 'Recomended Actions', 'activello' ),
					'succes_text'	=> esc_html__( 'We\'re social', 'activello' ),
					'facebook' => 'https://www.facebook.com/colorlib',
					'twitter' => 'https://twitter.com/colorlib',
					'wp_review' => true,
					'priority' => 0
				)
			)
		);

}

add_action( 'customize_controls_enqueue_scripts', 'activello_welcome_scripts_for_customizer', 0 );

function activello_welcome_scripts_for_customizer(){
	wp_enqueue_style( 'cpotheme-welcome-screen-customizer-css', get_template_directory_uri() . '/inc/welcome-screen/css/welcome_customizer.css' );
	wp_enqueue_style( 'plugin-install' );
	wp_enqueue_script( 'plugin-install' );
	wp_enqueue_script( 'updates' );
	wp_add_inline_script( 'plugin-install', 'var pagenow = "customizer";' );
	wp_enqueue_script( 'cpotheme-welcome-screen-customizer-js', get_template_directory_uri() . '/inc/welcome-screen/js/welcome_customizer.js', array( 'customize-controls' ), '1.0', true );
}

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
			"help"        => '<a target="_blank"  href="https://colorlibvault-divilabltd.netdna-ssl.com/activello-content.xml">' . __( 'Posts', 'activello' ) . '</a>, 
							   <a target="_blank"  href="https://colorlibvault-divilabltd.netdna-ssl.com/activello-widgets.wie">' . __( 'Widgets', 'activello' ) . '</a>',
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