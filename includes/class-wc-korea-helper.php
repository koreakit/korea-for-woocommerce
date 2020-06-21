<?php
/**
 * WooCommerce Korea - Helper
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Helper class.
 */
class WC_Korea_Helper {

	/**
	 * Get test account
	 *
	 * @param string $id Payment Gateway ID.
	 *
	 * @return array
	 */
	public static function get_testaccount( $id ) {
		if ( empty( $id ) ) {
			return null;
		}

		$data     = array();
		$settings = get_option( 'woocommerce_' . $id . '_settings' );

		if ( ! isset( $settings['testaccount'] ) ) {
			return null;
		}

		$testaccount = get_user_by( 'id', $settings['testaccount'] );

		if ( ! $testaccount ) {
			return null;
		}

		$data[ $testaccount->ID ] = $testaccount->user_email;

		return $data;
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
				return substr( $tel, 0, 4 ) . '-' . substr( $tel, 4 );
			case 9:
				return substr( $tel, 0, 2 ) . '-' . substr( $tel, 2, 3 ) . '-' . substr( $tel, 5 );
			case 10:
				if ( substr( $tel, 0, 2 ) === '02' ) {
					return substr( $tel, 0, 2 ) . '-' . substr( $tel, 2, 4 ) . '-' . substr( $tel, 6 );
				} else {
					return substr( $tel, 0, 3 ) . '-' . substr( $tel, 3, 3 ) . '-' . substr( $tel, 6 );
				}
			default:
				if ( substr( $tel, 0, 4 ) === '0303' || substr( $tel, 0, 3 ) === '050' ) {
					if ( strlen( $tel ) === 12 ) {
						return substr( $tel, 0, 4 ) . '-' . substr( $tel, 4, 4 ) . '-' . substr( $tel, 8 );
					} else {
						return substr( $tel, 0, 4 ) . '-' . substr( $tel, 4, 3 ) . '-' . substr( $tel, 7 );
					}
				} else {
					return substr( $tel, 0, 3 ) . '-' . substr( $tel, 3, 4 ) . '-' . substr( $tel, 7 );
				}
		}
	}

	/**
	 * Check if a Korean phone number contains a valid area code and the correct number of digits.
	 *
	 * @source https://github.com/rhymix/rhymix/blob/master/common/framework/korea.php#L72
	 *
	 * @param string $tel Phone number.
	 * @return bool
	 */
	public static function is_valid_phone( $tel ) {
		$tel = str_replace( '-', '', self::format_phone( $tel ) );

		if ( preg_match( '/^1[0-9]{7}$/', $tel ) ) {
			return true;
		}

		if ( preg_match( '/^02[2-9][0-9]{6,7}$/', $tel ) ) {
			return true;
		}

		if ( preg_match( '/^0[13-8][0-9][2-9][0-9]{6,7}$/', $tel ) ) {
			return true;
		}

		if ( preg_match( '/^0(?:303|505)[2-9][0-9]{6,7}$/', $tel ) ) {
			return true;
		}

		if ( preg_match( '/^01[016789][2-9][0-9]{6,7}$/', $tel ) ) {
			return true;
		}

		return false;
	}

}
