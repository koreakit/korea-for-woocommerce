<?php
/**
 * WooCommerce Korea - WPML
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_WPML {

	public function __construct() {
		add_action( 'admin_footer', array( $this, 'admin_footer' ), 50 );
	}

	public function admin_footer() {
		$screen = get_current_screen();

		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		wp_dequeue_script( 'wpml-select-2' );
	}

}

return new WC_Korea_WPML();
