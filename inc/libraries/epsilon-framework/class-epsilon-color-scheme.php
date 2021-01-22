<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Color_Scheme
 */
class Epsilon_Color_Scheme {
	/**
	 * @var array
	 */
	public $options = array();
	/**
	 * If there isn't any inline style, we don't need to generate the CSS
	 *
	 * @var bool
	 */
	protected $terminate = false;
	/**
	 * @var string
	 */
	protected $handler = '';
	/**
	 * @var string
	 */
	protected $css = '';
	/**
	 * @var array
	 */
	protected $customizer_controls = array();

	/**
	 * Epsilon_Color_Scheme constructor.
	 *
	 * @param string $handler
	 * @param array  $args
	 */
	public function __construct( $handler, $args ) {
		$this->handler = $handler;
		$this->css     = $args['css'];
		$this->set_customizer_controls( $args );

		add_action( 'wp_ajax_epsilon_generate_color_scheme_css', array(
			$this,
			'epsilon_generate_color_scheme_css',
		) );
		add_action( 'wp_ajax_nopriv_epsilon_generate_color_scheme_css', array(
			$this,
			'epsilon_generate_color_scheme_css',
		) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'customize_register', array( $this, 'add_controls_settings' ) );
	}

	/**
	 * @param $args
	 */
	private function set_customizer_controls( $args ) {
		$controls = array();
		$options  = array();
		foreach ( $args['fields'] as $control => $prop ) {
			$controls[ $control ] = $prop;
			$options[ $control ]  = $prop['default'];
		}

		$this->customizer_controls = $controls;
		$this->options             = $options;
	}

	/**
	 * @return array
	 */
	public function get_customizer_controls() {
		return $this->customizer_controls;
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Grabs the instance of the epsilon color scheme class
	 *
	 * @param null  $handler
	 * @param array $args
	 *
	 * @return Epsilon_Color_Scheme
	 */
	public static function get_instance( $handler = null, $args = array() ) {
		static $inst;
		if ( ! $inst ) {
			$inst = new Epsilon_Color_Scheme( $handler, $args );
		}

		return $inst;
	}

	/**
	 * When the function is called through AJAX, we update the colors by merging the 2 arrays
	 *
	 * @param $args
	 */
	public function update_colors( $args ) {
		if ( null !== $args ) {
			$array = array_merge( $this->options, $args );
			foreach ( $this->customizer_controls as $control => $prop ) {
				if ( ! $prop['hover-state'] ) {
					continue;
				}

				if ( ! empty( $array[ $control ] ) ) {
					$array[ $control . '_hover' ] = $this->adjust_brightness( $array[ $control ], 10 );
				}
			}

			$this->options = $array;
		}
	}

	/**
	 * Add the controls to the customizer
	 *
	 * @todo - nu cred ca merge translation stuff
	 */
	public function add_controls_settings() {
		global $wp_customize;
		$i = 3;
		foreach ( $this->customizer_controls as $control => $properties ) {
			$wp_customize->add_setting( $control, array(
				'default'           => $properties['default'],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			) );
			$wp_customize->add_control( $control, array() );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control, array(
				'label'       => $properties['label'],
				'description' => $properties['description'],
				'section'     => $properties['section'],
				'settings'    => $control,
				'priority'    => $i,
			) ) );
			$i ++;
		}
	}

	/**
	 * @param $hex
	 * @param $steps
	 *
	 * @return string
	 */
	public function adjust_brightness( $hex, $steps ) {
		$steps = max( - 255, min( 255, $steps ) );
		$hex   = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		$color_parts = str_split( $hex, 2 );
		$return      = '#';
		foreach ( $color_parts as $color ) {
			$color  = hexdec( $color ); // Convert to decimal
			$color  = max( 0, min( 255, $color + $steps ) ); // Adjust color
			$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT ); // Make two char hex code
		}

		return $return;
	}

	/**
	 * Return the css inline styles for the AJAX function (through the customizer)
	 *
	 * @return string
	 */
	public function generate_live_css() {
		return vsprintf( $this->css_template(), $this->options );
	}

	/**
	 * Return the css string for the live website
	 *
	 * @return string
	 */
	public function generate_css() {
		$color_scheme = $this->get_color_scheme();

		return vsprintf( $this->css_template(), $color_scheme );
	}

	/**
	 * Enqueue the inline style CSS string
	 */
	public function enqueue() {
		$css = $this->generate_css();
		wp_add_inline_style( $this->handler, $css );
	}

	/**
	 * Returns the whole CSS string
	 *
	 * @return string
	 */
	public function css_template() {
		if ( $this->terminate || ! $this->css ) {
			return '';
		}

		return $this->css;
	}

	/**
	 * Create the array with the new settings
	 *
	 * @return array
	 */
	public function get_color_scheme() {
		$colors = array();

		foreach ( $this->options as $k => $v ) {
			$color        = get_theme_mod( $k, $v );
			$colors[ $k ] = $color;
		}

		/**
		 * small check
		 */
		$a = serialize( $this->options );
		$b = serialize( $colors );

		if ( $a === $b ) {
			$this->terminate = true;
		}

		foreach ( $this->customizer_controls as $control => $prop ) {
			if ( ! $prop['hover-state'] ) {
				continue;
			}

			if ( ! empty( $colors[ $control ] ) ) {
				$colors[ $control . '_hover' ] = $this->adjust_brightness( $colors[ $control ], 10 );
			}
		}

		return $colors;
	}

	/**
	 * @param $path
	 *
	 * @return bool|string
	 */
	public static function load_css_overrides( $path ) {
		if ( file_exists( $path ) ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			return $wp_filesystem->get_contents( $path );
		}

		return false;
	}

	/**
	 * Generate the color scheme css
	 */
	public function epsilon_generate_color_scheme_css() {
		$args = array();

		/**
		 * Sanitize the $_POST['args']
		 */
		foreach ( $_POST['args'] as $k => $v ) {
			$args[ $k ] = sanitize_hex_color( $v );
		}

		/**
		 * Grab the instance of the Epsilon Color Scheme
		 */
		$color_scheme = Epsilon_Color_Scheme::get_instance( $this->handler );

		/**
		 * Update the option array
		 */
		$color_scheme->update_colors( $args );
		/**
		 * Echo the css inline sheet
		 */
		echo $color_scheme->generate_live_css();
		wp_die();
	}
}
