<?php
/**
 * activello Theme Customizer
 *
 * @package activello
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function activello_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'activello_customize_register' );

/**
 * Options for WordPress Theme Customizer.
 */
function activello_customizer( $wp_customize ) {

    // Load custom section.
    require_once( trailingslashit( get_template_directory() ) . 'inc/customizer/custom-section.php' );

    // Register custom section types.
    $wp_customize->register_section_type( 'Activello_Customize_Section_Documentation' );

    /* Support & Documentation */
    $wp_customize->add_section(
        new Activello_Customize_Section_Documentation(
            $wp_customize,
            'activello_documentation',
            array(
                'title'    => esc_html__( 'Activello', 'activello' ),
                'documentation_text' => esc_html__( 'Documentation', 'activello' ),
                'documentation_url'  => 'https://colorlib.com/wp/support/activello/',
                'priority' => 1,
            )
        )
    );

    global $header_show;
    $wp_customize->add_setting('header_show', array(
            'default' => 'logo-text',
            'sanitize_callback' => 'activello_sanitize_radio_header'
        ));
    $wp_customize->add_control('header_show', array(
        'type' => 'radio',
        'label' => __('Show', 'activello'),
        'section' => 'title_tagline',
        'choices' => $header_show
    ));

        /* Main option Settings Panel */
    $wp_customize->add_panel('activello_main_options', array(
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __('Activello Options', 'activello'),
        'description' => __('Panel to update activello theme options', 'activello'), // Include html tags such as <p>.
        'priority' => 10 // Mixed with top-level-section hierarchy.
    ));

	// add "Content Options" section
	$wp_customize->add_section( 'activello_content_section' , array(
		'title'      => esc_html__( 'Content Options', 'activello' ),
		'priority'   => 50,
                'panel' => 'activello_main_options'
	) );

	// add setting for excerpts/full posts toggle
	$wp_customize->add_setting( 'activello_excerpts', array(
		'default'           => 1,
		'sanitize_callback' => 'activello_sanitize_checkbox',
	) );

	// add checkbox control for excerpts/full posts toggle
	$wp_customize->add_control( 'activello_excerpts', array(
		'label'     => esc_html__( 'Show post excerpts?', 'activello' ),
		'section'   => 'activello_content_section',
		'priority'  => 10,
		'type'      => 'checkbox'
	) );

	$wp_customize->add_setting( 'activello_page_comments', array(
		'default' => 1,
		'sanitize_callback' => 'activello_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'activello_page_comments', array(
		'label'		=> esc_html__( 'Display Comments on Static Pages?', 'activello' ),
		'section'	=> 'activello_content_section',
		'priority'	=> 20,
		'type'      => 'checkbox',
	) );


	// add "Featured Posts" section
	$wp_customize->add_section( 'activello_featured_section' , array(
		'title'      => esc_html__( 'Slider Options', 'activello' ),
		'priority'   => 60,
        'panel' => 'activello_main_options'
	) );

	$wp_customize->add_setting( 'activello_featured_cat', array(
		'default' => 0,
		'transport'   => 'refresh',
                'sanitize_callback' => 'activello_sanitize_slidecat'
	) );

	$wp_customize->add_control( 'activello_featured_cat', array(
		'type' => 'select',
		'label' => 'Choose a category',
		'choices' => activello_cats(),
		'section' => 'activello_featured_section',
	) );

	$wp_customize->add_setting( 'activello_featured_limit', array(
			'default' => -1,
			'transport'   => 'refresh',
			'sanitize_callback' => 'activello_sanitize_number'
	) );
	
	$wp_customize->add_control( 'activello_featured_limit', array(
			'type' => 'number',
			'label' => 'Limit posts',
			'section' => 'activello_featured_section',
	) );
	
	$wp_customize->add_setting( 'activello_featured_hide', array(
		'default' => 0,
		'transport'   => 'refresh',
                'sanitize_callback' => 'activello_sanitize_checkbox'
	) );

	$wp_customize->add_control( 'activello_featured_hide', array(
		'type' => 'checkbox',
		'label' => 'Show Slider',
		'section' => 'activello_featured_section',
	) );


	// add "Sidebar" section
        $wp_customize->add_section('activello_layout_section', array(
            'title' => __('Layout options', 'activello'),
            'priority' => 31,
            'panel' => 'activello_main_options'
        ));
            // Layout options
            global $site_layout;
            $wp_customize->add_setting('activello_sidebar_position', array(
                 'default' => 'side-right',
                 'sanitize_callback' => 'activello_sanitize_layout'
            ));
            $wp_customize->add_control('activello_sidebar_position', array(
                 'label' => __('Website Layout Options', 'activello'),
                 'section' => 'activello_layout_section',
                 'type'    => 'select',
                 'description' => __('Choose between different layout options to be used as default', 'activello'),
                 'choices'    => $site_layout
            ));

            $wp_customize->add_setting('accent_color', array(
                    'default' => '#a161bf',
                    'sanitize_callback' => 'activello_sanitize_hexcolor'
                ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
                'label' => __('Accent Color', 'activello'),
                'description'   => __('Default used if no color is selected','activello'),
                'section' => 'activello_layout_section',
            )));

            $wp_customize->add_setting('social_color', array(
                'default' => '#696969',
                'sanitize_callback' => 'activello_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_color', array(
                'label' => __('Social icon color', 'activello'),
                'description' => sprintf(__('Default used if no color is selected', 'activello')),
                'section' => 'activello_layout_section',
            )));

            $wp_customize->add_setting('social_hover_color', array(
                'default' => '#a161bf',
                'sanitize_callback' => 'activello_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_hover_color', array(
                'label' => __('Social Icon:hover Color', 'activello'),
                'description' => sprintf(__('Default used if no color is selected', 'activello')),
                'section' => 'activello_layout_section',
            )));

	// add "Footer" section
	$wp_customize->add_section( 'activello_footer_section' , array(
		'title'      => esc_html__( 'Footer', 'activello' ),
		'priority'   => 90,
	) );

	$wp_customize->add_setting( 'activello_footer_copyright', array(
		'default' => '',
		'transport'   => 'refresh',
                'sanitize_callback' => 'activello_sanitize_strip_slashes'
	) );

	$wp_customize->add_control( 'activello_footer_copyright', array(
		'type' => 'textarea',
		'label' => 'Copyright Text',
		'section' => 'activello_footer_section',
	) );

        /* Activello Other Options */
        $wp_customize->add_section('activello_other_options', array(
            'title' => __('Other', 'activello'),
            'priority' => 70,
            'panel' => 'activello_main_options'
        ));

}
add_action( 'customize_register', 'activello_customizer' );

/**
 * Adds sanitization callback function: Strip Slashes
 * @package Activello
 */
function activello_sanitize_strip_slashes($input) {
    return wp_kses_stripslashes($input);
}

/**
 * Sanitzie checkbox for WordPress customizer
 */
function activello_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Adds sanitization callback function: Sidebar Layout
 * @package Activello
 */
function activello_sanitize_layout( $input ) {
    global $site_layout;
    if ( array_key_exists( $input, $site_layout ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: colors
 * @package Activello
 */
function activello_sanitize_hexcolor($color) {
    if ($unhashed = sanitize_hex_color_no_hash($color))
        return '#' . $unhashed;
    return $color;
}

/**
 * Adds sanitization callback function: Slider Category
 * @package Activello
 */
function activello_sanitize_slidecat( $input ) {

    if ( array_key_exists( $input, activello_cats()) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Radio Header
 * @package Activello
 */
function activello_sanitize_radio_header( $input ) {
   global $header_show;
    if ( array_key_exists( $input, $header_show ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Number
 * @package Activello
 */
function activello_sanitize_number($input) {
    if ( isset( $input ) && is_numeric( $input ) ) {
        return $input;
    }
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function activello_customize_preview_js() {
	wp_enqueue_script( 'activello_customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), '20160217', true );
}
add_action( 'customize_preview_init', 'activello_customize_preview_js' );

function activello_customizer_scripts(){

    wp_enqueue_script( 'activello-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/js/customize-controls.js', array( 'customize-controls' ) );
    wp_enqueue_style( 'activello-customize-controls', trailingslashit( get_template_directory_uri() ) . 'inc/css/customize-controls.css' );

}
add_action( 'customize_controls_enqueue_scripts', 'activello_customizer_scripts', 0 );


