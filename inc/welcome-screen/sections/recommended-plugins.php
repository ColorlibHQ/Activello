<?php
/**
 * Recommended Plugins
 */
global $activello_required_actions, $activello_recommended_plugins;
wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
?>

<div class="feature-section recommended-plugins three-col demo-import-boxed" id="plugin-filter">
	<?php foreach ( $activello_recommended_plugins as $plugin => $prop ) { ?>
		<?php
		$info = $this->call_plugin_api( $plugin );
		
		// Skip if info is a WP_Error object
		if ( is_wp_error( $info ) ) {
			continue;
		}
		
		// Safely access properties with fallbacks
		$icons = isset( $info->icons ) && is_array( $info->icons ) ? $info->icons : array();
		$icon = $this->check_for_icon( $icons );
		$active = $this->check_active( $plugin );
		$url = $this->create_action_link( $active['needs'], $plugin );
		$name = isset( $info->name ) ? $info->name : $plugin;
		$version = isset( $info->version ) ? $info->version : '';
		$author = isset( $info->author ) ? $info->author : '';

		$label = '';

		switch ( $active['needs'] ) {
			case 'install':
				$class = 'install-now button';
				$label = __( 'Install', 'activello' );
				break;
			case 'activate':
				$class = 'activate-now button button-primary';
				$label = __( 'Activate', 'activello' );
				break;
			case 'deactivate':
				$class = 'deactivate-now button';
				$label = __( 'Deactivate', 'activello' );
				break;
		}

		?>
		<div class="col plugin_box">
			<img src="<?php echo esc_attr( $icon ) ?>" alt="plugin box image">
			<span class="version"><?php echo __( 'Version:', 'activello' ); ?><?php echo esc_html( $version ); ?></span>
			<span class="separator">|</span> <?php echo wp_kses_post( $author ); ?>
			<div class="action_bar <?php echo esc_attr( ( 'install' !== $active['needs'] && $active['status'] ) ? 'active' : '' ) ?>">
				<span class="plugin_name"><?php echo esc_html( ( 'install' !== $active['needs'] && $active['status'] ) ? 'Active: ' : '' ) . esc_html( $name ); ?></span>
			</div>
			<span class="plugin-card-<?php echo esc_attr( $plugin ) ?> action_button <?php echo esc_attr( ( 'install' !== $active['needs'] && $active['status'] ) ? 'active' : '' ) ?>">
				<a data-slug="<?php echo esc_attr( $plugin ) ?>" class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $url ) ?>"> <?php echo esc_html( $label ) ?> </a>
			</span>
		</div>
	<?php }// End foreach().
	?>
</div>
