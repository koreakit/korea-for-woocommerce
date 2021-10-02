<?php
/**
 * WooCommerce Korea - Address
 */

namespace Greys\WooCommerce\Korea\Checkout;

/**
 * Address class.
 */
class Address {

	/**
	 * Initialize
	 */
	public static function init() {
		add_filter( 'woocommerce_get_country_locale', array( __CLASS__, 'set_country_locale' ) );
	}

	/**
	 * Change priority & requirement for korean checkout fields
	 *
	 * @param array $fields Checkout fields.
	 */
	public static function set_country_locale( $fields ) {
		$fields['KR']['postcode']['priority'] = 40;
		$fields['KR']['postcode']['required'] = true;
		$fields['KR']['country']['priority']  = 30;

		return $fields;
	}

}
