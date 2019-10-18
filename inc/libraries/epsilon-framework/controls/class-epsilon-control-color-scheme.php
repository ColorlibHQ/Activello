<?php

/**
 * Class Epsilon_Color_Scheme
 */
class Epsilon_Control_Color_Scheme extends WP_Customize_Control {
	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'epsilon-color-scheme';
	/**
	 * Displays the control content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		?>
		<label>
				<span class="customize-control-title">
					<?php echo esc_attr( $this->label ); ?>
					<?php if ( ! empty( $this->description ) ) : ?>
						<i class="dashicons dashicons-editor-help"
						   style="vertical-align: text-bottom; position: relative;">
							<span class="mte-tooltip"><?php echo wp_kses_post( $this->description ); ?></span>
						</i>
					<?php endif; ?>
				</span>
			<input disabled type="hidden" class="epsilon-color-scheme-input" id="input_<?php echo $this->id; ?>"
				   value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
		</label>

		<div id="color_scheme_<?php echo $this->id; ?>" class="epsilon-color-scheme">
			<?php foreach ( $this->choices as $choice ) { ?>
				<div class="epsilon-color-scheme-option <?php echo $choice['id'] === $this->value() ? 'selected' : ''; ?>"
					 data-color-id="<?php echo $choice['id'] ?>">
					<input type="hidden" value="<?php echo esc_attr( json_encode( $choice['colors'] ) ); ?>"/>
					<span class="epsilon-color-scheme-name"> <?php echo $choice['name'] ?> </span>
					<div class="epsilon-color-scheme-palette">
						<?php foreach ( $choice['colors'] as $color ) { ?>
							<span style="background-color:<?php echo $color ?>"></span>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
