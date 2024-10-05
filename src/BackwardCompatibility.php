<?php
/**
 * WooCommerce Korea - Backward Compatibility
 */

namespace Greys\WooCommerce\Korea;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use const Greys\WooCommerce\Korea\Version as VERSION;
use const Greys\WooCommerce\Korea\MainFile as MAIN_FILE;
use const Greys\WooCommerce\Korea\PluginUrl as PLUGIN_URL;
use const Greys\WooCommerce\Korea\PluginPath as PLUGIN_PATH;

/**
 * BackwardCompatibility class.
 */
class BackwardCompatibility {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Classes.
		class_alias( '\Greys\WooCommerce\Korea\Loader', 'WC_Korea' );
		class_alias( '\Greys\WooCommerce\Korea\Payment\PaymentGateway', 'WC_Korea_Payment_Gateway' );
		class_alias( '\Greys\WooCommerce\Korea\Addons\License', 'WC_Korea_License' );

		// Constants.
		define( 'WC_KOREA_VERSION', VERSION );
		define( 'WC_KOREA_MAIN_FILE', MAIN_FILE );
		define( 'WC_KOREA_PLUGIN_URL', PLUGIN_URL );
		define( 'WC_KOREA_PLUGIN_PATH', PLUGIN_PATH );
	}

}
