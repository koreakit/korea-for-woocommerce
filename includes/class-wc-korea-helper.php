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
		if ( empty($id) ) {
			return NULL;
		}

		$data        = [];
		$settings    = get_option( 'woocommerce_'. $id .'_settings' );

		if ( ! isset($settings['testaccount']) ) {
			return NULL;
		}

		$testaccount = get_user_by( 'id', $settings['testaccount'] );

		if ( ! $testaccount ) {
			return NULL;
		}

		$data[ $testaccount->ID ] = $testaccount->user_email;

		return $data;
	}
	
}