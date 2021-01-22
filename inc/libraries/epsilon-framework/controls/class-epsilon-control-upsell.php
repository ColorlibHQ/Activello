<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( class_exists( 'WP_Customize_Control' ) ) :
	/**
	 * Class Epsilon_Control_Upsell
	 */
	class Epsilon_Control_Upsell extends WP_Customize_Control {

		/**
		 * @var string
		 */
		public $type = 'epsilon-upsell';
		/**
		 * @var string
		 */
		public $button_text = '';
		/**
		 * @var string
		 */
		public $button_url = '#';
		/**
		 * @var string
		 */
		public $second_button_text = '';
		/**
		 * @var string
		 */
		public $second_button_url = '#';
		/**
		 * @var string
		 */
		public $separator = '';
		/**
		 * @var array
		 */
		public $options = array();
		/**
		 * @var array
		 */
		public $requirements = array();
		/**
		 * @var string|void
		 */
		public $pro_label = '';
		/**
		 * @var array
		 */
		public $json = array();
		/**
		 * @var bool|mixed|void
		 */
		public $allowed = true;

		/**
		 * Epsilon_Control_Upsell constructor.
		 *
		 * @param WP_Customize_Manager $manager
		 * @param string               $id
		 * @param array                $args
		 */
		public function __construct( WP_Customize_Manager $manager, $id, array $args ) {
			$this->pro_label = __( 'Pro', 'epsilon-framework' );
			$this->allowed   = apply_filters( 'epsilon_upsell_control_display', true );

			$manager->register_control_type( 'Epsilon_Control_Upsell' );
			parent::__construct( $manager, $id, $args );
		}

		/**
		 *
		 */
		public function to_json() {
			parent::to_json();
			$this->json['button_text']        = $this->button_text;
			$this->json['button_url']         = $this->button_url;
			$this->json['second_button_text'] = $this->second_button_text;
			$this->json['second_button_url']  = $this->second_button_url;
			$this->json['separator']          = $this->separator;
			$this->json['allowed']            = $this->allowed;

			$arr = array();
			$i   = 0;
			foreach ( $this->options as $option ) {
				$arr[ $i ]['option'] = $option;
				$i ++;
			}

			$i = 0;
			foreach ( $this->requirements as $help ) {
				$arr[ $i ]['help'] = $help;
				$i ++;
			}

			$this->json['options']   = $arr;
			$this->json['pro_label'] = $this->pro_label;
		}

		/**
		 *
		 */
		public function content_template() {
			//@formatter:off ?>

			<# if ( data.allowed ) { #>
			<div class="epsilon-upsell">
				<# if ( data.options ) { #>
					<ul class="epsilon-upsell-options">
						<# _.each(data.options, function( option, index) { #>
							<li><span class="wp-ui-notification">{{ data.pro_label }}</span>{{ option.option }}
								<i class="dashicons dashicons-editor-help"
								   style="vertical-align: text-bottom; position: relative;">
									<span class="mte-tooltip">{{ option.help }}</span>
								</i>
							</li>
							<# }) #>
					</ul>
				<# } #>

				<div class="epsilon-button-group">
					<# if ( data.button_text && data.button_url ) { #>
						<a href="{{ data.button_url }}" class="button" target="_blank">{{
							data.button_text }}</a>
					<# } #>

					<# if ( data.separator ) { #>
						<span class="button-separator">{{ data.separator }}</span>
					<# } #>

					<# if ( data.second_button_text && data.second_button_url ) { #>
						<a href="{{ data.second_button_url }}" class="button button-primary" target="_blank"> {{data.second_button_text }}</a>
					<# } #>
				</div>
			</div>
			<# } #>
<?php //@formatter:on
		}
	}
endif;
