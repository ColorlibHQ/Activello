<?php
/**
 * Documentation customizer section.
 *
 * @since  1.0.0
 * @access public
 */
class Activello_Customize_Section_Documentation extends WP_Customize_Section {
	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'activello-documentation';
	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $documentation_text = '';
	/**
	 * Custom documentatio button URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $documentation_url = '';
	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();
		$json['documentation_text'] = $this->documentation_text;
		$json['documentation_url']  = esc_url( $this->documentation_url );
		return $json;
	}
	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

			<h3 class="accordion-section-title">
				{{ data.title }}

				<# if ( data.documentation_text && data.documentation_url ) { #>
					<a href="{{ data.documentation_url }}" class="button button-secondary alignright" target="_blank">{{ data.documentation_text }}</a>
				<# } #>
			</h3>
		</li>
	<?php }
}