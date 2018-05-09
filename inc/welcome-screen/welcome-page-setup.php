<?php

add_action( 'customize_register', 'activello_ws_customize_register' );

function activello_ws_customize_register( $wp_customize ) {

	// Recomended actions
	global $activello_required_actions, $activello_recommended_plugins;

	$customizer_recommended_plugins = array();
	if ( is_array( $activello_recommended_plugins ) ) {
		foreach ( $activello_recommended_plugins as $k => $s ) {
			if ( $s['recommended'] ) {
				$customizer_recommended_plugins[ $k ] = $s;
			}
		}
	}

	$theme_slug = 'activello';

	$wp_customize->add_section(
		new Epsilon_Section_Recommended_Actions(
			$wp_customize,
			'epsilon_recomended_section',
			array(
				'title'                        => esc_html__( 'Recomended Actions', 'activello' ),
				'social_text'                  => esc_html__( 'We are social', 'activello' ),
				'plugin_text'                  => esc_html__( 'Recomended Plugins', 'activello' ),
				'actions'                      => $activello_required_actions,
				'plugins'                      => $customizer_recommended_plugins,
				'theme_specific_option'        => $theme_slug . '_show_required_actions',
				'theme_specific_plugin_option' => $theme_slug . '_show_recommended_plugins',
				'facebook'                     => 'https://www.facebook.com/colorlib',
				'twitter'                      => 'https://twitter.com/colorlib',
				'wp_review'                    => true,
				'priority'                     => 0,
			)
		)
	);

	$wp_customize->add_section(
		new Epsilon_Section_Pro(
			$wp_customize,
			'epsilon-section-pro',
			array(
				'title'       => esc_html__( 'Activello', 'activello' ),
				'button_text' => esc_html__( 'Documentation', 'activello' ),
				'button_url'  => 'https://colorlib.com/wp/support/activello/',
				'priority'    => 0,
			)
		)
	);

}

// Load the system checks ( used for notifications )
require get_template_directory() . '/inc/welcome-screen/class-mt-notify-system.php';

// Welcome screen
if ( is_admin() ) {
	global $activello_required_actions, $activello_recommended_plugins;
	$activello_recommended_plugins = array(
		'colorlib-login-customizer' => array(
			'recommended' => true,
		),
		'kiwi-social-share' => array(
			'recommended' => true,
		),
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
		array(
			'id'          => 'activello-req-ac-download-data',
			'title'       => esc_html__( 'Download theme sample data', 'activello' ),
			'description' => esc_html__( 'Head over to our website and download the sample content data.', 'activello' ),
			'help'        => '<a target="_blank"  href="https://colorlibvault-divilabltd.netdna-ssl.com/activello-content.xml">' . __( 'Posts', 'activello' ) . '</a>, 
							   <a target="_blank"  href="https://colorlibvault-divilabltd.netdna-ssl.com/activello-widgets.wie">' . __( 'Widgets', 'activello' ) . '</a>',
			'check'       => MT_Notify_System::has_content(),
		),
		array(
			'id'    => 'activello-req-ac-install-data',
			'title' => esc_html__( 'Import Sample Data', 'activello' ),
			'help'  => '<a class="button button-primary" target="_blank"  href="' . self_admin_url( 'admin.php?import=wordpress' ) . '">' . __( 'Import Posts', 'activello' ) . '</a> 
							   <a class="button button-primary" target="_blank"  href="' . self_admin_url( 'tools.php?page=widget-importer-exporter' ) . '">' . __( 'Import Widgets', 'activello' ) . '</a>',
			'check' => MT_Notify_System::has_import_plugins(),
		),
	);
	require get_template_directory() . '/inc/welcome-screen/class-activello-welcome.php';
}// End if().
