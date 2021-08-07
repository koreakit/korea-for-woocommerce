<?php
/**
 * WooCommerce Korea - Admin
 *
 * @package WC_Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin {

	/**
	 * Initialize admin.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );

		new Addons\Premium();
		new Addons\Licenses();
		new Licenses\FormHandler();
	}

	/**
	 * Enqueue admin scripts
	 */
	public static function admin_scripts() {
		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-admin', plugins_url( 'assets/js/admin.js', WC_KOREA_MAIN_FILE ), array(), WC_KOREA_VERSION, true );
		wp_enqueue_style( 'wc-korea-admin', plugins_url( 'assets/css/admin.css', WC_KOREA_MAIN_FILE ), array(), WC_KOREA_VERSION, true );
	}

}
