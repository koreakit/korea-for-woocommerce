<?php
/**
 * WooCommerce Korea - Admin
 *
 * @package WC_Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Admin;

defined( 'ABSPATH' ) || exit;

use const Greys\WooCommerce\Korea\VERSION as VERSION;
use const Greys\WooCommerce\Korea\MAIN_FILE as MAIN_FILE;

/**
 * Controller class.
 */
class Controller {

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
	}

	/**
	 * Enqueue admin scripts
	 */
	public static function admin_scripts() {
		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-admin', plugins_url( 'assets/js/admin.js', MAIN_FILE ), array(), VERSION, true );
		wp_enqueue_style( 'wc-korea-admin', plugins_url( 'assets/css/admin.css', MAIN_FILE ), array(), VERSION, true );
	}

}
