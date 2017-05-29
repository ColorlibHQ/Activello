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
					<p><img src="<?php echo get_template_directory_uri() ?>/inc/welcome-screen/img/logo.png" width="200"></p>
					<p><?php esc_html_e('Activello now support full width posts on homepage. If you have done this usiging custom CSS please go to Customizer -> Activello Options -> Layout Options -> Blog Posts Layout Options in order to have full width images.', 'activello') ?></p>
					<p><?php printf( '%s <a href="%s" target="_blank">%s</a> %s', esc_html__( "Also in order to increase our theme speed we changed the images' sizes. In order to take advantage of this improvement you'll need to use", 'activello' ), esc_url( 'https://wordpress.org/plugins/force-regenerate-thumbnails/' ), esc_html__('Force Regenerate Thumbnails','activello'), esc_html__('to regenerate all your image sizes.','activello') ) ?></p>
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