<?php
/**
 * WC Korea Order Statuses
 *
 * @package WC_Korea
 * @author  @jgreys
 */
defined( 'ABSPATH' ) || exit;

class WC_Korea_Order_Statuses {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'init', array($this, 'wc_register_post_statuses') );
		add_filter( 'wc_order_statuses', array($this, 'wc_add_order_statuses') );
	}

	public function wc_register_post_statuses() {
		register_post_status( 'wc-partial-refunded', [
			'label'						=> __( 'Partially Refunded', 'korea-for-woocommerce' ),
			'public'					=> true,
			'exclude_from_search'		=> false,
			'show_in_admin_all_list'	=> true,
			'show_in_admin_status_list'	=> true,
			'post_type'                 => ['shop_order'],
			'label_count'				=> _n_noop( 'Partially Refunded <span class="count">(%s)</span>', 'Partially Refunded <span class="count">(%s)</span>', 'korea-for-woocommerce' )
		]);
	}

	public function wc_add_order_statuses( $order_statuses ) {
		$order_statuses['wc-partial-refunded'] = __( 'Partially Refunded', 'korea-for-woocommerce' );
		return $order_statuses;
	}
}

new WC_Korea_Order_Statuses();