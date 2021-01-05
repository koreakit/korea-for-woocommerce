<?php
/**
 * Korea for WooCommerce Hooks
 *
 * Action/filter hooks used for WooCommerce.
 *
 * @package WC_Korea
 * @author  @jgreys
 */

if ( true == apply_filters( 'wc_korea_checkout_phone_validation', true ) ) {

	/**
	 * Validate & format korean phone number
	 */
	add_action(
		'woocommerce_after_checkout_validation',
		function( $data, $errors ) {
			if ( 'required' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) {
				return;
			}

			if ( 'KR' !== $data['billing_country'] ) {
				return;
			}

			if ( ! WC_Korea_Helper::is_valid_phone( $data['billing_phone'] ) ) {
				$errors->add( 'validation', __( '<strong>Billing Phone</strong> is not a valid phone number.' ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			}
		},
		10,
		2
	);

}

if ( true == apply_filters( 'wc_korea_checkout_phone_format', true ) ) {

	add_action(
		'woocommerce_checkout_create_order',
		function( $order, $data ) {
			if ( 'required' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) {
				return;
			}

			if ( 'KR' !== $data['billing_country'] ) {
				return;
			}

			$phone = WC_Korea_Helper::format_phone( $data['billing_phone'] );

			// Format phone for order.
			$order->set_billing_phone( $phone );
		},
		10,
		2
	);

	add_action(
		'woocommerce_checkout_update_customer',
		function( $customer, $data ) {
			if ( 'required' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) {
				return;
			}

			if ( 'KR' !== $data['billing_country'] ) {
				return;
			}

			// Format phone for order.
			$customer->set_billing_phone( WC_Korea_Helper::format_phone( $data['billing_phone'] ) );
		},
		10,
		2
	);

}
