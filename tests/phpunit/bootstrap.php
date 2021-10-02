<?php
/**
 * WooCommerce Korea for WooCommerce Unit Tests Bootstrap
 */

namespace Greys\WooCommerce\Korea\Tests;

/**
 * Class Bootstrap
 */
class Bootstrap {

	/** @var Bootstrap instance */
	protected static $instance = null;

	/** @var string directory where wordpress-tests-lib is installed */
	public $wp_tests_dir;

	/** @var string testing directory */
	public $tests_dir;

	/** @var string plugin directory */
	public $plugin_dir;

	/**
	 * Setup the unit testing environment.
	 */
	public function __construct() {
		$this->tests_dir    = dirname( __FILE__ );
		$this->plugin_dir   = dirname( dirname( $this->tests_dir ) );

		ini_set( 'display_errors', 'on' ); // phpcs:ignore WordPress.PHP.IniSet.display_errors_Blacklisted
		error_reporting( E_ALL ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.prevent_path_disclosure_error_reporting, WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_error_reporting

		// Ensure server variable is set for WP email functions.
		// phpcs:disable WordPress.VIP.SuperGlobalInputUsage.AccessDetected
		if ( ! isset( $_SERVER['SERVER_NAME'] ) ) {
			$_SERVER['SERVER_NAME'] = 'localhost';
		}
		// phpcs:enable WordPress.VIP.SuperGlobalInputUsage.AccessDetected

		$this->wp_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : sys_get_temp_dir() . '/wordpress-tests-lib';

		// load test function so tests_add_filter() is available.
		require_once $this->wp_tests_dir . '/includes/functions.php';

		// load plugins.
		tests_add_filter( 'muplugins_loaded', array( $this, 'load_plugins' ) );

		// load the WP testing environment.
		require_once $this->wp_tests_dir . '/includes/bootstrap.php';

		// load WC testing framework.
		$this->includes();
	}

	/**
	 * Load plugins.
	 */
	public function load_plugins() {
		require_once WP_PLUGIN_DIR .'/woocommerce/woocommerce.php';
		require_once $this->plugin_dir .'/korea-for-woocommerce.php';
	}

	/**
	 * Load specific test cases and factories.
	 */
	public function includes() {
		// Helpers.
		require_once __DIR__ . '/helpers/class-wc-helper-product.php';
		require_once __DIR__ . '/helpers/class-wc-helper-shipping.php';
		require_once __DIR__ . '/helpers/class-wc-helper-order.php';
	}

	/**
	 * Get the single class instance.
	 *
	 * @return Bootstrap
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Bootstrap::instance();
