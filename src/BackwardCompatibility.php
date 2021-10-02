<?php
/**
 * WooCommerce Korea - Backward Compatibility
 */

namespace Greys\WooCommerce\Korea;

defined( 'ABSPATH' ) || exit;

use \Greys\WooCommerce\Korea\VERSION;
use \Greys\WooCommerce\Korea\MAIN_FILE;
use \Greys\WooCommerce\Korea\ABSPATH;
use \Greys\WooCommerce\Korea\PLUGIN_URL;
use \Greys\WooCommerce\Korea\PLUGIN_PATH;

/**
 * BackwardCompatibility class.
 */
class BackwardCompatibility {

	/**
	 * Initialize
	 */
	public static function init() {
		// Classes.
		class_alias( '\Greys\WooCommerce\Korea\Loader', 'WC_Korea' );
		class_alias( '\Greys\WooCommerce\Korea\Payment\PaymentGateway', 'WC_Korea_Payment_Gateway' );
		class_alias( '\Greys\WooCommerce\Korea\Updater\License', 'WC_Korea_License' );

		// Constants.
		define( 'WC_KOREA_VERSION', VERSION );
		define( 'WC_KOREA_MAIN_FILE', MAIN_FILE );
		define( 'WC_KOREA_ABSPATH', ABSPATH );
		define( 'WC_KOREA_PLUGIN_URL', PLUGIN_URL );
		define( 'WC_KOREA_PLUGIN_PATH', PLUGIN_PATH );
	}

}
