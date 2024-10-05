<?php
/**
 * Loader Class.
 *
 * @package Greys\WooCommerce\Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use const Greys\WooCommerce\Korea\MainFile as MAIN_FILE;
use const Greys\WooCommerce\Korea\Basename as BASENAME;

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
	public static function instance() {
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
		// WooCommerce
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'missing_wc_notice' ) );
			return;
		}

		self::load_plugin_textdomain();
		self::admin_init();
		self::init();
		self::hooks();

		do_action( 'woocommerce_korea_loaded' );
	}

	/**
	 * WooCommerce fallback notice.
	 */
	public static function missing_wc_notice() {
		/* translators: 1. URL link. */
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Korea for WooCommerce requires WooCommerce to be installed and active. You can download %s here.', 'korea-for-woocommerce' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
	}

	/**
	* Initialize the plugin.
	*/
	public function admin_init() {
		if ( ! is_admin() ) {
			return;
		}

		do_action( 'woocommerce_korea_admin_init' );

		$load_admin_classes = array(
			__NAMESPACE__ . '\Admin\Controller',
			__NAMESPACE__ . '\Admin\Licenses\FormHandler'
		);

		$load_admin_classes = apply_filters( 'woocommerce_korea_admin_classes', $load_admin_classes );

		foreach ( $load_admin_classes as $admin_class ) {
			new $admin_class();
		}
	}

	/**
	* Initialize the plugin.
	*/
	public function init() {
		do_action( 'woocommerce_korea_init' );

		$load_classes = array(
			__NAMESPACE__ . '\Analytics\Naver',
			__NAMESPACE__ . '\Checkout\Controller',
			__NAMESPACE__ . '\Checkout\Postcode',
			__NAMESPACE__ . '\ShoppingEnginePage\Daum',
			__NAMESPACE__ . '\ShoppingEnginePage\Naver',
			__NAMESPACE__ . '\Support\KakaoChannel',
			__NAMESPACE__ . '\Support\NaverTalkTalk',
			__NAMESPACE__ . '\BackwardCompatibility',
		);

		$load_classes = apply_filters( 'woocommerce_korea_classes', $load_classes );

		// Load classes.
		foreach ( $load_classes as $class ) {
			new $class();
		}
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
		load_plugin_textdomain( 'korea-for-woocommerce', false, plugin_basename( BASENAME ) . '/i18n' );
	}

	/**
	 * Hooks
	 */
	public static function hooks() {
		add_action( 'before_woocommerce_init', [ __CLASS__, 'enable_hpos_compatibility' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( MAIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );
	}

	/**
	 * Enable HPOS compatibility.
	 */
	public static function enable_hpos_compatibility() {
		if ( ! class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			return;
		}

		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', plugin_basename( BASENAME ), true );
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
				'<a href="admin.php?page=wc-korea-settings">' . esc_html__( 'Settings', 'korea-for-woocommerce' ) . '</a>',
				'<a href="admin.php?page=wc-korea-settings&tab=addons">' . esc_html__( 'Addons', 'korea-for-woocommerce' ) . '</a>',
				'<a href="https://greys.co/contact/">' . esc_html__( 'Support', 'korea-for-woocommerce' ) . '</a>',
			),
			$links
		);
	}

}
