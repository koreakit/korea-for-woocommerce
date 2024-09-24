<?php
/**
 * Main Class.
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea class.
 */
class WC_Korea {

	/**
	 * The single instance of the class.
	 *
	 * @var WC_Korea
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
		if ( ! $this->requirements() ) {
			return;
		}

		$this->load_plugin_textdomain();
		$this->includes();
		$this->hooks();

		do_action( 'woocommerce_korea_loaded' );
	}

	/**
	 * Verify if the requirements are met
	 */
	public function requirements() {
		// WooCommerce
		if ( ! class_exists( 'WooCommerce' ) ) {
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
	 public function load_plugin_textdomain() {
		$locale = determine_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'korea-for-woocommerce' );

		unload_textdomain( 'korea-for-woocommerce' );
		load_textdomain( 'korea-for-woocommerce', WP_LANG_DIR . '/korea-for-woocommerce/korea-for-woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'korea-for-woocommerce', false, plugin_basename( dirname( WC_KOREA_ABSPATH ) ) . '/i18n' );
	}

	/**
	 * Init the plugin after plugins_loaded so environment variables are set.
	 */
	public function includes() {
		if ( is_admin() ) {
			require_once WC_KOREA_ABSPATH . '/includes/admin/class-wc-korea-admin-ajax.php';
			require_once WC_KOREA_ABSPATH . '/includes/admin/class-wc-korea-admin.php';

			// Addons.
			require_once WC_KOREA_ABSPATH . '/includes/admin/class-wc-korea-addons.php';
			require_once WC_KOREA_ABSPATH . '/includes/admin/addons/class-wc-korea-addons-licenses.php';
			require_once WC_KOREA_ABSPATH . '/includes/admin/addons/class-wc-korea-addons-premium.php';
		}

		// Compatibility with 3rd party plugins.
		require_once WC_KOREA_ABSPATH . '/includes/compat/class-wc-korea-shipment-tracking-compat.php';
		require_once WC_KOREA_ABSPATH . '/includes/compat/class-wc-korea-ml-compat.php';

		// Integrations.
		require_once WC_KOREA_ABSPATH . '/includes/class-wc-korea-integration.php';
		require_once WC_KOREA_ABSPATH . '/includes/analytics/class-wc-korea-naver-analytics.php';
		require_once WC_KOREA_ABSPATH . '/includes/support/class-wc-korea-kakao-channel.php';
		require_once WC_KOREA_ABSPATH . '/includes/support/class-wc-korea-naver-talktalk.php';
		require_once WC_KOREA_ABSPATH . '/includes/sep/class-wc-korea-daum-sep.php';
		require_once WC_KOREA_ABSPATH . '/includes/sep/class-wc-korea-naver-sep.php';
		require_once WC_KOREA_ABSPATH . '/includes/class-wc-korea-helper.php';
		require_once WC_KOREA_ABSPATH . '/includes/class-wc-korea-postcode.php';
		require_once WC_KOREA_ABSPATH . '/includes/wc-korea-hooks.php';

		// Payment Gateways (Addons).
		require_once WC_KOREA_ABSPATH . '/includes/abstracts/abstract-wc-korea-payment-gateway.php';

		// License for Korea for WooCommerce Addons.
		require_once WC_KOREA_ABSPATH . '/includes/updater/class-wc-korea-license.php';
	}

	/**
	 * Hooks
	 */
	public function hooks() {
		add_action( 'before_woocommerce_init', array( $this, 'enable_hpos_compatibility' ) );
		add_filter( 'woocommerce_integrations', array( $this, 'wc_integrations' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( WC_KOREA_MAIN_FILE ), array( $this, 'plugin_action_links' ) );
		add_filter( 'query_vars', array( $this, 'wc_sep_query_var' ) );
	}

	/**
	 * Enable HPOS compatibility.
	 */
	public function enable_hpos_compatibility() {
		if ( ! class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			return;
		}

		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', plugin_basename( WC_KOREA_MAIN_FILE ), true );
	}

	/**
	 * Add a Korean integration to WooCommerce.
	 *
	 * @param array $integrations Integrations.
	 * @return array
	 */
	public function wc_integrations( $integrations ) {
		$integrations[] = 'WC_Korea_Integration';
		return $integrations;
	}

	/**
	 * Adds plugin action links.
	 *
	 * @param array $links Links.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		return array_merge(
			array(
				'<a href="admin.php?page=wc-settings&tab=integration&section=korea">' . esc_html__( 'Settings', 'korea-for-woocommerce' ) . '</a>',
				'<a href="admin.php?page=wc-addons&section=wc-korea">' . esc_html__( 'Addons', 'korea-for-woocommerce' ) . '</a>',
				'<a href="https://greys.co/contact/">' . esc_html__( 'Support', 'korea-for-woocommerce' ) . '</a>',
			),
			$links
		);
	}

	/**
	 * Add SEP query vars
	 *
	 * @param array $query_vars The array of available query variables.
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
	 *
	 * @return array
	 */
	public function wc_sep_query_var( $query_vars ) {
		$query_vars[] = 'wc-sep';
		return $query_vars;
	}

	/**
	 * Updates the plugin version in db
	 */
	public function update_plugin_version() {
		delete_option( 'woocommerce_korea_version' );
		update_option( 'woocommerce_korea_version', WC_KOREA_VERSION );
	}

}
