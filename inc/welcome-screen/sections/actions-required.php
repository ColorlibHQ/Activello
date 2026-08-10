<?php
/**
 * Actions required
 *
 * The required-actions list ships empty since 1.6.0, so normally this tab only
 * renders the recommended-plugin fallback boxes. It is reachable by URL, not
 * from the tab navigation.
 */
wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );

$activello_welcome = new Activello_Welcome();
?>

<div class="feature-section action-required demo-import-boxed" id="plugin-filter">

	<?php
	global $activello_required_actions, $activello_recommended_plugins;
	$nr_actions_required = 0;
	$nr_action_dismissed = 0;
	if ( ! empty( $activello_required_actions ) ) :
		/* activello_show_required_actions is an array of true/false for each required action that was dismissed */
		$activello_show_required_actions = get_option( 'activello_show_required_actions' );
		if ( ! is_array( $activello_show_required_actions ) ) {
			$activello_show_required_actions = array();
		}
		foreach ( $activello_required_actions as $activello_required_action_key => $activello_required_action_value ) :
			$hidden = false;
			if ( isset( $activello_show_required_actions[ $activello_required_action_value['id'] ] ) && false === $activello_show_required_actions[ $activello_required_action_value['id'] ] ) {
				$hidden = true;
			}
			if ( ! empty( $activello_required_action_value['check'] ) ) {
				continue;
			}
			$nr_actions_required ++;
			if ( $hidden ) {
				$nr_action_dismissed ++;
			}

			?>
			<div class="activello-action-required-box">
				<?php if ( ! $hidden ) : ?>
					<span data-action="dismiss" class="dashicons dashicons-visibility activello-required-action-button"
						  id="<?php echo esc_attr( $activello_required_action_value['id'] ); ?>"></span>
				<?php else : ?>
					<span data-action="add" class="dashicons dashicons-hidden activello-required-action-button"
						  id="<?php echo esc_attr( $activello_required_action_value['id'] ); ?>"></span>
				<?php endif; ?>
				<h3><?php if ( ! empty( $activello_required_action_value['title'] ) ) : echo esc_html( $activello_required_action_value['title'] );
endif; ?></h3>
				<p>
					<?php if ( ! empty( $activello_required_action_value['description'] ) ) : echo wp_kses_post( $activello_required_action_value['description'] );
endif; ?>
					<?php if ( ! empty( $activello_required_action_value['help'] ) ) : echo '<br/>' . wp_kses_post( $activello_required_action_value['help'] );
endif; ?>
				</p>
				<?php
				if ( ! empty( $activello_required_action_value['plugin_slug'] ) ) {
					$active = $activello_welcome->check_active( $activello_required_action_value['plugin_slug'] );
					$url    = $activello_welcome->create_action_link( $active['needs'], $activello_required_action_value['plugin_slug'] );
					$label  = '';
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
					<p class="plugin-card-<?php echo esc_attr( $activello_required_action_value['plugin_slug'] ) ?> action_button <?php echo ( 'install' !== $active['needs'] && $active['status'] ) ? 'active' : '' ?>">
						<a data-slug="<?php echo esc_attr( $activello_required_action_value['plugin_slug'] ) ?>"
						   class="<?php echo esc_attr( $class ); ?>"
						   href="<?php echo esc_url( $url ) ?>"> <?php echo esc_html( $label ) ?> </a>
					</p>
					<?php
				};
				?>
			</div>
			<?php
		endforeach;
	endif;
	$nr_recommended_plugins = 0;
	if ( 0 == $nr_actions_required || $nr_actions_required == $nr_action_dismissed ) :

		$activello_show_recommended_plugins = get_option( 'activello_show_recommended_plugins' );
		foreach ( $activello_recommended_plugins as $slug => $plugin_opt ) {

			if ( ! $plugin_opt['recommended'] ) {
				continue;
			}

			if ( 0 == $nr_recommended_plugins ) {
				echo '<h3 class="hooray">' . esc_html__( 'Hooray! There are no required actions for you right now. But you can make your theme more powerful with next actions: ', 'activello' ) . '</h3>';
			}

			$nr_recommended_plugins ++;
			echo '<div class="activello-action-required-box">';

			if ( ! isset( $activello_show_recommended_plugins[ $slug ] ) || ( isset( $activello_show_recommended_plugins[ $slug ] ) && $activello_show_recommended_plugins[ $slug ] ) ) : ?>
				<span data-action="dismiss" class="dashicons dashicons-visibility activello-recommended-plugin-button"
					  id="<?php echo esc_attr( $slug ); ?>"></span>
			<?php else : ?>
				<span data-action="add" class="dashicons dashicons-hidden activello-recommended-plugin-button"
					  id="<?php echo esc_attr( $slug ); ?>"></span>
			<?php endif;

			$active = $activello_welcome->check_active( $slug );
			$url    = $activello_welcome->create_action_link( $active['needs'], $slug );
			$info   = $activello_welcome->call_plugin_api( $slug );

			if ( is_wp_error( $info ) ) {
				echo '</div>';
				continue;
			}

			$label  = '';
			$class = '';
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
			<h3><?php echo esc_html( $label . ': ' . ( isset( $info->name ) ? $info->name : $slug ) ); ?></h3>
			<p>
				<?php echo isset( $info->short_description ) ? esc_html( $info->short_description ) : ''; ?>
			</p>
			<p class="plugin-card-<?php echo esc_attr( $slug ) ?> action_button <?php echo ( 'install' != $active['needs'] && $active['status'] ) ? 'active' : '' ?>">
				<a data-slug="<?php echo esc_attr( $slug ) ?>"
				   class="<?php echo esc_attr( $class ); ?>"
				   href="<?php echo esc_url( $url ) ?>"> <?php echo esc_html( $label ) ?> </a>
			</p>
			<?php

			echo '</div>';

		}// End foreach().

	endif;

	if ( 0 == $nr_recommended_plugins && 0 == $nr_actions_required ) {
		echo '<span class="hooray">' . esc_html__( 'Hooray! There are no required actions for you right now.', 'activello' ) . '</span>';
	}

	?>

</div>
