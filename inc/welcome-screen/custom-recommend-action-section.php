<?php
/**
 * Pro customizer section.
 *
 * @since  1.0.0
 * @access public
 */
class Activello_Customize_Section_Recommend extends WP_Customize_Section {
	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'activello-recomended-section';
	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $required_actions = '';
	public $total_actions = '';
	public $succes_text = '';
	public $facebook = '';
	public $twitter = '';
	public $wp_review = false;

	public function check_active( $slug ) {
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $slug . '.php' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$needs = is_plugin_active( $slug . '/' . $slug . '.php' ) ? 'deactivate' : 'activate';

			return array( 'status' => is_plugin_active( $slug . '/' . $slug . '.php' ), 'needs' => $needs );
		}

		return array( 'status' => false, 'needs' => 'install' );
	}

	public function create_action_link( $state, $slug ) {
		switch ( $state ) {
			case 'install':
				return wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => $slug
						),
						network_admin_url( 'update.php' )
					),
					'install-plugin_' . $slug
				);
				break;
			case 'deactivate':
				return add_query_arg( array(
					                      'action'        => 'deactivate',
					                      'plugin'        => rawurlencode( $slug . '/' . $slug . '.php' ),
					                      'plugin_status' => 'all',
					                      'paged'         => '1',
					                      '_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug . '/' . $slug . '.php' ),
				                      ), network_admin_url( 'plugins.php' ) );
				break;
			case 'activate':
				return add_query_arg( array(
					                      'action'        => 'activate',
					                      'plugin'        => rawurlencode( $slug . '/' . $slug . '.php' ),
					                      'plugin_status' => 'all',
					                      'paged'         => '1',
					                      '_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $slug . '/' . $slug . '.php' ),
				                      ), network_admin_url( 'plugins.php' ) );
				break;
		}
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();
		global $activello_required_actions;
		$formatted_array = array();
		foreach ( $activello_required_actions as $key => $activello_required_action ) {
			if ( $activello_required_action['check'] ) {
				continue;
			}

			$activello_required_action['index'] = $key + 1;

			if ( isset($activello_required_action['plugin_slug']) ) {
				$active = $this->check_active( $activello_required_action['plugin_slug'] );
				$activello_required_action['url']    = $this->create_action_link( $active['needs'], $activello_required_action['plugin_slug'] );
				if ( $active['needs'] !== 'install' && $active['status'] ) {
					$activello_required_action['class'] = 'active';
				}else{
					$activello_required_action['class'] = '';
				}

				switch ( $active['needs'] ) {
					case 'install':
						$activello_required_action['button_class'] = 'install-now button';
						$activello_required_action['button_label'] = __( 'Install', 'activello' );
						break;
					case 'activate':
						$activello_required_action['button_class'] = 'activate-now button button-primary';
						$activello_required_action['button_label'] = __( 'Activate', 'activello' );
						break;
					case 'deactivate':
						$activello_required_action['button_class'] = 'deactivate-now button';
						$activello_required_action['button_label'] = __( 'Deactivate', 'activello' );
						break;
				}

			}
			$formatted_array[] = $activello_required_action;
		}
		$json['required_actions'] = $formatted_array;
		$json['total_actions'] = count($activello_required_actions);
		$json['succes_text'] = $this->succes_text;
		$json['facebook'] = $this->facebook;
		$json['twitter'] = $this->twitter;
		$json['wp_review'] = $this->wp_review;
		if ( $this->wp_review ) {
			$json['theme_slug'] = get_template();
		}
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
				<span class="section-title" data-succes="{{ data.succes_text }}">
					<# if( data.required_actions.length > 0 ){ #>
						{{ data.title }}
					<# }else{ #>
						{{ data.succes_text }}
					<# } #>
				</span>
				<# if( data.required_actions.length > 0 ){ #>
					<span class="activello-actions-count">
						<span class="current-index">{{ data.required_actions[0].index }}</span>
						/
						{{ data.total_actions }}
					</span>
				<# } #>
			</h3>
			<div class="recomended-actions_container" id="plugin-filter">
				<# if( data.required_actions.length > 0 ){ #>
					<# for (action in data.required_actions) { #>
						<div class="epsilon-recommeded-actions-container" data-index="{{ data.required_actions[action].index }}">
							<# if( !data.required_actions[action].check ){ #>
								<div class="epsilon-recommeded-actions">
									<p class="title">{{ data.required_actions[action].title }}</p>
									<div class="description">{{{ data.required_actions[action].description }}}</div>
									<# if( data.required_actions[action].plugin_slug ){ #>
										<div class="custom-action">
											<p class="plugin-card-{{ data.required_actions[action].plugin_slug }} action_button {{ data.required_actions[action].class }}">
												<a data-slug="{{ data.required_actions[action].plugin_slug }}"
												   class="{{ data.required_actions[action].button_class }}"
												   href="{{ data.required_actions[action].url }}">{{ data.required_actions[action].button_label }}</a>
											</p>
										</div>
									<# } #>
									<# if( data.required_actions[action].help ){ #>
										<div class="custom-action">{{{ data.required_actions[action].help }}}</div>
									<# } #>
								</div>
							<# } #>
						</div>
					<# } #>
				<# } #>
				<# if( data.required_actions.length == 0 ){ #>
					<p class="succes">
						<# if( data.facebook ){ #>
							<a href="{{ data.facebook }}" class="button social"><span class="dashicons dashicons-facebook-alt"></span>Facebook</a>
						<# } #>

						<# if( data.twitter ){ #>
							<a href="{{ data.twitter }}" class="button social"><span class="dashicons dashicons-twitter"></span>Twitter</a>
						<# } #>
						<# if( data.wp_review ){ #>
							<a href="https://wordpress.org/support/theme/{{ data.theme_slug }}/reviews/#new-post" class="button button-primary activello-wordpress"><span class="dashicons dashicons-wordpress"></span>Review this theme on w.org</a>
						<# } #>
					</p>
				<# }else{ #>
					<p class="succes hide">
						<# if( data.facebook ){ #>
							<a href="{{ data.facebook }}" class="button social">Facebook</a>
						<# } #>

						<# if( data.twitter ){ #>
							<a href="{{ data.twitter }}" class="button social">Twitter</a>
						<# } #>
						<# if( data.wp_review ){ #>
							<a href="https://wordpress.org/support/theme/{{ data.theme_slug }}/reviews/#new-post" class="button button-primary">Review this theme on w.org</a>
						<# } #>
					</p>
				<# } #>
			</div>
		</li>
	<?php }
}