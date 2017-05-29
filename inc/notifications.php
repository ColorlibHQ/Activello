<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Activello_NUX_Admin' ) ) :

	/**
	 * The Storefront NUX Admin class
	 */
	class Activello_NUX_Admin {
		/**
		 * Setup class.
		 *
		 * @since 2.2.0
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts',                   array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_notices',                           array( $this, 'admin_notices' ), 99 );
			add_action( 'wp_ajax_activello_dismiss_notice',       array( $this, 'dismiss_nux' ) );
		}

		/**
		 * Enqueue scripts.
		 *
		 * @since 2.2.0
		 */
		public function enqueue_scripts() {
			global $wp_customize;

			if ( isset( $wp_customize ) || true === (bool) get_option( 'activello_nux_dismissed' ) ) {
				return;
			}

			wp_enqueue_style( 'activello-admin-nux', get_template_directory_uri() . '/inc/css/admin.css', '', '' );

			wp_enqueue_script( 'activello-admin-nux', get_template_directory_uri() . '/inc/js/admin.js', array( 'jquery' ), '', 'all' );

			$activello_nux = array(
				'nonce' => wp_create_nonce( 'activello_notice_dismiss' )
			);

			wp_localize_script( 'activello-admin-nux', 'activelloNUX', $activello_nux );
		}

		/**
		 * Output admin notices.
		 *
		 * @since 2.2.0
		 */
		public function admin_notices() {
			global $pagenow;
			if ( true === (bool) get_option( 'activello_nux_dismissed' ) ) {
				return;
			}
			?>

			<div class="notice notice-info sf-notice-nux is-dismissible">
				<div class="notice-content">
					<p>Hello, we want to inform you that we added in this version of Activello a new type of blog page layout. Now we introduced an option to make all posts to be full width, to do this go to Customizer -> Activello Options -> Layout Options -> Blog Posts Layout Options. In the previous version they were: 2 full width posts then all were half width posts .  If you previously achieved this with custom CSS you will need to go to select this option in order for the pictures to be full width.</p>
					<p>Also in order to increase our theme speed we changed the images' sizes. In order to take advantage of this improvement you'll need to use <a href="https://wordpress.org/plugins/force-regenerate-thumbnails/" target="_blank">Force Regenerate Thumbnails</a> to regenerate all your image sizes.</p>
				</div>
			</div>
		<?php }

		/**
		 * AJAX dismiss notice.
		 *
		 * @since 2.2.0
		 */
		public function dismiss_nux() {
			$nonce = ! empty( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if ( ! $nonce || ! wp_verify_nonce( $nonce, 'activello_notice_dismiss' ) || ! current_user_can( 'manage_options' ) ) {
				die();
			}

			update_option( 'activello_nux_dismissed', true );
		}

	}

endif;

return new Activello_NUX_Admin();