<?php
/**
 * WooCommerce Korea - Ajax
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Admin_Ajax class.
 */
class WC_Korea_Admin_Ajax {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_wc_korea_testaccounts', array( $this, 'get_testaccounts' ) );
	}


	/**
	 * Get test accounts
	 *
	 * @return string
	 */
	public function get_testaccounts() {
		$q = isset( $_REQUEST['q'] ) && ! empty( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$users = get_users(
			array(
				'search'   => $q . '*',
				'role__in' => array( 'author', 'contributor', 'customer', 'editor', 'subscriber' ),
			)
		);

		if ( ! $users ) {
			return wp_send_json_error( 'No users' );
		}

		$data = array();
		foreach ( $users as $user ) {
			$data[ $user->ID ] = $user->user_email;
		}

		return wp_send_json_success( $data );
	}

}

new WC_Korea_Admin_Ajax();
