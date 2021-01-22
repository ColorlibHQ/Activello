<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( class_exists( 'WP_Customize_Control' ) ) {
	class Epsilon_Control_Typography extends WP_Customize_Control {
		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'epsilon-typography';

		/**
		 * @since  1.0.0
		 * @access public
		 * @var string
		 */
		public $selectors;

		/**
		 * @since  1.0.3
		 * @access public
		 * @var array
		 */
		public $font_defaults;

		/**
		 * Epsilon_Control_Typography constructor.
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string               $id
		 * @param array                $args
		 */
		public function __construct( WP_Customize_Manager $manager, $id, array $args = array() ) {
			parent::__construct( $manager, $id, $args );
			$this->set_font_defaults( $args, $id );
		}

		/**
		 * Sets the typography defaults
		 */
		public function set_font_defaults( $args, $id ) {
			$arr = array();
			if ( ! empty( $args['font_defaults'] ) ) {
				$arr[ $id ] = $args['font_defaults'];
			}

			$this->font_defaults = $arr;
		}

		/**
		 * Enqueues selectize js
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_style( 'selectize', get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/vendors/selectize/selectize.css' );
			wp_enqueue_script( 'selectize', get_template_directory_uri() . '/inc/libraries/epsilon-framework/assets/vendors/selectize/selectize.min.js', array( 'jquery' ), '1.0.0', true );
		}

		/**
		 * Grabs the value from the json and creates a k/v array
		 *
		 * @since 1.0.0
		 *
		 * @param $values
		 *
		 * @return array
		 */
		public function get_values( $values ) {
			$defaults = array(
				'font-family'    => 'Select font',
				'font-weight'    => '',
				'font-style'     => '',
				'letter-spacing' => '0',
				'font-size'      => '16',
				'line-height'    => '18',
			);

			$arr = array();
			foreach ( $this->choices as $choice ) {
				if ( array_key_exists( $choice, $defaults ) ) {
					$arr[ $choice ] = $defaults[ $choice ];
				}
			}

			if ( empty( $values ) ) {
				return $arr;
			}

			$json = get_theme_mod( $values, '' );

			if ( '' === $json ) {
				return $arr;
			}

			$json    = str_replace( '&quot;', '"', $json );
			$json    = (array) json_decode( $json );
			$options = (array) $json['json'];

			/**
			 * Changed these options (font-style and weight) in toggles
			 */
			if ( ! empty( $options['font-style'] ) ) {
				$options['font-style'] = 'on';
			}
			if ( ! empty( $options['font-weight'] ) ) {
				$options['font-weight'] = 'on';
			}

			$return = array_merge( $arr, $options );

			foreach ( $return as $k => $v ) {
				$return[ $k ] = esc_attr( $v );
			}

			return $return;
		}

		/**
		 * Access the GFonts Json and parse its content.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return array|mixed|object
		 */
		public function google_fonts() {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}

			$path   = dirname( dirname( __FILE__ ) ) . '/assets/data/gfonts.json';
			$gfonts = $wp_filesystem->get_contents( $path );

			return json_decode( $gfonts );
		}

		/**
		 * @return string
		 */
		public function set_selectors() {
			return implode( ',', $this->selectors );
		}

		/**
		 * Displays the control content.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title">
					<?php echo esc_attr( $this->label ); ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<i class="dashicons dashicons-editor-help" style="vertical-align: text-bottom; position: relative;">
							<span class="mte-tooltip"><?php echo wp_kses_post( $this->description ); ?></span>
						</i>
					<?php endif; ?>
				</span>
				<input disabled type="hidden" id="selectors_<?php echo esc_attr( $this->id ) ?>" value="<?php echo esc_attr( $this->set_selectors() ); ?>"/>
				<input disabled type="hidden" class="epsilon-typography-input" id="hidden_input_<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
			</label>

			<?php
			$inputs = $this->get_values( $this->id );
			$fonts  = $this->google_fonts();
			?>
			<div class="epsilon-typography-container" data-unique-id="<?php echo esc_attr( $this->id ) ?>">
				<?php if ( in_array( 'font-family', $this->choices ) ) : ?>
					<div class="epsilon-typography-font-family">
						<select id="<?php echo esc_attr( $this->id ); ?>-font-family" class="epsilon-typography-input">
							<option value="default_font"><?php echo esc_html__( 'Theme default', 'epsilon-framework' ); ?></option>
							<?php foreach ( $fonts as $font => $properties ) { ?>
								<option <?php echo $inputs['font-family'] === $properties->family ? 'selected' : ''; ?>
									value="<?php echo esc_attr( $properties->family ) ?>"><?php echo esc_html( $properties->family ) ?></option>
							<?php } ?>
						</select>
					</div>
				<?php endif; ?>

				<div class="epsilon-typography-font-weight">
					<div class="epsilon-font-weight-switch">
						<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>-font-weight" class="epsilon-typography-input epsilon-font-weight-switch-checkbox" value="on" <?php checked( $inputs['font-weight'], 'on' ) ?>>
						<label class="epsilon-font-weight-switch-label" for="<?php echo esc_attr( $this->id ); ?>-font-weight"></label>
					</div>
				</div>

				<div class="epsilon-typography-font-style">
					<div class="epsilon-font-style-switch">
						<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>-font-style" class="epsilon-typography-input epsilon-font-style-switch-checkbox" value="on" <?php checked( $inputs['font-style'], 'on' ) ?>>
						<label class="epsilon-font-style-switch-label" for="<?php echo esc_attr( $this->id ); ?>-font-style"></label>
					</div>
				</div>

				<?php if ( in_array( 'font-size', $this->choices ) || in_array( 'line-height', $this->choices ) || in_array( 'letter-spacing', $this->choices ) ) : ?>
					<div class="epsilon-typography-advanced">
						<a href="#" data-toggle="<?php echo esc_attr( $this->id ) ?>-toggle" class="epsilon-typography-advanced-options-toggler"><span class="dashicons dashicons-admin-generic"></span></a>
					</div>
					<div class="epsilon-typography-advanced-options" id="<?php echo esc_attr( $this->id ) ?>-toggle">
						<?php if ( in_array( 'font-size', $this->choices ) ) : ?>
							<label for="<?php echo esc_attr( $this->id ); ?>-font-size">
								<?php echo esc_html__( 'Font Size', 'epsilon-framework' ); ?>
							</label>
							<div class="slider-container">
								<input data-default-font-size="<?php echo esc_attr( $this->font_defaults[ $this->id ]['font-size'] ) ?>" type="text" class="epsilon-typography-input rl-slider" id="<?php echo esc_attr( $this->id ); ?>-font-size" value="<?php echo esc_attr( $inputs['font-size'] ); ?>"/>
								<div id="slider_<?php echo esc_attr( $this->id ) ?>-font-size" data-attr-min="0" data-attr-max="40" data-attr-step="1" class="ss-slider"></div>
							</div>
						<?php endif; ?>
						<?php if ( in_array( 'line-height', $this->choices ) ) : ?>
							<label for="<?php echo esc_attr( $this->id ); ?>-line-height">
								<?php echo esc_html__( 'Line Height', 'epsilon-framework' ); ?>
							</label>
							<div class="slider-container">
								<input data-default-line-height="<?php echo esc_attr( $this->font_defaults[ $this->id ]['line-height'] ) ?>" type="text" class="epsilon-typography-input rl-slider" id="<?php echo esc_attr( $this->id ); ?>-line-height" value="<?php echo esc_attr( $inputs['line-height'] ); ?>"/>
								<div id="slider_<?php echo esc_attr( $this->id ) ?>-line-height" data-attr-min="0" data-attr-max="40" data-attr-step="1" class="ss-slider"></div>
							</div>
						<?php endif; ?>
						<?php if ( in_array( 'letter-spacing', $this->choices ) ) : ?>
							<label for="<?php echo esc_attr( $this->id ); ?>-letter-spacing">
								<?php echo esc_html__( 'Letter Spacing', 'epsilon-framework' ); ?>
							</label>
							<div class="slider-container">
								<input data-default-letter-spacing="<?php echo esc_attr( $this->font_defaults[ $this->id ]['letter-spacing'] ) ?>" type="text" class="epsilon-typography-input rl-slider" id="<?php echo esc_attr( $this->id ); ?>-letter-spacing" value="<?php echo esc_attr( $inputs['letter-spacing'] ); ?>"/>
								<div id="slider_<?php echo esc_attr( $this->id ) ?>-letter-spacing" data-attr-min="0" data-attr-max="5" data-attr-step="0.1" class="ss-slider"></div>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<!--<a href="#" class="epsilon-typography-default"><?php //echo esc_html__( 'Reset to default', 'epsilon-framework' ) ?></a> -->
			</div>
			<?php
		}
	}
}// End if().
