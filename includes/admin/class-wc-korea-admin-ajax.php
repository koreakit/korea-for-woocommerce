<?php
/**
 * WooCommerce Korea - Ajax
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Admin_Ajax {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_wc_korea_testaccounts', array( $this, 'get_testaccounts' ) );
	}

	public function get_testaccounts() {
		$users = get_users(
			array(
				'search'   => sanitize_text_field( $_REQUEST['q'] ) . '*',
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
