<?php
/**
 * Plugin Name: Korea for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/korea-for-woocommerce/
 * Description: WooCommerce Toolkit for Korean use.
 * Version: 1.1.1
 * Author: GREYS
 * Author URI: https://greys.co/
 * Requires at least: 4.9.0
 * Tested up to: 5.5.0
 * WC requires at least: 3.4.0
 * WC tested up to: 4.3.0
 *
 * Text Domain: korea-for-woocommerce
 * Domain Path: /i18n/
 *
 * @package korea-for-woocommerce
 * @author  GREYS
 */

defined( 'ABSPATH' ) || exit;

// Define Constants.
define( 'WC_KOREA_VERSION', '1.1.1' );
define( 'WC_KOREA_MAIN_FILE', __FILE__ );
define( 'WC_KOREA_ABSPATH', dirname( __FILE__ ) );
define( 'WC_KOREA_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_KOREA_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

// Include the main WC_Korea class.
if ( ! class_exists( 'WC_Korea' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wc-korea.php';
}

/**
* Hook to run when your plugin is activated
*/
register_activation_hook( __FILE__, array( 'WC_Korea', 'install' ) );

/**
* Initialize the plugin.
*/
add_action( 'plugins_loaded', array( 'WC_Korea', 'get_instance' ) );
