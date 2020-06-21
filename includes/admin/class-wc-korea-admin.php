<?php
/**
 * WooCommerce Korea - Admin
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Admin class.
 */
class WC_Korea_Admin {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_scripts() {
		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-admin', plugins_url( 'assets/js/admin.js', WC_KOREA_MAIN_FILE ), array(), WC_KOREA_VERSION, true );
		wp_enqueue_style( 'wc-korea-admin', plugins_url( 'assets/css/admin.css', WC_KOREA_MAIN_FILE ), array(), WC_KOREA_VERSION, true );
	}

}
