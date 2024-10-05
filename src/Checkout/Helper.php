<?php
/**
 * WooCommerce Korea - Helper
 *
 * @package WC_Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Checkout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper class.
 */
class Helper {

	/**
	 * Check if a Korean phone number contains a valid area code and the correct number of digits.
	 *
	 * @source https://github.com/rhymix/rhymix/blob/master/common/framework/korea.php#L72
	 *
	 * @param string $tel Phone number.
	 * @return bool
	 */
	public static function is_valid_phone(string $tel): bool {
		$tel = str_replace('-', '', self::format_phone($tel));

		$pattern = '/^(1[0-9]{7}|02[2-9][0-9]{6,7}|0[13-8][0-9][2-9][0-9]{6,7}|0(?:303|505)[2-9][0-9]{6,7}|01[016789][2-9][0-9]{6,7})$/';

		return preg_match($pattern, $tel) === 1;
	}

	/**
	 * Format a given phone number.
	 *
	 * @source https://github.com/rhymix/rhymix/blob/master/common/framework/korea.php#L16
	 *
	 * @param  string $tel Phone number.
	 * @return string
	 */
	public static function format_phone( $tel ) {
		// Remove all non-numbers.
		$tel = preg_replace( '/[^0-9]/', '', $tel );

		// Remove the country code.
		if ( strncmp( $tel, '82', 2 ) === 0 ) {
			$tel = substr( $tel, 2 );
			if ( strncmp( $tel, '0', 1 ) !== 0 ) {
				$tel = '0' . $tel;
			}
		}

		// Apply different format based on the number of digits.
		switch ( strlen( $tel ) ) {
			case 8:
				return substr_replace( $tel, '-', 4, 0 );
			case 9:
				return substr_replace( substr_replace( $tel, '-', 2, 0 ), '-', 6, 0 );
			case 10:
				if ( substr( $tel, 0, 2 ) === '02' ) {
					return substr_replace( substr_replace( $tel, '-', 2, 0 ), '-', 7, 0 );
				} else {
					return substr_replace( substr_replace( $tel, '-', 3, 0 ), '-', 7, 0 );
				}
			default:
				if ( substr( $tel, 0, 4 ) === '0303' || substr( $tel, 0, 3 ) === '050' ) {
					if ( strlen( $tel ) === 12 ) {
						return substr_replace( substr_replace( $tel, '-', 4, 0 ), '-', 9, 0 );
					} else {
						return substr_replace( substr_replace( $tel, '-', 4, 0 ), '-', 8, 0 );
					}
				} else {
					return substr_replace( substr_replace( $tel, '-', 3, 0 ), '-', 8, 0 );
				}
		}
	}

}
