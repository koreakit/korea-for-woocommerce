<?php
/**
 * Main Class.
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;


	class WC_Korea {

		/**
		 * @var Singleton The reference the *Singleton* instance of this class
		 */
		private static $instance;

		/**
		 * @var Reference to logging class.
		 */
		private static $log;

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
		private function __clone() {}

		/**
		 * Private unserialize method to prevent unserializing of the *Singleton*
		 * instance.
		 *
		 * @return void
		 */
		private function __wakeup() {}

		/**
		 * Protected constructor to prevent creating a new instance of the
		 * *Singleton* via the `new` operator from outside of this class.
		 */
		private function __construct() {
			if ( ! $this->are_requirements_met() ) {
				return;
			}

			$this->load_plugin_textdomain();
			$this->includes();
			$this->init();
		}

		/**
		 * Verify if the requirements are met
		 */
		public function are_requirements_met() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action(
					'admin_notices',
					function() {
						echo '<div class="error"><p><strong>' . __( 'Korea for WooCommerce requires WooCommerce to be installed and active. You can download <a href="https://woocommerce.com/" target="_blank">WooCommerce</a> here.', 'korea-for-woocommerce' ) . '</strong></p></div>';
					}
				);
				return false;
			}

			return true;
		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function includes() {
			if ( is_admin() ) {
				require_once dirname( __FILE__ ) . '/admin/class-wc-korea-admin-ajax.php';
				require_once dirname( __FILE__ ) . '/admin/class-wc-korea-admin.php';

				// Addons
				require_once dirname( __FILE__ ) . '/admin/class-wc-korea-addons.php';
				require_once dirname( __FILE__ ) . '/admin/addons/class-wc-korea-addons-licenses.php';
				require_once dirname( __FILE__ ) . '/admin/addons/class-wc-korea-addons-premium.php';
			}

			// Compatibility with 3rd party plugins
			require_once dirname( __FILE__ ) . '/compat/class-wc-korea-shipment-tracking-compat.php';
			require_once dirname( __FILE__ ) . '/compat/class-wc-korea-wpml-compat.php';

			// Integrations
			require_once dirname( __FILE__ ) . '/class-wc-korea-integration.php';
			require_once dirname( __FILE__ ) . '/analytics/class-wc-korea-naver-analytics.php';
			require_once dirname( __FILE__ ) . '/support/class-wc-korea-kakao-channel.php';
			require_once dirname( __FILE__ ) . '/support/class-wc-korea-naver-talktalk.php';
			require_once dirname( __FILE__ ) . '/sep/class-wc-korea-daum-sep.php';
			require_once dirname( __FILE__ ) . '/sep/class-wc-korea-naver-sep.php';
			require_once dirname( __FILE__ ) . '/class-wc-korea-helper.php';
			require_once dirname( __FILE__ ) . '/class-wc-korea-postcode.php';

			// Payment Gateways (Addons)
			require_once dirname( __FILE__ ) . '/abstracts/abstract-wc-korea-payment-gateway.php';

			// License for Korea for WooCommerce Addons
			require_once dirname( __FILE__ ) . '/updater/class-wc-korea-license.php';
		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function init() {
			add_filter( 'woocommerce_integrations', array( $this, 'wc_integrations' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( WC_KOREA_MAIN_FILE ), array( $this, 'plugin_action_links' ) );
			add_filter( 'query_vars', array( $this, 'wc_sep_query_var' ) );
		}

		public function wc_integrations( $integrations ) {
			$integrations[] = 'WC_Korea_Integration';
			return $integrations;
		}

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

		/**
		 * Adds plugin action links.
		 */
		public function plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="admin.php?page=wc-settings&tab=integration&section=korea">' . esc_html__( 'Settings', 'korea-for-woocommerce' ) . '</a>',
				'<a href="admin.php?page=wc-addons&section=wc-korea">' . esc_html__( 'Addons', 'korea-for-woocommerce' ) . '</a>',
				'<a href="https://greys.co/contact/">' . esc_html__( 'Support', 'korea-for-woocommerce' ) . '</a>',
			);

			return array_merge( $plugin_links, $links );
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @access public
		 * @return bool
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'korea-for-woocommerce', false, plugin_basename( WC_KOREA_ABSPATH ) . '/i18n' );
		}

	}

}
