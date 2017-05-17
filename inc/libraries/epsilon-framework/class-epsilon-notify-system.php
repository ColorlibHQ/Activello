<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Epsilon_Notify_System
 */
class Epsilon_Notify_System {
	/**
	 * @param $ver
	 *
	 * @return mixed
	 */
	public static function version_check( $ver ) {
		$theme = wp_get_theme();

		return version_compare( $theme['Version'], $ver, '>=' );
	}

	/**
	 * @return bool
	 */
	public static function is_not_static_page() {
		return 'page' == get_option( 'show_on_front' ) ? true : false;
	}

}
