<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Slider control
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 */
	class Epsilon_Control_Slider extends WP_Customize_Control {
		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'epsilon-slider';

		/**
		 * Enqueue scripts/styles.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-slider' );
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

				<input disabled type="text" class="rl-slider" id="input_<?php echo $this->id; ?>"
					   value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>

			</label>

			<div id="slider_<?php echo $this->id; ?>" class="ss-slider"></div>
			<script>
				jQuery(document).ready(function ($) {
					$('[id="slider_<?php echo $this->id; ?>"]').slider({
						value: <?php echo esc_attr( $this->value() ); ?>,
						range: 'min',
						min  : <?php echo $this->choices['min']; ?>,
						max  : <?php echo $this->choices['max']; ?>,
						step : <?php echo $this->choices['step']; ?>,
						slide: function (event, ui) {
							$('[id="input_<?php echo $this->id; ?>"]').val(ui.value).keyup();
						}
					});
					$('[id="input_<?php echo $this->id; ?>"]').val($('[id="slider_<?php echo $this->id; ?>"]').slider("value"));
					$('[id="input_<?php echo $this->id; ?>"]').change(function () {
						$('[id="slider_<?php echo $this->id; ?>"]').slider({
							value: $(this).val()
						});
					});
				});
			</script>
			<?php
		}
	}
}// End if().
