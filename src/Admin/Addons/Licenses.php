<?php
/**
 * WooCommerce Korea - License Addons
 *
 * @package WC_Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Admin\Addons;

defined( 'ABSPATH' ) || exit;

use const Greys\WooCommerce\Korea\PLUGIN_PATH as PLUGIN_PATH;
use Greys\WooCommerce\Korea\Classes\Addons;

/**
 * Licenses class.
 *
 * @extends Addons
 */
final class Licenses extends Addons {

	/**
	 * Outputs settings for all license sections.
	 *
	 * @since 1.0.0
	 */
	public function output() {
		$tab = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : null; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended

		if ( 'licenses' !== $tab ) {
			return;
		}

		// Licenses
		$licenses = apply_filters( 'woocommerce_korea_plugin_license_settings', array() );

		/**
		 * Licenses page view.
		 *
		 * @uses $licenses
		 */
		include_once PLUGIN_PATH . '/includes/addons/html-admin-page-korea-licenses.php';
	}

}
