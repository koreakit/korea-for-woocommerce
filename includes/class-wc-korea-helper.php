<?php
/**
 * WooCommerce Korea - Helper
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Helper {

	/**
	 * Get test account
	 *
	 * @return array
	 */
	public static function get_testaccount( $id ) {
		if ( empty( $id ) ) {
			return null;
		}

		$data     = array();
		$settings = get_option( 'woocommerce_' . $id . '_settings' );

		if ( ! isset( $settings['testaccount'] ) ) {
			return null;
		}

		$testaccount = get_user_by( 'id', $settings['testaccount'] );

		if ( ! $testaccount ) {
			return null;
		}

		$data[ $testaccount->ID ] = $testaccount->user_email;

		return $data;
	}

}
