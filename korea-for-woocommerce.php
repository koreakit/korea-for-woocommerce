<?php
/**
 * Plugin Name: Korea for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/korea-for-woocommerce/
 * Description: WooCommerce Toolkit for Korean use.
 * Version: 1.1.11
 * Author: GREYS
 * Author URI: https://greys.co/
 * Requires at least: 5.0.0
 * Tested up to: 6.5
 * WC requires at least: 3.4.0
 * WC tested up to: 8.7.0
 *
 * Text Domain: korea-for-woocommerce
 * Domain Path: /i18n/
 *
 * @package korea-for-woocommerce
 * @author  GREYS
 */

defined( 'ABSPATH' ) || exit;

// Define Constants.
define( 'WC_KOREA_VERSION', '1.1.10' );
define( 'WC_KOREA_MAIN_FILE', __FILE__ );
define( 'WC_KOREA_ABSPATH', dirname( __FILE__ ) );
define( 'WC_KOREA_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_KOREA_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

// Include the main WC_Korea class.
if ( ! class_exists( 'WC_Korea' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wc-korea.php';
}

/**
* Initialize the plugin.
*/
add_action( 'plugins_loaded', array( 'WC_Korea', 'get_instance' ) );
