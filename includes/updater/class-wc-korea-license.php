<?php
/**
 * WooCommerce Korea - License
 *
 * @package WC_Korea
 * @author  @jgreys
 */
defined( 'ABSPATH' ) or exit;

/**
 * Provides a general license settings page for plugins to add license key inputs.
 *
 * @since 1.0.0
 */
class WC_Korea_License {

	/**
	 * @var string $api_url plugin update URL
	 */
	protected $api_url;

	/**
	 * @var string $file plugin file
	 */
	protected $file;

	/**
	 * @var string $plugin_url plugin url
	 */
	protected $plugin_url;

	/**
	 * @var int $id plugin post ID on our site
	 */
	protected $id;

	/**
	 * @var string $version plugin version
	 */
	protected $version;

	/**
	 * @var string $author plugin author
	 */
	protected $author;

	/**
	 * @var string $prefix plugin prefix
	 */
	private $prefix;

	/**
	 * @var string $license license key
	 */
	private $license;

	/**
	 * @var string $updater_url the URL for plugin updates
	 */
	protected $updater_url = 'https://greys.co/';

	/**
	 * @var $settings the settings instance
	 */
	protected $settings;


	public function __construct( $_id, $_prefix, $_file, $_path, $_plugin_url, $_version, $_author = 'GREYS' ) {

		if ( is_numeric( $_id ) ) {
			$this->id = absint( $_id );
		}

		if ( ! function_exists('get_plugin_data') ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$this->api_url    = 'https://greys.co/';
		$this->prefix     = $_prefix;
		$this->file       = $_file;
		$this->path       = $_path;
		$this->plugin_url = $_plugin_url;
		$this->version    = $_version;
		$this->author     = $_author;
		$this->data       = get_plugin_data( $this->file );
		$this->license    = trim( get_option( "{$this->prefix}_license_key", '' ) );

		$this->includes();
		$this->add_hooks();
	}


	/**
	 * Includes required files.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		if ( ! class_exists( 'WC_Korea_Updater' ) ) {
			require_once( WC_KOREA_ABSPATH . '/includes/updater/class-wc-korea-updater.php');
		}
	}


	/**
	 * Setup plugin hooks.
	 *
	 * @since 1.0.0
	 */
	private function add_hooks() {
		// load plugin updater
		add_action( 'admin_init', array( $this, 'auto_updater' ), 0 );

		// add scheduled event for license update check
		add_filter( 'cron_schedules', array( $this, 'add_cron_schedule' ) );
		add_action( 'wp', array( $this, 'schedule_events' ) );

		// register settings
		add_filter( 'wc_korea_plugin_license_settings', array( $this, 'add_settings' ), 1 );

		// add styles for settings
		add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );

		// activate license key on settings save
		add_action( 'admin_init', array( $this, 'activate_license' ) );

		// deactivate license key
		add_action( 'admin_init', array( $this, 'deactivate_license' ) );

		// check that license is valid once per week
		add_action( 'wc_korea_weekly_scheduled_events', array( $this, 'weekly_license_check' ) );

		// Display notices to admins
		add_action( 'admin_notices', array( $this, 'notices' ) );

		add_action( 'in_plugin_update_message-' . plugin_basename( $this->file ), array( $this, 'plugin_row_license_missing' ), 10, 2 );
	}


	/**
	 * Load the auto updater.
	 *
	 * @since 1.0.0
	 */
	public function auto_updater() {
		$data = [
			'version' => $this->version,
			'license' => $this->license,
			'item_id' => $this->id,
			'author'  => $this->author,
		];

		// Setup the updater
		$plugin_updater = new WC_Korea_Updater( $this->file, $data );
	}


	/**
	 * Registers new cron schedule.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $schedules existing schedules
	 * @return string[] update schedules
	 */
	public function add_cron_schedule( $schedules = [] ) {

		// adds a weekly schedule to available cron schedules
		$schedules['weekly'] = [
			'interval' => 604800,
			'display'  => __( 'Once per week', 'korea-for-woocommerce' ),
		];

		return $schedules;
	}


	/**
	 * Schedule weekly events.
	 *
	 * @since 1.0.0
	 */
	public function schedule_events() {
		if ( ! wp_next_scheduled( 'wc_korea_weekly_scheduled_events' ) ) {
			wp_schedule_event( current_time( 'timestamp', true ), 'weekly', 'wc_korea_weekly_scheduled_events' );
		}
	}


	/**
	 * Get license settings page URL.
	 *
	 * @since 1.0.0
	 *
	 * @param array
	 * @return array
	 */
	public function add_settings( $settings ) {
		return array_merge($settings, [
			[
				'id'      => "{$this->prefix}_license_key",
				'name'    => sprintf( __( '%1$s', 'korea-for-woocommerce' ), $this->data['Name'] ),
				'desc'    => '',
				'type'    => 'license_key',
				'options' => array( 'is_valid_license_option' => "{$this->prefix}_license_active" ),
				'size'    => 'regular',
			]
		]);
	}


	/**
	 * Adds updater page stylesheet.
	 *
	 * @since 1.0.0
	 */
	public function add_styles() {
		if ( isset( $_GET['section'] ) && 'wc-korea' === $_GET['section'] ) {
			wp_enqueue_style( 'wc-korea-license-settings', WC_KOREA_PLUGIN_URL . '/assets/css/admin.css', [], $this->version );
		}
	}


	/**
	 * Activate the license key
	 *
	 * @since 1.0.0
	 */
	public function activate_license() {

		if ( ! isset( $_REQUEST["{$this->prefix}_license_key-nonce"] ) || ! wp_verify_nonce( $_REQUEST["{$this->prefix}_license_key-nonce"], "{$this->prefix}_license_key-nonce" ) || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( empty( $_POST["{$this->prefix}_license_key"] ) ) {
			delete_option( "{$this->prefix}_license_active" );
			return;
		}

		// don't activate a key when deactivating a different key
		foreach ( $_POST as $key => $value ) {
			if ( false !== strpos( $key, 'license_key_deactivate' ) ) {
				return;
			}
		}

		$details = get_option( "{$this->prefix}_license_active" );

		if ( is_object( $details ) && 'valid' === $details->license ) {
			return;
		}

		$license = sanitize_text_field( $_POST["{$this->prefix}_license_key"] );

		if ( empty( $license ) ) {
			return;
		}

		// data to send to the API
		$api_params = [
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => $this->id,
			'url'        => home_url(),
		];

		$response = wp_remote_post(
			$this->api_url,
			[
				'timeout' => 15,
				'body'    => $api_params,
			]
		);

		// make sure there are no errors
		if ( is_wp_error( $response ) ) {
			return;
		}

		// tell WP to look for updates
		set_site_transient( 'update_plugins', null );

		// decode license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( "{$this->prefix}_license_active", $license_data );
	}


	/**
	 * Deactivate the license key
	 *
	 * @since 1.0.0
	 */
	public function deactivate_license() {

		if ( ! isset( $_POST["{$this->prefix}_license_key"] ) || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST["{$this->prefix}_license_key-nonce"], "{$this->prefix}_license_key-nonce" ) ) {
			wp_die( __( 'Nonce verification failed', 'korea-for-woocommerce' ), __( 'Error', 'korea-for-woocommerce' ), array( 'response' => 403 ) );
		}

		// run on deactivate button press
		if ( isset( $_POST["{$this->prefix}_license_key_deactivate"] ) ) {

			// data to send to the API
			$api_params = [
				'edd_action' => 'deactivate_license',
				'license'    => $this->license,
				'item_id'    => $this->id,
				'url'        => home_url(),
			];

			$response = wp_remote_post(
				$this->api_url,
				[
					'timeout' => 15,
					'body'    => $api_params,
				]
			);

			// Make sure there are no errors
			if ( is_wp_error( $response ) ) {
				return;
			}

			// Decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			delete_option( "{$this->prefix}_license_active" );
		}
	}


	/**
	 * Check for a valid license on this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return bool true if valid
	 */
	public function is_license_valid() {

		$details = get_option( "{$this->prefix}_license_active" );

		return is_object( $details ) && 'valid' === $details->license;
	}


	/**
	 * Check if license key is valid once per week
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function weekly_license_check() {

		if ( ! empty( $_POST["{$this->prefix}_license_key"] ) ) {
			return false; // don't fire when saving settings
		}

		if ( empty( $this->license ) ) {
			return false;
		}

		// data to send in our API request
		$api_params = [
			'edd_action' => 'check_license',
			'license'    => $this->license,
			'item_id'    => $this->id,
			'url'        => home_url(),
		];

		$response = wp_remote_post(
			$this->api_url,
			[
				'timeout' => 15,
				'body'    => $api_params,
			]
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( "{$this->prefix}_license_active", $license_data );
	}


	/**
	 * Add admin notices to WooCommerce pages for errors.
	 *
	 * @since 1.0.0
	 */
	public function notices() {
		global $current_screen;

		static $showed_invalid_message = false;

		$prefix  = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$screens = array( "{$prefix}_page_wc-addons", "{$prefix}_page_wc-settings", 'plugins' );
		$key     = trim( get_option( "{$this->prefix}_license_key", '' ) );

		if ( empty( $key ) || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$messages = [];
		$license  = get_option( "{$this->prefix}_license_active" );

		if ( in_array( $current_screen->id, $screens, true ) && is_object( $license ) && 'valid' !== $license->license && ! $showed_invalid_message ) {

			// only show this notice on settings / Extensions screens
			if ( ! isset( $_GET['section'] ) || 'wc-korea' !== $_GET['section'] ) {

				$messages[] = sprintf(
					/* translators: Placeholder: %1$s - <a>, %2$s - </a> */
					__( 'You have invalid or expired license keys for Korea for WooCommerce Addons. Please go to the %1$sLicenses page%2$s to correct this issue.', 'korea-for-woocommerce' ),
					'<a href="' . $this->get_license_settings_url() . '">',
					'</a>'
				);

				$showed_invalid_message = true;
			}
		}

		if ( ! empty( $messages ) ) {

			foreach( $messages as $message ) {
				echo '<div class="error"><p>' . $message . '</p></div>';
			}
		}
	}


	/**
	 * Displays message inline on plugin row that the license key is missing
	 *
	 * @since 1.0.0
	 */
	public function plugin_row_license_missing( $plugin_data, $version_info ) {

		static $showed_missing_key_message = [];

		$license = get_option( "{$this->prefix}_license_active" );

		if ( ( ! is_object( $license ) || 'valid' !== $license->license ) && empty( $showed_missing_key_message[ $this->prefix ] ) ) {

			echo '&nbsp;<strong><a href="' . esc_url( $this->get_license_settings_url() ) . '">' . __( 'Enter valid license key for automatic updates.', 'korea-for-woocommerce' ) . '</a></strong>';
			$showed_missing_key_message[ $this->prefix ] = true;
		}
	}


	/**
	 * Get license settings page URL.
	 *
	 * @since 1.0.0
	 */
	public function get_license_settings_url() {
		return admin_url( 'admin.php?page=wc-addons&section=wc-korea' );
	}


	/**
	 * Gets the license settings instance.
	 */
	public function get_license_settings_instance() {
		return $this->settings;
	}


}