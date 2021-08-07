<?php
/**
 * Plugin Name: Korea for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/korea-for-woocommerce/
 * Description: WooCommerce Toolkit for Korean use.
 * Version: 1.1.6
 * Author: GREYS
 * Author URI: https://greys.co/
 * Requires at least: 5.0.0
 * Tested up to: 5.6.0
 * WC requires at least: 3.4.0
 * WC tested up to: 4.9.0
 *
 * Text Domain: korea-for-woocommerce
 * Domain Path: /i18n/
 *
 * @package Greys\WooCommerce\Korea
 * @author  GREYS
 */

defined( 'ABSPATH' ) || exit;

require_once( 'vendor/autoload.php' );

// Define Constants.
define( 'WC_KOREA_VERSION', '1.1.6' );
define( 'WC_KOREA_MAIN_FILE', __FILE__ );
define( 'WC_KOREA_ABSPATH', dirname( __FILE__ ) );
define( 'WC_KOREA_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_KOREA_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
* Initialize the plugin.
*/
add_action( 'plugins_loaded', array( '\Greys\WooCommerce\Korea\Loader', 'get_instance' ) );
