<?php
/**
 * WooCommerce Korea - Updater
 *
 * @package WC_Korea
 * @author  @jgreys
 */
defined( 'ABSPATH' ) or exit;

/**
 * Adds the plugin updater API.
 *
 * @since 1.0.0
 */
class WC_Korea_Updater {

	/**
	 * @var string $api_url the URL from which updates are retrieved
	 */
	private $api_url;

	/**
	 * @var string[] $api_data the updater data
	 */
	private $api_data = [];

	/**
	 * @var string $plugin_file the plugin file
	 */
	private $plugin_file;

	/**
	 * @var string $name the plugin name
	 */
	private $name;

	/**
	 * @var string $slug the plugin slug
	 */
	private $slug;

	/**
	 * @var mixed $version the current plugin version
	 */
	private $version;

	/**
	 * @var bool $wp_override
	 */
	private $wp_override = false;

	/**
	 * @var string $cache_key key to cache requests
	 */
	private $cache_key;

	/**
	 * @var array $api_url_available checks if the URL is available
	 */
	private $api_url_available = [];


	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $_plugin_file path to the plugin file
	 * @param string[] $_api_data optional data to send with API calls
	 * @param string $_api_url the URL pointing to the custom API endpoint
	 */
	public function __construct( $_plugin_file, $_api_data, $_api_url = 'https://greys.co/' ) {
		global $wc_korea_plugin_data;

		$this->api_url     = trailingslashit( $_api_url );
		$this->api_data    = $_api_data;
		$this->plugin_file = $_plugin_file;
		$this->name        = plugin_basename( $_plugin_file );
		$this->slug        = basename( $_plugin_file, '.php' );
		$this->version     = $_api_data['version'];
		$this->wp_override = isset( $_api_data['wp_override'] ) ? (bool) $_api_data['wp_override'] : false;
		$this->beta        = ! empty( $this->api_data['beta'] ) ? true : false;
		$this->cache_key   = 'wc_korea_' . md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) );

		$wc_korea_plugin_data[ $this->slug ] = $this->api_data;

		// set up hooks
		$this->init();
	}


	/**
	 * Set up WordPress filters to hook into WP's update process.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

		add_filter( 'plugins_api', array( $this, 'plugins_api_filter' ), 10, 3 );

		remove_action( "after_plugin_row_{$this->name}", 'wp_plugin_update_row', 10 );

		add_action( "after_plugin_row_{$this->name}", array( $this, 'show_update_notification' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'show_changelog' ) );
	}


	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @since 1.0.0
	 *
	 * @param array $_transient_data Update array build by WordPress.
	 * @return array Modified update array with custom plugin data.
	 */
	public function check_update( $_transient_data ) {
		global $pagenow;

		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new \stdClass;
		}

		if ( 'plugins.php' === $pagenow && is_multisite() ) {
			return $_transient_data;
		}

		if ( ! empty( $_transient_data->response ) && ! empty( $_transient_data->response[ $this->name ] ) && false === $this->wp_override ) {
			return $_transient_data;
		}

		$version_info = $this->get_cached_version_info();

		if ( false === $version_info ) {

			$version_info = $this->api_request( 'plugin_latest_version', [
				'slug' => $this->slug,
				'beta' => $this->beta
			]);

			$this->set_version_info_cache( $version_info );
		}

		if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

			if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {
				$_transient_data->response[ $this->name ] = $version_info;
			}

			$_transient_data->last_checked           = current_time( 'timestamp' );
			$_transient_data->checked[ $this->name ] = $this->version;
		}

		return $_transient_data;
	}


	/**
	 * Show update notification row.
	 *
	 * Needed for multisite subsites, because WP won't tell you otherwise!
	 *
	 * @since 1.0.0
	 *
	 * @param string $file plugin file
	 * @param object $plugin
	 */
	public function show_update_notification( $file, $plugin ) {

		if ( is_network_admin() || ! is_multisite() || ! current_user_can( 'update_plugins' ) || $this->name != $file ) {
			return;
		}

		// remove our filter on the site transient
		remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 10 );

		$update_cache = get_site_transient( 'update_plugins' );
		$update_cache = is_object( $update_cache ) ? $update_cache : new \stdClass();

		if ( empty( $update_cache->response ) || empty( $update_cache->response[ $this->name ] ) ) {

			$version_info = $this->get_cached_version_info();

			if ( false === $version_info ) {

				$version_info = $this->api_request( 'plugin_latest_version', [
					'slug' => $this->slug,
					'beta' => $this->beta
				]);

				// since we disabled our filter for the transient, we aren't running our object conversion on banners, sections, or icons. Do this now:
				if ( isset( $version_info->banners ) && ! is_array( $version_info->banners ) ) {
					$version_info->banners = $this->convert_object_to_array( $version_info->banners );
				}

				if ( isset( $version_info->sections ) && ! is_array( $version_info->sections ) ) {
					$version_info->sections = $this->convert_object_to_array( $version_info->sections );
				}

				if ( isset( $version_info->icons ) && ! is_array( $version_info->icons ) ) {
					$version_info->icons = $this->convert_object_to_array( $version_info->icons );
				}

				$this->set_version_info_cache( $version_info );
			}

			if ( ! is_object( $version_info ) ) {
				return;
			}

			if ( version_compare( $this->version, $version_info->new_version, '<' ) ) {
				$update_cache->response[ $this->name ] = $version_info;
			}

			$update_cache->last_checked           = current_time( 'timestamp' );
			$update_cache->checked[ $this->name ] = $this->version;

			set_site_transient( 'update_plugins', $update_cache );

		} else {
			$version_info = $update_cache->response[ $this->name ];
		}

		// restore our filter
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );

		if ( ! empty( $update_cache->response[ $this->name ] ) && version_compare( $this->version, $version_info->new_version, '<' ) ) {

			// build a plugin list row, with update notification
			$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

			// <tr class="plugin-update-tr"><td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
			echo '<tr class="plugin-update-tr" id="' . $this->slug . '-update" data-slug="' . $this->slug . '" data-plugin="' . $this->slug . '/' . $file . '">';
			echo '<td colspan="3" class="plugin-update colspanchange">';
			echo '<div class="update-message notice inline notice-warning notice-alt">';

			$changelog_link = self_admin_url( 'index.php?edd_sl_action=view_plugin_changelog&plugin=' . $this->name . '&slug=' . $this->slug . '&TB_iframe=true&width=772&height=911' );

			if ( empty( $version_info->download_link ) ) {

				printf(
					__( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s.', 'korea-for-woocommerce' ),
					esc_html( $version_info->name ),
					'<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
					esc_html( $version_info->new_version ),
					'</a>'
				);

			} else {

				printf(
					__( 'There is a new version of %1$s available. %2$sView version %3$s details%4$s or %5$supdate now%6$s.', 'korea-for-woocommerce' ),
					esc_html( $version_info->name ),
					'<a target="_blank" class="thickbox" href="' . esc_url( $changelog_link ) . '">',
					esc_html( $version_info->new_version ),
					'</a>',
					'<a href="' . esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->name, 'upgrade-plugin_' . $this->name ) ) .'">',
					'</a>'
				);
			}

			do_action( "in_plugin_update_message-{$file}", $plugin, $version_info );

			echo '</div></td></tr>';
		}
	}


	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed   $_data
	 * @param string  $_action
	 * @param object  $_args
	 * @return object $_data
	 */
	public function plugins_api_filter( $_data, $_action = '', $_args = null ) {

		if ( $_action !== 'plugin_information' || ! isset( $_args->slug ) || ( $_args->slug !== $this->slug ) ) {
			return $_data;
		}

		$to_send = [
			'slug'   => $this->slug,
			'is_ssl' => is_ssl(),
			'fields' => [
				'banners' => [],
				'reviews' => false
			]
		];

		$cache_key = 'wc_korea_api_request_' . md5( serialize( $this->slug . $this->api_data['license'] . $this->beta ) );

		// get the transient where we store the api request for this plugin for 24 hours
		$api_request_transient = $this->get_cached_version_info( $cache_key );

		// if we have no transient-saved value, run the API, set a fresh transient with the API value, and return that value too right now.
		if ( empty( $api_request_transient ) ) {

			$api_response = $this->api_request( 'plugin_information', $to_send );

			// expires in 3 hours
			$this->set_version_info_cache( $api_response, $cache_key );

			if ( false !== $api_response ) {
				$_data = $api_response;
			}

		} else {
			$_data = $api_request_transient;
		}

		// convert objects into associative arrays - we're getting an object, but core expects an array
		if ( isset( $_data->sections ) && ! is_array( $_data->sections ) ) {
			$_data->sections = $this->convert_object_to_array( $_data->sections );
		}

		if ( isset( $_data->banners ) && ! is_array( $_data->banners ) ) {
			$_data->banners = $this->convert_object_to_array( $_data->banners );
		}

		if ( isset( $_data->icons ) && ! is_array( $_data->icons ) ) {
			$_data->icons = $this->convert_object_to_array( $_data->icons );
		}

		return $_data;
	}


	/**
	 * Disable SSL verification in order to prevent download update failures.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $args
	 * @param string  $url
	 * @return string[] $array
	 */
	public function http_request_args( $args, $url ) {

		$verify_ssl = $this->verify_ssl();

		if ( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = $verify_ssl;
		}

		return $args;
	}


	/**
	 * Calls the API and, if successful, returns the object delivered by the API.
	 *
	 * @since 1.0.0
	 *
	 * @param string $_action The requested action.
	 * @param array $_data Parameters for the API action.
	 * @return false|object
	 */
	private function api_request( $_action, $_data ) {

		$data = array_merge( $this->api_data, $_data );

		if ( $data['slug'] !== $this->slug || ! $this->api_status_check() ) {
			return false;
		}

		if ( $this->api_url == trailingslashit( home_url() ) ) {
			return false; // don't allow a plugin to ping itself
		}

		$api_params = [
			'edd_action' => 'get_version',
			'license'    => ! empty( $data['license'] ) ? $data['license'] : '',
			'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
			'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
			'version'    => isset( $data['version'] ) ? $data['version'] : false,
			'slug'       => $data['slug'],
			'author'     => $data['author'],
			'url'        => home_url(),
			'beta'       => ! empty( $data['beta'] ),
		];

		$request = wp_remote_post( $this->api_url, [
			'timeout'   => 25,
			'sslverify' => $this->verify_ssl(),
			'body'      => $api_params,
		] );

		if ( ! is_wp_error( $request ) ) {
			$request = json_decode( wp_remote_retrieve_body( $request ) );
		}

		if ( $request && isset( $request->sections ) ) {
			$request->sections = maybe_unserialize( $request->sections );
		} else {
			$request = false;
		}

		if ( $request && isset( $request->banners ) ) {
			$request->banners = maybe_unserialize( $request->banners );
		}

		if ( $request && isset( $request->icons ) ) {
			$request->icons = maybe_unserialize( $request->icons );
		}

		/**
		 * Allow plugins to load their own custom icons for the updater.
		 *
		 * @since 1.1.0
		 *
		 * @param array the custom icons; should include $icon['svg'] or $icon['1x'] and $icon['2x']
		 * @param object $value the version info
		 */
		$custom_icons = apply_filters( "wc_korea_plugin_updater_{$this->name}_icon", [
			'1x' => $this->get_plugin_url() . '/lib/skyverge/updater/assets/img/plugin-icon-128.png',
			'2x' => $this->get_plugin_url() . '/lib/skyverge/updater/assets/img/plugin-icon-256.png',
		], $request );

		if ( ! empty( $custom_icons ) ) {
			$request->icons = (array) $custom_icons;
		}

		if ( ! empty( $request->sections ) ) {

			foreach ( $request->sections as $key => $section ) {
				$request->$key = (array) $section;
			}
		}

		return $request;
	}


	/**
	 * Performs a status check on the API url.
	 *
	 * @since 1.1.0
	 *
	 * @return bool true if the url is available
	 */
	protected function api_status_check() {

		// do a quick status check on this domain if we haven't already checked it.
		$store_hash = md5( $this->api_url );

		if ( ! is_array( $this->api_url_available ) || ! isset( $this->api_url_available[ $store_hash ] ) ) {

			$test_url_parts = parse_url( $this->api_url );

			$scheme = ! empty( $test_url_parts['scheme'] ) ? $test_url_parts['scheme']     : 'http';
			$host   = ! empty( $test_url_parts['host'] )   ? $test_url_parts['host']       : '';
			$port   = ! empty( $test_url_parts['port'] )   ? ':' . $test_url_parts['port'] : '';

			if ( empty( $host ) ) {

				$this->api_url_available[ $store_hash ] = false;

			} else {

				$test_url = "{$scheme}://{$host}{$port}";
				$response = wp_remote_get( $test_url, [
					'timeout'   => 25,
					'sslverify' => $this->verify_ssl(),
				] );

				$this->api_url_available[ $store_hash ] = ! is_wp_error( $response );
			}
		}

		return $this->api_url_available[ $store_hash ];
	}


	/**
	 * Shows the plugin changelog.
	 *
	 * @since 1.0.0
	 */
	public function show_changelog() {
		global $wc_korea_plugin_data;

		if ( empty( $_REQUEST['edd_sl_action'] ) || 'view_plugin_changelog' != $_REQUEST['edd_sl_action'] ) {
			return;
		}

		if ( empty( $_REQUEST['plugin'] ) || empty( $_REQUEST['slug'] ) ) {
			return;
		}

		if ( ! current_user_can( 'update_plugins' ) ) {
			wp_die( __( 'You do not have permission to install plugin updates', 'korea-for-woocommerce' ), __( 'Error', 'korea-for-woocommerce' ), [ 'response' => 403 ] );
		}

		$data         = $wc_korea_plugin_data[ $_REQUEST['slug'] ];
		$beta         = ! empty( $data['beta'] ) ? true : false;
		$cache_key    = md5( 'wc_korea_plugin_' . sanitize_key( $_REQUEST['plugin'] ) . '_' . $beta . '_version_info' );
		$version_info = $this->get_cached_version_info( $cache_key );

		if ( false === $version_info ) {

			$api_params = [
				'edd_action' => 'get_version',
				'item_name'  => isset( $data['item_name'] ) ? $data['item_name'] : false,
				'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
				'slug'       => $_REQUEST['slug'],
				'author'     => $data['author'],
				'url'        => home_url(),
				'beta'       => ! empty( $data['beta'] )
			];

			$request = wp_remote_post( $this->api_url, [
				'timeout'   => 25,
				'sslverify' => $this->verify_ssl(),
				'body'      => $api_params,
			] );

			if ( ! is_wp_error( $request ) ) {
				$version_info = json_decode( wp_remote_retrieve_body( $request ) );
			}

			if ( ! empty( $version_info ) && isset( $version_info->sections ) ) {
				$version_info->sections = maybe_unserialize( $version_info->sections );
			} else {
				$version_info = false;
			}

			if ( ! empty( $version_info ) ) {

				foreach( $version_info->sections as $key => $section ) {
					$version_info->$key = (array) $section;
				}
			}

			$this->set_version_info_cache( $version_info, $cache_key );
		}

		if ( ! empty( $version_info ) && isset( $version_info->sections['changelog'] ) ) {
			echo '<div style="background:#fff;padding:10px;">' . $version_info->sections['changelog'] . '</div>';
		}

		exit;
	}


	/**
	 * Convert some objects to arrays when injecting data into the update API.
	 *
	 * Some data like sections, banners, and icons are expected to be an associative array, however due to the JSON
	 * decoding, they are objects. This method allows us to pass in the object and return an associative array.
	 *
	 * @since 1.1.0
	 *
	 * @param \stdClass $data
	 * @return array
	 */
	private function convert_object_to_array( $data ) {

		$new_data = [];

		foreach ( $data as $key => $value ) {
			$new_data[ $key ] = $value;
		}

		return $new_data;
	}


	/**
	 * Gets cached plugin version information.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cache_key
	 * @return array|bool|mixed|object
	 */
	public function get_cached_version_info( $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->cache_key;
		}

		$cache = get_option( $cache_key );

		if ( empty( $cache['timeout'] ) || current_time( 'timestamp' ) > $cache['timeout'] ) {
			return false; // Cache is expired
		}


		return json_decode( $cache['value'] );
	}


	/**
	 * Sets up a cache for plugin version info.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value
	 * @param string $cache_key
	 */
	public function set_version_info_cache( $value = '', $cache_key = '' ) {

		if ( empty( $cache_key ) ) {
			$cache_key = $this->cache_key;
		}

		/**
		 * Allow plugins to load their own custom icons for the updater.
		 *
		 * @since 1.1.0
		 *
		 * @param array the custom icons; should include $icon['svg'] or $icon['1x'] and $icon['2x']
		 * @param object $value the version info
		 */
		$custom_icons = apply_filters( "wc_korea_plugin_updater_{$this->name}_icon", [
			'1x' => $this->get_plugin_url() . '/lib/skyverge/updater/assets/img/plugin-icon-128.png',
			'2x' => $this->get_plugin_url() . '/lib/skyverge/updater/assets/img/plugin-icon-256.png',
		], $value );

		if ( ! empty( $custom_icons ) ) {
			$value->icons = (array) $custom_icons;
		}

		$data = [
			'timeout' => strtotime( '+3 hours', current_time( 'timestamp' ) ),
			'value'   => json_encode( $value )
		];

		update_option( $cache_key, $data, 'no' );
	}


	/**
	 * Returns if the SSL of the store should be verified.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function verify_ssl() {
		return (bool) apply_filters( 'wc_korea_sl_api_request_verify_ssl', true, $this );
	}


	/**
	 * Helper to get the plugin URL.
	 *
	 * @since 1.1.0
	 *
	 * @return string the plugin URL
	 */
	public function get_plugin_url() {
		return untrailingslashit( plugins_url( '/', $this->plugin_file ) );
	}


}