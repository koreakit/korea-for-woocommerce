<?php
/**
 * WooCommerce Korea - Multilingual
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Shipment_Tracking class.
 */
class WC_Korea_ML_Compat {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'admin_footer' ), 50 );
	}

	/**
	 * Dequeue WPML select2 script in woocommerce page settings.
	 */
	public function admin_footer() {
		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		wp_dequeue_script( 'wpml-select-2' );
	}

}

return new WC_Korea_ML_Compat();
