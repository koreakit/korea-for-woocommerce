<?php
/**
 * Plugin Name: Korea for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/korea-for-woocommerce/
 * Description: WooCommerce Toolkit for Korean use.
 * Version: 1.2.0
 * Author: Greys
 * Author URI: https://greys.co
 * Requires at least: 5.0.0
 * Tested up to: 6.3.0
 * WC requires at least: 3.4.0
 * WC tested up to: 8.3.0
 *
 * Text Domain: korea-for-woocommerce
 * Domain Path: /i18n/
 *
 * @package Greys\WooCommerce\Korea
 * @author  Greys
 */

namespace Greys\WooCommerce\Korea;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Constants.
define( __NAMESPACE__ . '\Version', '1.2.0' );
define( __NAMESPACE__ . '\MainFile', __FILE__ );
define( __NAMESPACE__ . '\Basename', dirname( __FILE__ ) );
define( __NAMESPACE__ . '\PluginUrl', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( __NAMESPACE__ . '\PluginPath', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

// Load the autoloader.
require_once 'vendor/autoload.php';

// Initialize the plugin.
add_action( 'plugins_loaded', [ __NAMESPACE__ . '\Loader', 'instance' ], 20 );
