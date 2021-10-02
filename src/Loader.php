<?php
/**
 * Loader Class.
 *
 * @package Greys\WooCommerce\Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea;

defined( 'ABSPATH' ) || exit;

use const Greys\WooCommerce\Korea\ABSPATH as ABSPATH;
use const Greys\WooCommerce\Korea\MAIN_FILE as MAIN_FILE;

/**
 * Loader class.
 */
class Loader {

	/**
	 * The single instance of the class.
	 *
	 * @var Loader
	 */
	private static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	public function __clone() {}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	public function __wakeup() {}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	public function __construct() {
		if ( ! self::requirements() ) {
			return;
		}

		self::load_plugin_textdomain();
		self::admin_init();
		self::hooks();

		do_action( 'woocommerce_korea_loaded' );
	}

	/**
	* Initialize the plugin.
	*/
	public function admin_init() {
		if ( ! is_admin() ) {
			return;
		}

		Admin::init();
	}

	/**
	 * Verify if the requirements are met
	 */
	public static function requirements() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// WooCommerce
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action(
				'admin_notices',
				function() {
					echo '<div class="error"><p>';
					echo wp_kses(
						sprintf(
							/* translators: 1) woocommerce link */
							__( 'Korea for WooCommerce requires WooCommerce to be installed and active. You can download <a href="%s" target="_blank">WooCommerce</a> here.', 'korea-for-woocommerce' ),
							'https://woocommerce.com/'
						),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					);
					echo '</p></div>';
				}
			);
			return false;
		}

		return true;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * Locales found in:
	 *  - WP_LANG_DIR/korea-for-woocommerce/korea-for-woocommerce-LOCALE.mo
	 *  - WP_LANG_DIR/plugins/korea-for-woocommerce-LOCALE.mo
	 */
	 public static function load_plugin_textdomain() {
		$locale = determine_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'korea-for-woocommerce' );

		unload_textdomain( 'korea-for-woocommerce' );
		load_textdomain( 'korea-for-woocommerce', WP_LANG_DIR . '/korea-for-woocommerce/korea-for-woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'korea-for-woocommerce', false, plugin_basename( dirname( ABSPATH ) ) . '/i18n' );
	}

	/**
	 * Hooks
	 */
	public static function hooks() {
		add_filter( 'woocommerce_integrations', array( __CLASS__, 'add_korea_integration' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( MAIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );
	}

	/**
	 * Add a Korean integration to WooCommerce.
	 *
	 * @param array $integrations Integrations.
	 * @return array
	 */
	public static function add_korea_integration( $integrations ) {
		$integrations[] = '\Greys\WooCommerce\Korea\Admin\Integration';
		return $integrations;
	}

	/**
	 * Adds plugin action links.
	 *
	 * @param array $links Links.
	 *
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		return array_merge(
			array(
				'<a href="admin.php?page=wc-settings&tab=integration&section=korea">' . esc_html__( 'Settings', 'korea-for-woocommerce' ) . '</a>',
				'<a href="admin.php?page=wc-addons&section=wc-korea">' . esc_html__( 'Addons', 'korea-for-woocommerce' ) . '</a>',
				'<a href="https://greys.co/contact/">' . esc_html__( 'Support', 'korea-for-woocommerce' ) . '</a>',
			),
			$links
		);
	}

}
