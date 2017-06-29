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

			wp_enqueue_style( 'activello-admin-nux', get_template_directory_uri() . '/assets/css/admin.css', '', '' );

			wp_enqueue_script( 'activello-admin-nux', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), '', 'all' );
			wp_enqueue_script( 'activello-plugin-install-nux', get_template_directory_uri() . '/assets/js/plugin-install.js', array( 'jquery', 'updates' ), '', 'all' );

			$activello_nux = array(
				'nonce' => wp_create_nonce( 'activello_notice_dismiss' ),
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
					<h2><?php esc_html_e( 'Thanks for installing Activello, you rock!', 'activello' ) ?> <img draggable="false" class="emoji" alt="🤘" src="https://s.w.org/images/core/emoji/2.2.1/svg/1f918.svg"></h2>
					<p><?php esc_html_e( 'Activello now support full width posts on homepage. If you have done this using custom CSS please go to Customizer -> Activello Options -> Layout Options -> Blog Posts Layout Options in order to have full width images.', 'activello' ) ?></p>
					<p><?php esc_html_e( "Also in order to increase our theme speed we changed the images' sizes. In order to take advantage of this improvement you'll need to use Force Regenerate Thumbnails to regenerate all your image sizes.", 'activello' ) ?></p>
					<p><?php $this->install_plugin_button( 'force-regenerate-thumbnails', 'force-regenerate-thumbnails.php', 'Force Regenerate Thumbnails', array( 'sf-nux-button' ), __( 'Regenerate Thumbnails Now', 'activello' ), __( 'Activate Force Regenerate Thumbnails', 'activello' ), __( 'Install Force Regenerate Thumbnails', 'activello' ) ); ?></p>
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

		public function install_plugin_button( $plugin_slug, $plugin_file, $plugin_name, $classes = array(), $activated = '', $activate = '', $install = '' ) {
			if ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) {
				$url = $this->_is_plugin_installed( $plugin_slug );
				if ( is_plugin_active( $plugin_slug . '/' . $plugin_file ) ) {
					// The plugin is already active
					$button = array(
						'message' => esc_attr__( 'Settings', 'activello' ),
						'url'     => admin_url( 'tools.php?page=force-regenerate-thumbnails' ),
						'classes' => array( 'activello-button', 'disabled' ),
					);

					if ( '' !== $activated ) {
						$button['message'] = esc_attr( $activated );
					}
				} elseif ( $url ) {
					// The plugin exists but isn't activated yet.
					$button = array(
						'message' => esc_attr__( 'Activate', 'activello' ),
						'url'     => $url,
						'classes' => array( 'activello-button', 'activate-now' ),
					);

					if ( '' !== $activate ) {
						$button['message'] = esc_attr( $activate );
					}
				} else {
					// The plugin doesn't exist.
					$url = wp_nonce_url( add_query_arg( array(
						'action' => 'install-plugin',
						'plugin' => $plugin_slug,
					), self_admin_url( 'update.php' ) ), 'install-plugin_' . $plugin_slug );
					$button = array(
						'message' => esc_attr__( 'Install now', 'activello' ),
						'url'     => $url,
						'classes' => array( 'activello-button', 'sf-install-now', 'install-now', 'install-' . $plugin_slug ),
					);

					if ( '' !== $install ) {
						$button['message'] = esc_attr( $install );
					}
				}// End if().

				if ( ! empty( $classes ) ) {
					$button['classes'] = array_merge( $button['classes'], $classes );
				}

				$button['classes'] = implode( ' ', $button['classes'] );

				?>
				<span class="sf-plugin-card plugin-card-<?php echo esc_attr( $plugin_slug ); ?>">
					<a href="<?php echo esc_url( $button['url'] ); ?>" class="<?php echo esc_attr( $button['classes'] ); ?>" data-originaltext="<?php echo esc_attr( $button['message'] ); ?>" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $plugin_slug ); ?>" aria-label="<?php echo esc_attr( $button['message'] ); ?>"><?php echo esc_attr( $button['message'] ); ?></a>
				</span>
				<?php
			}// End if().
		}

		private function _is_plugin_installed( $plugin_slug ) {
			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
				$plugins = get_plugins( '/' . $plugin_slug );
				if ( ! empty( $plugins ) ) {
					$keys        = array_keys( $plugins );
					$plugin_file = $plugin_slug . '/' . $keys[0];
					$url         = wp_nonce_url( add_query_arg( array(
						'action' => 'activate',
						'plugin' => $plugin_file,
					), admin_url( 'plugins.php' ) ), 'activate-plugin_' . $plugin_file );
					return $url;
				}
			}
			return false;
		}

	}

endif;

return new Activello_NUX_Admin();
