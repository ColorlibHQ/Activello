# Epsilon Framework

## 1. Getting started
### Available controls
> [Toggle](https://github.com/MachoThemes/epsilon-framework#toggle)

> [Range slider](https://github.com/MachoThemes/epsilon-framework#range-slider)

> [Typography](https://github.com/MachoThemes/epsilon-framework#typography)

> [Color scheme](https://github.com/MachoThemes/epsilon-framework#color-scheme)

> [Upsell](https://github.com/MachoThemes/epsilon-framework#upsell)

### Available sections
> [Upsell pro section](https://github.com/MachoThemes/epsilon-framework#upsell-pro-section)

> [Recommended action section](https://github.com/MachoThemes/epsilon-framework#recommended-action-section)

## 2. Installation
> In the root of the theme, run:

    $ git submodule add https://github.com/MachoThemes/epsilon-framework inc/libraries/epsilon-framework

> Load class-epsilon-autoloader.php

> Initiate new Epsilon_Framework( $args = array() ); E.g.

		$args = array(
			'controls' => array( 'slider', 'toggle', 'typography', 'color-scheme' ), // array of controls to load
			'sections' => array( 'recommended-actions' ), // array of sections to load
			'path'     => '/inc/libraries' // path to your epsilon framework in your theme, e.g. theme-name*/inc/libraries*/epsilon-framework
		);
        
        new Epsilon_Framework( $args );
  
> Updating the framework (You can add the ID if you have more than one submodule)
  
    $ git submodule update --remote
  
## 3. Using Epsilon
### Sections

#### Upsell pro section

    $wp_customize->add_section(
      new Epsilon_Section_Pro(
        $wp_customize,
        'epsilon-section-pro',
        array(
          'title'       => esc_html__( 'Section title', 'text-domain' ),
          'button_text' => esc_html__( 'Button label', 'text-domain' ),
          'button_url'  => esc_url_raw( # ),
          'priority'    => 0
        )
      )
    );

> Themes can disable the Upsell Pro section by adding `add_filter ('epsilon_upsell_section_display', '__return_false');` in functions.php

#### Recommended action section

    $wp_customize->add_section(
      new Epsilon_Section_Recommended_Actions(
        $wp_customize,
        'epsilon_recomended_section',
        array(
          'title'                        => esc_html__( 'Section title', 'text-domain' ),
          'social_text'                  => esc_html__( 'Social text - displayed when no plugins, actions left :', 'text-domain' ),
          'plugin_text'                  => esc_html__( 'Plugin text - displayed when no actions left', 'text-domain' ),
          'actions'                      => array(
                                              array(
                                                "id"          => 'theme-action-specific-id',
                                                "title"       => esc_html__('Action title', 'text-domain'),
                                                "description" => esc_html__('Action description', 'text-domain'),
                                                "check"       => function(),
                                                "plugin_slug" => false
                                                // Plugin slug is used to create an installation/activation link
                                              )
                                           ),
          'plugins'                      => array(
                                              'kiwi-social-share'        => array( 'recommended' => false ),
                                              'modula-best-grid-gallery' => array( 'recommended' => true )
                                            ),
          'theme_specific_option'        => $theme_slug . '_show_required_actions',
          'theme_specific_plugin_option' => $theme_slug . '_show_required_plugins',
          'facebook'                     => 'https://www.facebook.com/machothemes',
          'twitter'                      => 'https://twitter.com/MachoThemez',
          'wp_review'                    => false,
          'priority'                     => 0
        )
      )
    );
    
### Controls

#### Toggle

    $wp_customize->add_control( new Epsilon_Control_Toggle(
                                  $wp_customize,
                                  'epsilon_control_toggle',
                                  array(
                                    'type'        => 'epsilon-toggle',
                                    'label'       => esc_html__( 'Epsilon Toggle Label', 'text-domain' ),
                                    'description' => esc_html__( 'Epsilon Toggle Description', 'text-domain' ),
                                    'section'     => 'section_id',
                                  )
                                )
                              );
                              
#### Range Slider

    $wp_customize->add_control( new Epsilon_Control_Slider(
                                  $wp_customize,
                                  'epsilon_control_range',
                                  array(
                                    'type'        => 'epsilon-slider',
                                    'label'       => esc_html__( 'Epsilon Range Label', 'text-domain' ),
                                    'description' => esc_html__( 'Epsilon Range Description', 'text-domain' ),
                                    'choices'     => array(
                                      'min'  => 10,
                                      'max'  => 55,
                                      'step' => 5,
                                    ),
                                    'section'     => 'section_id',
                                  )
                                )
                               );

#### Typography
    $wp_customize->add_control( new Epsilon_Control_Typography(
                                  $wp_customize,
                                  'epsilon_control_typography',
                                  array(
                                    'section'     => 'section_id',
                                    'label'       => esc_html__( 'Epsilon Typography Label', 'text-domain' ),
                                    'description' => esc_html__( 'Epsilon Typography Description', 'text-domain' ),
                                    'choices'     => array(
                                      'font-family',
                                      'font-weight',
                                      'font-style',
                                      'font-size',
                                      'line-height'
                                    ),
                                    'selectors'   => array(
                                      '.entry-content h1',
                                      '.entry-content h2',
                                      '.entry-content h3',
                                      '.entry-content h4',
                                      '.entry-content h5',
                                      '.entry-content h6'
                                    )
                                  )
                                )
                              );

Collect all the options ID and get an instance of the Typography class (this is used to render the styles in frontend)

    /**
    * Instantiate the Epsilon Typography object
    */
    $options = array(
     'epsilon_control_typography'
    );

    /**
    * Attach the styles to an enqueued stylesheet
    */
    $handler = 'theme-style';
    Epsilon_Typography::get_instance( $options, $handler );

#### Color Scheme
> 1. Create an override CSS file in your assets/css/ file, called : style-overrides.css
> 2. Add your selectors using a vsprintf syntax, e.g.

    a:hover {
      color: %1$s;
    }
    p {
      color: %2$s;
    }
    
> 3. Add the control

    $wp_customize->add_control( new Epsilon_Control_Color_Scheme(
                                  $wp_customize,
                                  'epsilon_control_color_scheme',
                                  array(
                                    'label'       => esc_html__( 'Epsilon Color Scheme Label', 'text-domain' ),
                                    'description' => esc_html__( 'Epsilon Color Scheme Description', 'text-domain' ),
                                    'type'        => 'epsilon-color-scheme',
                                    'priority'    => 0,
                                    'default'     => 'red',
                                    'section'     => 'section_id',
                                    'choices'     => array(
                                      array(
                                        'id'     => 'red',
                                        'name'   => 'Default',
                                        'colors' => array(
                                          'epsilon_accent_color'               => '#ff3d2e',
                                          'epsilon_text_color'                 => '#333333',
                                          'epsilon_content_widget_title_color' => '#ff3d2e',
                                          'epsilon_footer_bg_color'            => '#272f32',
                                          'epsilon_footer_widget_title_color'  => '#ffffff',
                                          'epsilon_footer_links_color'         => '#ffffff'
                                        ),
                                      ),
                                      array(
                                        'id'     => 'yellow',
                                        'name'   => 'Yellow',
                                        'colors' => array(
                                          'epsilon_accent_color'               => '#f3950f',
                                          'epsilon_text_color'                 => '#333333',
                                          'epsilon_content_widget_title_color' => '#f3950f',
                                          'epsilon_footer_bg_color'            => '#272f32',
                                          'epsilon_footer_widget_title_color'  => '#ffffff',
                                          'epsilon_footer_links_color'         => '#ffffff'
                                        ),
                                      ),
                                    ),
                                  )
                                )
                              );
                          
> 4. Instantiate the frontend part

    $handler = 'theme-style';

    $args = array(
      'fields' => array(
        'epsilon_accent_color' => array(
          'label'       => __( 'Accent Color', 'text-domain' ),
          'description' => __( 'The main color used for links, buttons, and more.', 'text-domain' ),
          'default'     => '#ff3d2e',
          'section'     => 'section_id',
          'hover-state' => true,
        ),

        'epsilon_text_color' => array(
          'label'       => __( 'Text Color', 'text-domain' ),
          'description' => __( 'The color used for paragraphs.', 'text-domain' ),
          'default'     => '#333333',
          'section'     => 'section_id',
          'hover-state' => false,
        ),

        'epsilon_content_widget_title_color' => array(
          'label'       => __( 'Content Widget Title Color', 'text-domain' ),
          'description' => __( 'The color used for content widgets title.', 'text-domain' ),
          'default'     => '#ff3d2e',
          'section'     => 'section_id',
          'hover-state' => false,
        ),

        'epsilon_footer_bg_color' => array(
          'label'       => __( 'Footer Background Color', 'text-domain' ),
          'description' => __( 'The color used for the footer background.', 'text-domain' ),
          'default'     => '#272f32',
          'section'     => 'section_id',
          'hover-state' => false,
        ),

        'epsilon_footer_widget_title_color' => array(
          'label'       => __( 'Footer Widget Title Color', 'text-domain' ),
          'description' => __( 'The color used for the footer widgets title.', 'text-domain' ),
          'default'     => '#ffffff',
          'section'     => 'section_id',
          'hover-state' => false,
        ),

        'epsilon_footer_links_color' => array(
          'label'       => __( 'Footer Links Color', 'text-domain' ),
          'description' => __( 'The color used for the footer links.', 'text-domain' ),
          'default'     => '#ffffff',
          'section'     => 'section_id',
          'hover-state' => true,
        )
      ),

      'css' => Epsilon_Color_Scheme::load_css_overrides( get_template_directory() . '/assets/css/style-overrides.css' )
    );

    Epsilon_Color_Scheme::get_instance( $handler, $args );
    
#### Upsell

    $wp_customize->add_control( new Epsilon_Control_Upsell(
                                  $wp_customize,
                                  'epsilon_control_upsell',
                                  array(
                                    'section'      => 'section_id',
                                    'priority'     => 0,
                                    'options'      => array(
                                      esc_html__( 'First Option', 'text-domain' ),
                                    ),
                                    'requirements' => array(
                                      esc_html__( 'Premium version description.', 'text-domain' ),
                                    ),
                                    'button_url'   => esc_url_raw( # ),
                                    'button_text'  => esc_html__( 'Button Label', 'text-domain' ),

                                    'second_button_url'  => esc_url_raw( # ),
                                    'second_button_text' => esc_html__( 'Button Label', 'text-domain' ),

                                    'separator' => '- or -'
                                  )
                                )
                              );
                              
> Themes can disable the Upsell Pro Control by adding `add_filter ('epsilon_upsell_control_display', '__return_false');` in functions.php
