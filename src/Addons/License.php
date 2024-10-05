<?php
/**
 * WooCommerce Korea - License
 *
 * @package Greys\WooCommerce\Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Addons;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use const Greys\WooCommerce\Korea\PluginUrl as PLUGIN_URL;
use Greys\WooCommerce\Korea\Addons\Updater as UpdaterController;

/**
 * Provides a general license settings page for plugins to add license key inputs.
 */
class License {

	/**
	 * Plugin update URL
	 *
	 * @var string $api_url
	 */
	protected static $api_url;

	/**
	 * Plugin file
	 *
	 * @var string $file
	 */
	protected static $file;

	/**
	 * Path to plugin
	 *
	 * @var string $path
	 */
	protected static $path;

	/**
	 * Plugin url
	 *
	 * @var string $plugin_url
	 */
	protected static $plugin_url;

	/**
	 * Plugin post ID on our site
	 *
	 * @var int $id
	 */
	protected static $id;

	/**
	 * Plugin version
	 *
	 * @var string $version
	 */
	protected static $version;

	/**
	 * Plugin author
	 *
	 * @var string $author
	 */
	protected static $author;

	/**
	 * Plugin data
	 *
	 * @var string $data
	 */
	protected static $data;

	/**
	 * Plugin prefix
	 *
	 * @var string $prefix
	 */
	private static $prefix;

	/**
	 * License key
	 *
	 * @var string $license
	 */
	private static $license;

	/**
	 * The URL for plugin updates
	 *
	 * @var string $updater_url
	 */
	protected static $updater_url = 'https://greys.co/';

	/**
	 * The settings instance
	 *
	 * @var array $settings
	 */
	protected static $settings;

	/**
	 * Class constructor
	 *
	 * @param string $_id
	 * @param string $_prefix
	 * @param string $_file
	 * @param string $_path
	 * @param string $_plugin_url
	 * @param string $_version
	 * @param string $_author
	 */
	public function __construct( $_id, $_prefix, $_file, $_path, $_plugin_url, $_version, $_author = 'GREYS' ) {

		if ( is_numeric( $_id ) ) {
			self::$id = absint( $_id );
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		self::$api_url    = 'https://greys.co/';
		self::$prefix     = $_prefix;
		self::$file       = $_file;
		self::$path       = $_path;
		self::$plugin_url = $_plugin_url;
		self::$version    = $_version;
		self::$author     = $_author;
		self::$data       = get_plugin_data( self::$file );
		self::$license    = trim( get_option( self::$prefix . '_license_key', '' ) );

		self::hooks();
	}

	/**
	 * Setup plugin hooks.
	 */
	private static function hooks() {
		// load plugin updater
		add_action( 'admin_init', [ __CLASS__, 'auto_updater' ], 0 );

		// add scheduled event for license update check
		add_filter( 'cron_schedules', [ __CLASS__, 'add_cron_schedule' ] );
		add_action( 'wp', [ __CLASS__, 'schedule_events' ] );

		// register settings
		add_filter( 'wc_korea_plugin_license_settings', [ __CLASS__, 'add_settings' ], 1 );

		// add styles for settings
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'add_styles' ] );

		// activate license key on settings save
		add_action( 'admin_init', [ __CLASS__, 'activate_license' ] );

		// deactivate license key
		add_action( 'admin_init', [ __CLASS__, 'deactivate_license' ] );

		// check that license is valid once per week
		add_action( 'wc_korea_weekly_scheduled_events', [ __CLASS__, 'weekly_license_check' ] );

		// Display notices to admins
		add_action( 'admin_notices', [ __CLASS__, 'notices' ] );

		add_action( 'in_plugin_update_message-' . plugin_basename( self::$file ), [ __CLASS__, 'plugin_row_license_missing' ], 10, 2 );
	}

	/**
	 * Load the auto updater.
	 */
	public static function auto_updater() {
		$data = [
			'version' => self::$version,
			'license' => self::$license,
			'item_id' => self::$id,
			'author'  => self::$author,
		];

		// Setup the updater
		$plugin_updater = new UpdaterController( self::$file, $data );
	}

	/**
	 * Registers new cron schedule.
	 *
	 * @param array $schedules existing schedules.
	 * @return array update schedules
	 */
	public static function add_cron_schedule( $schedules = [] ) {
		// adds a weekly schedule to available cron schedules
		$schedules['weekly'] = [
			'interval' => 604800,
			'display'  => __( 'Once per week', 'korea-for-woocommerce' ),
		];

		return $schedules;
	}

	/**
	 * Schedule weekly events.
	 */
	public static function schedule_events() {
		if ( ! wp_next_scheduled( 'wc_korea_weekly_scheduled_events' ) ) {
			wp_schedule_event( time(), 'weekly', 'wc_korea_weekly_scheduled_events' );
		}
	}

	/**
	 * Get license settings page URL.
	 *
	 * @param array $settings Original settings.
	 * @return array
	 */
	public static function add_settings( $settings ) {
		return array_merge(
			$settings,
			[
				[
					'id'      => self::$prefix . '_license_key',
					'name'    => self::$data['Name'],
					'desc'    => '',
					'type'    => 'license_key',
					'options' => [ 'is_valid_license_option' => self::$prefix . '_license_active' ],
					'size'    => 'regular',
				],
			]
		);
	}

	/**
	 * Adds updater page stylesheet.
	 */
	public static function add_styles() {
		$section = isset( $_GET['section'] ) && ! empty( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'wc-korea' === $section ) {
			wp_enqueue_style( 'wc-korea-license-settings', PLUGIN_URL . '/assets/css/admin.css', [], self::$version );
		}
	}

	/**
	 * Activate the license key
	 */
	public static function activate_license() {
		$nonce_value = wc_get_var( $_REQUEST[ self::$prefix . '_license_key-nonce' ], '' ); // @codingStandardsIgnoreLine
		if ( ! $nonce_value ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $nonce_value, self::$prefix . '_license_key-nonce' ) ) {
			wp_die( esc_html__( 'Nonce verification failed', 'korea-for-woocommerce' ), esc_html__( 'Error', 'korea-for-woocommerce' ), [ 'response' => 403 ] );
		}

		$license = isset( $_POST[ self::$prefix . '_license_key' ] ) && ! empty( $_POST[ self::$prefix . '_license_key' ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::$prefix . '_license_key' ] ) ) : null;
		if ( empty( $license ) ) {
			delete_option( self::$prefix . '_license_active' );
			return;
		}

		// don't activate a key when deactivating a different key
		foreach ( $_POST as $key => $value ) {
			if ( false !== strpos( $key, 'license_key_deactivate' ) ) {
				return;
			}
		}

		$details = get_option( self::$prefix . '_license_active' );
		if ( is_object( $details ) && 'valid' === $details->license ) {
			return;
		}

		// data to send to the API
		$api_params = [
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => self::$id,
			'url'        => home_url(),
		];

		$response = wp_remote_post(
			self::$api_url,
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

		update_option( self::$prefix . '_license_active', $license_data );
	}

	/**
	 * Deactivate the license key
	 */
	public static function deactivate_license() {
		$nonce_value = wc_get_var( $_REQUEST[ self::$prefix . '_license_key-nonce' ], '' ); // @codingStandardsIgnoreLine
		if ( ! $nonce_value ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $nonce_value, self::$prefix . '_license_key-nonce' ) ) {
			wp_die( esc_html__( 'Nonce verification failed', 'korea-for-woocommerce' ), esc_html__( 'Error', 'korea-for-woocommerce' ), [ 'response' => 403 ] );
		}

		// run on deactivate button press
		$is_deactivating = isset( $_POST[ self::$prefix . '_license_key_deactivate' ] ) ? true : false;
		if ( $is_deactivating ) {
			// data to send to the API
			$api_params = [
				'edd_action' => 'deactivate_license',
				'license'    => self::$license,
				'item_id'    => self::$id,
				'url'        => home_url(),
			];

			$response = wp_remote_post(
				self::$api_url,
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

			delete_option( self::$prefix . '_license_active' );
		}
	}

	/**
	 * Check for a valid license on this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return bool true if valid
	 */
	public static function is_license_valid() {
		$details = get_option( self::$prefix . '_license_active' );

		return is_object( $details ) && 'valid' === $details->license;
	}

	/**
	 * Check if license key is valid once per week
	 */
	public static function weekly_license_check() {
		$license = isset( $_POST[ self::$prefix . '_license_key' ] ) && ! empty( $_POST[ self::$prefix . '_license_key' ] ) ? sanitize_text_field( wp_unslash( $_POST[ self::$prefix . '_license_key' ] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! $license ) {
			return; // don't fire when saving settings
		}

		if ( empty( self::$license ) ) {
			return;
		}

		// data to send in our API request
		$api_params = [
			'edd_action' => 'check_license',
			'license'    => self::$license,
			'item_id'    => self::$id,
			'url'        => home_url(),
		];

		$response = wp_remote_post(
			self::$api_url,
			[
				'timeout' => 15,
				'body'    => $api_params,
			]
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( self::$prefix . '_license_active', $license_data );
	}

	/**
	 * Add admin notices to WooCommerce pages for errors.
	 */
	public static function notices() {
		global $current_screen;

		static $showed_invalid_message = false;

		$prefix  = sanitize_title( __( 'WooCommerce', 'woocommerce' ) ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		$screens = [ "{$prefix}_page_wc-addons", "{$prefix}_page_wc-settings", 'plugins' ];
		$key     = trim( get_option( self::$prefix . '_license_key', '' ) );

		if ( empty( $key ) || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$messages = [];
		$license  = get_option( self::$prefix . '_license_active' );

		if ( in_array( $current_screen->id, $screens, true ) && is_object( $license ) && 'valid' !== $license->license && ! $showed_invalid_message ) {

			// only show this notice on settings / Extensions screens
			$section = isset( $_GET['section'] ) && ! empty( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( 'wc-korea' !== $section ) {

				$messages[] = sprintf(
					/* translators: Placeholder: %1$s - <a>, %2$s - </a> */
					__( 'You have invalid or expired license keys for Korea for WooCommerce Addons. Please go to the %1$sLicenses page%2$s to correct this issue.', 'korea-for-woocommerce' ),
					'<a href="' . self::get_license_settings_url() . '">',
					'</a>'
				);

				$showed_invalid_message = true;
			}
		}

		if ( ! empty( $messages ) ) {

			foreach ( $messages as $message ) {
				echo '<div class="error"><p>' . esc_html( $message ) . '</p></div>';
			}
		}
	}

	/**
	 * Displays message inline on plugin row that the license key is missing
	 *
	 * @param array $plugin_data Plugin information.
	 * @param array $response
	 */
	public static function plugin_row_license_missing( $plugin_data, $response ) {
		static $showed_missing_key_message = [];
		$license                            = get_option( self::$prefix . '_license_active' );

		if ( ( ! is_object( $license ) || 'valid' !== $license->license ) && empty( $showed_missing_key_message[ self::$prefix ] ) ) {

			echo '&nbsp;<strong><a href="' . esc_url( self::get_license_settings_url() ) . '">' . esc_html__( 'Enter valid license key for automatic updates.', 'korea-for-woocommerce' ) . '</a></strong>';
			$showed_missing_key_message[ self::$prefix ] = true;
		}
	}

	/**
	 * Get license settings page URL.
	 */
	public static function get_license_settings_url() {
		return admin_url( 'admin.php?page=wc-addons&section=wc-korea&tab=licenses' );
	}

	/**
	 * Gets the license settings instance.
	 */
	public static function get_license_settings_instance() {
		return self::$settings;
	}
}
