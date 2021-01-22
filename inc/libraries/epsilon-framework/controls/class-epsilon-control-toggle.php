<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Multiple checkbox customize control class.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 */
	class Epsilon_Control_Toggle extends WP_Customize_Control {

		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'epsilon-toggle';

		/**
		 * Displays the control content.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function render_content() {
			?>
			<div class="checkbox_switch">
				<span class="customize-control-title onoffswitch_label"><?php echo esc_html( $this->label ); ?>
					<?php if ( ! empty( $this->description ) ) : ?>
					<i class="dashicons dashicons-editor-help" style="vertical-align: text-bottom; position: relative;">
						<span class="mte-tooltip"><?php echo wp_kses_post( $this->description ); ?></span>
					</i>
					<?php endif; ?>
				</span>
				<div class="onoffswitch">
					<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>"
						   name="<?php echo esc_attr( $this->id ); ?>" class="onoffswitch-checkbox"
						   value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link();
							checked( $this->value() ); ?>>
					<label class="onoffswitch-label" for="<?php echo esc_attr( $this->id ); ?>"></label>
				</div>
			</div>
			<?php
		}
	}
}// End if().
