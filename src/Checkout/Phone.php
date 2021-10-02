<?php
/**
 * WooCommerce Korea - Phone
 */

namespace Greys\WooCommerce\Korea\Checkout;

defined( 'ABSPATH' ) || exit;

use Greys\WooCommerce\Korea\Checkout\Helper as Helper;

/**
 * Postcode class.
 */
class Phone {

	/**
	 * Initialize
	 */
	public static function init() {
		if ( true == apply_filters( 'woocommerce_korea_checkout_phone_validation', true ) ) {
			add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'validate_phone' ), 10, 2 );
		}

		if ( true == apply_filters( 'woocommerce_korea_checkout_phone_format', true ) ) {
			add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'format_phone' ), 10, 2 );
			add_action( 'woocommerce_checkout_update_customer', array( __CLASS__, 'format_phone' ), 10, 2 );
		}
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
	 * @param WC_Order $order Order object.
	 * @param array    $data  Form data.
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

}
