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
		 * Enqueues selectize js
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue() {
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
				'font-family' => 'Select font',
				'font-weight' => 'initial',
				'font-style'  => 'initial',
				'font-size'   => '16',
				'line-height' => '18'
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

			if ( $json === '' ) {
				return $arr;
			}

			$json    = str_replace( '&quot;', '"', $json );
			$json    = (array) json_decode( $json );
			$options = (array) $json['json'];

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

			$path   = get_template_directory() . '/inc/libraries/epsilon-framework/assets/data/gfonts.json';
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
	                <?php if ( ! empty( $this->description ) ): ?>
		                <i class="dashicons dashicons-editor-help"
		                   style="vertical-align: text-bottom; position: relative;">
							<span class="mte-tooltip"><?php echo wp_kses_post( $this->description ); ?></span>
						</i>
	                <?php endif; ?>
				</span>
				<input disabled type="hidden" id="selectors_<?php echo $this->id ?>"
				       value="<?php echo $this->set_selectors(); ?>"/>
				<input disabled type="hidden" class="epsilon-typography-input" id="hidden_input_<?php echo $this->id; ?>"
				       value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
			</label>

			<?php
			$inputs = $this->get_values( $this->id );
			$fonts  = $this->google_fonts();
			?>

			<div class="epsilon-typography-container" data-unique-id="<?php echo $this->id ?>">
				<?php if ( in_array( 'font-family', $this->choices ) ): ?>
					<div class="epsilon-typography-font-family">
						<label
							for="<?php echo $this->id; ?>-font-family"><?php echo __( 'Font Family', 'epsilon-framework' ); ?></label>
						<select id="<?php echo $this->id; ?>-font-family" class="epsilon-typography-input">
							<option value="default_font"><?php echo __( 'Theme default', 'epsilon-framework' ); ?></option>
							<?php foreach ( $fonts as $font => $properties ) { ?>
								<option <?php echo $inputs['font-family'] === $properties->family ? 'selected' : ''; ?>
									value="<?php echo $properties->family ?>"><?php echo $properties->family ?></option>
							<?php } ?>
						</select>
					</div>
				<?php endif; ?>
				<?php if ( in_array( 'font-weight', $this->choices ) ): ?>
					<?php
					$defaults = array( 'Select font', 'Theme default', 'initial' );
					?>
					<div class="epsilon-typography-font-weight">
						<label
							for="<?php echo $this->id; ?>-font-weight"><?php echo __( 'Font Weight', 'epsilon-framework' ); ?></label>
						<select id="<?php echo $this->id; ?>-font-weight" class="epsilon-typography-input">
							<option value="initial"><?php echo __( 'Theme default', 'epsilon-framework' ); ?></option>
							<?php
							if ( ! in_array( $inputs['font-family'], $defaults ) ) {
								foreach ( $fonts->{$inputs['font-family']}->variants as $variant ) { ?>
									<option value="<?php echo esc_attr( $variant ) ?>" <?php echo $inputs['font-weight'] === $variant ? 'selected' : '' ?>><?php echo esc_html( $variant ) ?></option>
								<?php }
							}
							?>
						</select>
					</div>
				<?php endif; ?>
				<?php if ( in_array( 'font-style', $this->choices ) ): ?>
					<div class="epsilon-typography-font-style">
						<label
							for="<?php echo $this->id; ?>-font-style"><?php echo __( 'Font Style', 'epsilon-framework' ); ?></label>
						<select id="<?php echo $this->id; ?>-font-style" class="epsilon-typography-input">
							<option value="initial"><?php echo __( 'Theme default', 'epsilon-framework' ); ?></option>
							<option <?php echo $inputs['font-style'] === 'normal' ? 'selected' : ''; ?>
								value="normal"><?php echo __( 'Normal', 'epsilon-framework' ); ?>
							</option>
							<option <?php echo $inputs['font-style'] === 'italic' ? 'selected' : ''; ?>
								value="italic"><?php echo __( 'Italic', 'epsilon-framework' ); ?>
							</option>
						</select>
					</div>
				<?php endif; ?>
				<?php if ( in_array( 'font-size', $this->choices ) ): ?>
					<div class="epsilon-typography-font-size epsilon-number-field">
						<label
							for="<?php echo $this->id; ?>-font-size"><?php echo __( 'Font Size', 'epsilon-framework' ); ?></label>
						<input class="epsilon-typography-input" id="<?php echo $this->id; ?>-font-size"
						       value="<?php echo $inputs['font-size'] ?>" type="number" min="0"
						       step="any"/>
						<span class="unit <?php echo (int) $inputs['font-size'] > 99 ? 'go-right' : '' ?>">px</span>
					</div>
				<?php endif; ?>
				<?php if ( in_array( 'line-height', $this->choices ) ): ?>
					<div class="epsilon-typography-line-height epsilon-number-field">
						<label
							for="<?php echo $this->id; ?>-line-height"><?php echo __( 'Line Height', 'epsilon-framework' ); ?></label>
						<input class="epsilon-typography-input" id="<?php echo $this->id; ?>-line-height"
						       value="<?php echo $inputs['line-height'] ?>" type="number" min="0"
						       step="any"/>
						<span class="unit <?php echo (int) $inputs['line-height'] > 99 ? 'go-right' : '' ?>">px</span>
					</div>
				<?php endif; ?>
				<a href="#" class="epsilon-typography-default"><?php echo __( 'Reset to default', 'epsilon-framework' ) ?></a>
			</div>
			<?php
		}
	}
}