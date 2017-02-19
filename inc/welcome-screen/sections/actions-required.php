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
	global $activello_required_actions;
	if ( ! empty( $activello_required_actions ) ):
		/* activello_show_required_actions is an array of true/false for each required action that was dismissed */
		$activello_show_required_actions = get_option( "activello_show_required_actions" );
		foreach ( $activello_required_actions as $activello_required_action_key => $activello_required_action_value ):
			if ( @$activello_show_required_actions[ $activello_required_action_value['id'] ] === false ) {
				continue;
			}
			if ( @$activello_required_action_value['check'] ) {
				continue;
			}
			?>
			<div class="activello-action-required-box">
				<span class="dashicons dashicons-no-alt activello-dismiss-required-action"
				      id="<?php echo $activello_required_action_value['id']; ?>"></span>
				<h3><?php if ( ! empty( $activello_required_action_value['title'] ) ): echo $activello_required_action_value['title']; endif; ?></h3>
				<p>
					<?php if ( ! empty( $activello_required_action_value['description'] ) ): echo $activello_required_action_value['description']; endif; ?>
					<?php if ( ! empty( $activello_required_action_value['help'] ) ): echo '<br/>' . $activello_required_action_value['help']; endif; ?>
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
					<p class="plugin-card-<?php echo esc_attr( $activello_required_action_value['plugin_slug'] ) ?> action_button <?php echo ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ?>">
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
	$nr_actions_required = 0;
	/* get number of required actions */
	if ( get_option( 'activello_show_required_actions' ) ):
		$activello_show_required_actions = get_option( 'activello_show_required_actions' );
	else:
		$activello_show_required_actions = array();
	endif;
	if ( ! empty( $activello_required_actions ) ):
		foreach ( $activello_required_actions as $activello_required_action_value ):
			if ( ( ! isset( $activello_required_action_value['check'] ) || ( isset( $activello_required_action_value['check'] ) && ( $activello_required_action_value['check'] == false ) ) ) && ( ( isset( $activello_show_required_actions[ $activello_required_action_value['id'] ] ) && ( $activello_show_required_actions[ $activello_required_action_value['id'] ] == true ) ) || ! isset( $activello_show_required_actions[ $activello_required_action_value['id'] ] ) ) ) :
				$nr_actions_required ++;
			endif;
		endforeach;
	endif;
	if ( $nr_actions_required == 0 ):
		echo '<span class="hooray">' . __( 'Hooray! There are no required actions for you right now.', 'activello' ) . '</span>';
	endif;
	?>

</div>