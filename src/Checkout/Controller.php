<?php
/**
 * WooCommerce Korea - Checkout
 */

namespace Greys\WooCommerce\Korea\Checkout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Controller class.
 */
class Controller {

	/**
	 * Initialize
	 */
	public function __construct() {
		if ( true === apply_filters( 'woocommerce_korea_checkout_phone_validation', true ) ) {
			add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_phone' ), 10, 2 );
		}

		if ( true === apply_filters( 'woocommerce_korea_checkout_phone_format', true ) ) {
			add_action( 'woocommerce_checkout_create_order', array( $this, 'format_phone' ), 10, 2 );
			add_action( 'woocommerce_checkout_update_customer', array( $this, 'format_phone' ), 10, 2 );
		}

		add_filter( 'woocommerce_get_country_locale', array( $this, 'set_country_locale' ) );
	}

	/**
	 * Validate korean phone number
	 *
	 * @param array $data Format data.
	 * @param object $errors Error object.
	 */
	public static function validate_phone( $data, $errors ) {
		if ( 'required' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) {
			return;
		}

		if ( 'KR' !== $data['billing_country'] ) {
			return;
		}

		if ( ! Helper::is_valid_phone( $data['billing_phone'] ) ) {
			$errors->add( 'validation', __( '<strong>Billing Phone</strong> is not a valid phone number.' ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		}
	}

	/**
	 * Format korean phone number
	 *
	 * @param object $order Order object.
	 * @param array  $data  Form data.
	 */
	public static function format_phone( $order, $data ) {
		if ( 'required' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) {
			return;
		}

		if ( 'KR' !== $data['billing_country'] ) {
			return;
		}

		// Format phone for order.
		$phone = Helper::format_phone( $data['billing_phone'] );
		$order->set_billing_phone( $phone );
	}

	/**
	 * Change priority & requirement for korean checkout fields
	 *
	 * @param array $fields Checkout fields.
	 */
	public function set_country_locale( $fields ) {
		$fields['KR']['postcode']['priority'] = 30;
		$fields['KR']['postcode']['required'] = true;
		$fields['KR']['country']['priority']  = 30;

		return $fields;
	}

}
