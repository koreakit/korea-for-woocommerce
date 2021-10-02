<?php
/**
 * WooCommerce Korea - Postcode Finder
 */

namespace Greys\WooCommerce\Korea\Checkout;

defined( 'ABSPATH' ) || exit;

use const Greys\WooCommerce\Korea\VERSION as VERSION;
use const Greys\WooCommerce\Korea\MAIN_FILE as MAIN_FILE;

/**
 * Postcode class.
 */
class Postcode {

	/**
	 * Initialize
	 */
	public static function init() {
		$settings      = get_option( 'woocommerce_korea_settings' );
		self::$enabled = isset( $settings['postcode_yn'] ) && ! empty( $settings['postcode_yn'] ) ? 'yes' === $settings['postcode_yn'] : false;

		if ( ! self::$enabled ) {
			return;
		}

		$this->displaymode        = isset( $settings['postcode_displaymode'] ) && ! empty( $settings['postcode_displaymode'] ) ? $settings['postcode_displaymode'] : 'overlay';
		$this->bgcolor            = isset( $settings['postcode_bgcolor'] ) && ! empty( $settings['postcode_bgcolor'] ) ? $settings['postcode_bgcolor'] : '#ececec';
		$this->searchbgcolor      = isset( $settings['postcode_searchbgcolor'] ) && ! empty( $settings['postcode_searchbgcolor'] ) ? $settings['postcode_searchbgcolor'] : '#ffffff';
		$this->contentbgcolor     = isset( $settings['postcode_contentbgcolor'] ) && ! empty( $settings['postcode_contentbgcolor'] ) ? $settings['postcode_contentbgcolor'] : '#ffffff';
		$this->pagebgcolor        = isset( $settings['postcode_pagebgcolor'] ) && ! empty( $settings['postcode_pagebgcolor'] ) ? $settings['postcode_pagebgcolor'] : '#fafafa';
		$this->textcolor          = isset( $settings['postcode_textcolor'] ) && ! empty( $settings['postcode_textcolor'] ) ? $settings['postcode_textcolor'] : '#333333';
		$this->querytxtcolor      = isset( $settings['postcode_querytxtcolor'] ) && ! empty( $settings['postcode_querytxtcolor'] ) ? $settings['postcode_querytxtcolor'] : '#222222';
		$this->postalcodetxtcolor = isset( $settings['postcode_postalcodetxtcolor'] ) && ! empty( $settings['postcode_postalcodetxtcolor'] ) ? $settings['postcode_postalcodetxtcolor'] : '#fa4256';
		$this->emphtxtcolor       = isset( $settings['postcode_emphtxtcolor'] ) && ! empty( $settings['postcode_emphtxtcolor'] ) ? $settings['postcode_emphtxtcolor'] : '#008bd3';
		$this->outlinecolor       = isset( $settings['postcode_outlinecolor'] ) && ! empty( $settings['postcode_outlinecolor'] ) ? $settings['postcode_outlinecolor'] : '#e0e0e0';

		add_action( 'wp_footer', array( __CLASS__, 'wp_footer' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'wp_enqueue_scripts' ) );
	}

	/**
	 * Add inline styling in the footer
	 */
	public function wp_footer() {
		if ( ! is_account_page() && ! is_checkout() ) {
			return;
		}
		?>
		<style type="text/css">
			#billing-address-autocomplete,
			#shipping-address-autocomplete {
				display: none;
			}

			#billing-address-autocomplete.embed,
			#shipping-address-autocomplete.embed {
				position: relative;
				width: 100%;
				height: 395px;
				border: 1px solid #e7e7e7;
			}

			#billing-address-autocomplete.overlay,
			#shipping-address-autocomplete.overlay {
				position: fixed;
				width: 100%;
				height: 100%;
				border: 1px solid #e7e7e7;
				top: 0;
				left: 0;
				z-index: 99998;
			}

			#billing-address-autocomplete .closed,
			#shipping-address-autocomplete .closed {
				cursor:pointer;
				position:absolute;
				right:0px;
				top:-1px;
				z-index:1
			}
		</style>
		<?php
	}

	/**
	 * Enqueue Daum Postcode + Korea for WooCommerce Postcode scripts
	 */
	public function wp_enqueue_scripts() {
		// We do not enqueue the script if it's not required.
		if ( ! is_account_page() && ! is_checkout() ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-daum-postcode', '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js', array(), null, true );
		wp_enqueue_script( 'wc-korea-postcode', plugins_url( 'assets/js/wc-korea-postcode.js', MAIN_FILE ), array( 'jquery' ), VERSION, true );
		wp_localize_script(
			'wc-korea-postcode',
			'_postcode',
			array(
				'displaymode' => $this->displaymode,
				'theme' => array(
					'bgColor'            => $this->bgcolor,
					'searchBgColor'      => $this->searchbgcolor,
					'contentBgColor'     => $this->contentbgcolor,
					'pageBgColor'        => $this->pagebgcolor,
					'textColor'          => $this->textcolor,
					'queryTextColor'     => $this->querytxtcolor,
					'postcodeTextColor'  => $this->postalcodetxtcolor,
					'emphTextColor'      => $this->emphtxtcolor,
					'outlineColor'       => $this->outlinecolor,
				),
			)
		);
	}

}
