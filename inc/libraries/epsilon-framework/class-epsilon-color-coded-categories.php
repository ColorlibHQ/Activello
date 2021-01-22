<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Class Epsilon_Color_Coded_Categories
 */
class Epsilon_Color_Coded_Categories {
	/**
	 * @var array
	 */
	protected $selectors = array(
		'background' => array(),
		'color'      => array(),
		'box-shadow' => array(),
	);

	/**
	 * @var
	 */
	protected $handler;

	/**
	 * @var
	 */
	protected $section = array();

	/**
	 * @var string
	 */
	protected $css = '';

	/**
	 * Epsilon_Color_Coded_Categories constructor.
	 *
	 * @param $handler
	 * @param $args
	 */
	public function __construct( $handler, $args ) {
		$this->handler = $handler;

		$this->section = array(
			'id'          => $args['section'],
			'title'       => $args['section-title'],
			'description' => $args['section-description'],
			'panel'       => $args['panel'],
		);

		$this->add_controls_settings();

		add_action( 'wp_print_styles', array( $this, 'reset_queue' ), 0 );
		add_action( 'wp_footer', array( $this, 'proxy_enqueue' ) );
	}

	/**
	 * @return bool
	 */
	public function add_controls_settings() {
		global $wp_customize;

		if ( null === $wp_customize ) {
			return false;
		}

		$wp_customize->add_section( $this->section['id'], array(
			'priority' => 1,
			'title'    => $this->section['title'],
			'panel'    => $this->section['panel'],
		) );

		$i    = 1;
		$args = array(
			'orderby'    => 'id',
			'hide_empty' => 0,
		);

		$categories       = get_categories( $args );
		$wp_category_list = array();

		$wp_customize->add_setting( 'epsilon_hidden_category_info', array(
			'sanitize_callback' => 'esc_html',
		) );

		$wp_customize->add_control(
			'epsilon_hidden_category_info',
			array(
				'description' => $this->section['description'],
				'section'     => $this->section['id'],
				'settings'    => 'epsilon_hidden_category_info',
				'type'        => 'hidden',
				'priority'    => 0,
			)
		);
		foreach ( $categories as $category_list ) {
			$wp_category_list[ $category_list->cat_ID ] = $category_list->cat_name;
			$wp_customize->add_setting( 'epsilon_category_color_' . get_cat_ID( $wp_category_list[ $category_list->cat_ID ] ), array(
				'default'              => '',
				'capability'           => 'edit_theme_options',
				'sanitize_callback'    => array(
					'Epsilon_Color_Coded_Categories',
					'color_option_hex_sanitize',
				),
				'sanitize_js_callback' => array(
					'Epsilon_Color_Coded_Categories',
					'color_escaping_option_sanitize',
				),
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'epsilon_category_color_' . get_cat_ID( $wp_category_list[ $category_list->cat_ID ] ), array(
				'label'    => sprintf( '%s', $wp_category_list[ $category_list->cat_ID ] ),
				'section'  => $this->section['id'],
				'settings' => 'epsilon_category_color_' . get_cat_ID( $wp_category_list[ $category_list->cat_ID ] ),
				'priority' => $i,
			) ) );
			$i ++;
		}

		return true;
	}

	/**
	 * Reset WordPress queue for the current handler
	 */
	public function reset_queue() {
		if ( ! doing_action( 'wp_head' ) ) { // ensure we are on head
			return;
		}
		global $wp_styles;
		// empty the scripts and styles queue
		$key = array_search( $this->handler, $wp_styles->queue );
		if ( false !== $key ) {
			unset( $wp_styles->queue[ $key ] );
		}
		$key = array_search( $this->handler, $wp_styles->to_do );
		if ( false !== $key ) {
			unset( $wp_styles->to_do[ $key ] );
		}

		add_action( 'wp_footer', array( $this, 'readd_in_queue' ), 0 );
	}

	/**
	 * Re-add items in queue
	 */
	public function readd_in_queue() {
		// reset the queue to print scripts and styles in footer
		global $wp_styles;
		$wp_styles->queue[] = $this->handler;
		$wp_styles->to_do[] = $this->handler;
	}

	/**
	 * Proxy enqueue function
	 */
	public function proxy_enqueue() {
		$color = self::get_instance();
		$color->enqueue();
	}

	/**
	 * Proxy to enqueue files
	 */
	public function _enqueue() {
		foreach ( $this->selectors as $property => $arr ) {
			$this->css .= $this->parse_arr( $property, $arr );
		}
	}

	/**
	 * @param $property
	 * @param $arr
	 *
	 * @return string
	 */
	public function parse_arr( $property, $arr ) {
		$output = '';
		foreach ( $arr as $selector => $value ) {
			if ( '' !== $value ) {
				$output .= $selector . '{' . $property . ':' . $value . '}' . "\n";
			}
		}

		return $output;
	}

	/**
	 * @param null  $handler
	 * @param array $args
	 *
	 * @return Epsilon_Color_Coded_Categories
	 */
	public static function get_instance( $handler = null, $args = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Color_Coded_Categories( $handler, $args );
		}

		return $inst;
	}

	/**
	 * @param $args
	 */
	public function set_selectors( $args ) {
		$this->selectors[ $args['property'] ][ $args['selector'] ] = $args['value'];
	}

	/**
	 * print the styles
	 */
	public function enqueue() {
		$this->_enqueue();
		wp_add_inline_style( $this->handler, $this->css );
	}


	/**
	 * @param $input
	 *
	 * @return string
	 */
	public static function color_escaping_option_sanitize( $input ) {
		$input = esc_attr( $input );

		return $input;
	}

	/**
	 * @param $color
	 *
	 * @return string
	 */
	public static function color_option_hex_sanitize( $color ) {
		$unhashed = sanitize_hex_color_no_hash( $color );
		if ( $unhashed ) {
			return '#' . $unhashed;
		}

		return $color;
	}

	/**
	 * @param $wp_category_id
	 *
	 * @return string
	 */
	public static function category_color( $wp_category_id ) {
		return get_theme_mod( 'epsilon_category_color_' . $wp_category_id, false );
	}
}
