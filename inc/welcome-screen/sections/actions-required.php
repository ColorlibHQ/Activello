<?php
/**
 * Actions required
 */
wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
?>

<div class="feature-section action-required demo-import-boxed" id="plugin-filter">

	<?php
	global $activello_required_actions, $activello_recommended_plugins;
	if ( ! empty( $activello_required_actions ) ) :
		/* activello_show_required_actions is an array of true/false for each required action that was dismissed */
		$nr_actions_required = 0;
		$nr_action_dismissed = 0;
		$activello_show_required_actions = get_option( 'activello_show_required_actions' );
		foreach ( $activello_required_actions as $activello_required_action_key => $activello_required_action_value ) :
			$hidden = false;
			if ( false === @$activello_show_required_actions[ $activello_required_action_value['id'] ] ) {
				$hidden = true;
			}
			if ( @$activello_required_action_value['check'] ) {
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
				<h3><?php if ( ! empty( $activello_required_action_value['title'] ) ) : echo $activello_required_action_value['title'];
endif; ?></h3>
				<p>
					<?php if ( ! empty( $activello_required_action_value['description'] ) ) : echo $activello_required_action_value['description'];
endif; ?>
					<?php if ( ! empty( $activello_required_action_value['help'] ) ) : echo '<br/>' . $activello_required_action_value['help'];
endif; ?>
				</p>
				<?php
				if ( ! empty( $activello_required_action_value['plugin_slug'] ) ) {
					$active = $this->check_active( $activello_required_action_value['plugin_slug'] );
					$url    = $this->create_action_link( $active['needs'], $activello_required_action_value['plugin_slug'] );
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
						   class="<?php echo $class; ?>"
						   href="<?php echo esc_url( $url ) ?>"> <?php echo $label ?> </a>
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

			if ( Allegiant_Notify_System::has_plugin( $slug ) ) {
				continue;
			}
			if ( 0 == $nr_recommended_plugins ) {
				echo '<h3 class="hooray">' . __( 'Hooray! There are no required actions for you right now. But you can make your theme more powerful with next actions: ', 'activello' ) . '</h3>';
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

			$active = $this->check_active( $slug );
			$url    = $this->create_action_link( $active['needs'], $slug );
			$info   = $this->call_plugin_api( $slug );
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
			<h3><?php echo $label . ': ' . $info->name ?></h3>
			<p>
				<?php echo $info->short_description ?>
			</p>
			<p class="plugin-card-<?php echo esc_attr( $slug ) ?> action_button <?php echo ( 'install' != $active['needs'] && $active['status'] ) ? 'active' : '' ?>">
				<a data-slug="<?php echo esc_attr( $slug ) ?>"
				   class="<?php echo $class; ?>"
				   href="<?php echo esc_url( $url ) ?>"> <?php echo $label ?> </a>
			</p>
			<?php

			echo '</div>';

		}// End foreach().

	endif;

	if ( 0 == $nr_recommended_plugins && 0 == $nr_actions_required ) {
		echo '<span class="hooray">' . __( 'Hooray! There are no required actions for you right now.', 'activello' ) . '</span>';
	}

	?>

</div>
