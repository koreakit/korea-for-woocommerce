<?php
/**
 * WooCommerce Korea - Postcode Finder
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Postcode class.
 */
class WC_Korea_Postcode {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$settings      = get_option( 'woocommerce_korea_settings' );
		$this->enabled = isset( $settings['postcode_yn'] ) && ! empty( $settings['postcode_yn'] ) ? 'yes' === $settings['postcode_yn'] : false;

		if ( ! $this->enabled ) {
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

		add_filter( 'woocommerce_form_field_text', array( $this, 'postcode_field' ), 10, 5 );
		add_filter( 'woocommerce_get_country_locale', array( $this, 'wc_get_country_locale' ) );
	}

	/**
	 * Change priority & requirement for korean checkout fields
	 *
	 * @param array $fields Checkout fields.
	 */
	public function wc_get_country_locale( $fields ) {
		$fields['KR']['postcode']['priority'] = 40;
		$fields['KR']['postcode']['required'] = true;
		$fields['KR']['country']['priority']  = 30;

		return $fields;
	}

	/**
     * Postcode form field.
     *
     * @param string $field Field.
     * @param string $key Key.
     * @param mixed  $args Arguments.
     * @param string $value (default: null).
     * @return string
     */
	public function postcode_field( $field, $key, $args, $value ) {
		if ( 'billing_postcode' !== $key ) {
			return $field;
		}

		wp_enqueue_script( 'wc-korea-daum-postcode', '//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js', array(), null, true );
		wp_enqueue_script( 'wc-korea-postcode', plugins_url( 'assets/js/wc-korea-postcode.js', WC_KOREA_MAIN_FILE ), array( 'jquery' ), WC_KOREA_VERSION, true );
		wp_localize_script(
			'wc-korea-postcode',
			'_postcode',
			array(
				'displaymode' => $this->displaymode,
				'theme' => array(
					'bgColor'           => $this->bgcolor,
					'searchBgColor'     => $this->searchbgcolor,
					'contentBgColor'    => $this->contentbgcolor,
					'pageBgColor'       => $this->pagebgcolor,
					'textColor'         => $this->textcolor,
					'queryTextColor'    => $this->querytxtcolor,
					'postcodeTextColor' => $this->postalcodetxtcolor,
					'emphTextColor'     => $this->emphtxtcolor,
					'outlineColor'      => $this->outlinecolor,
				),
			)
		);

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
		</style>
		<?php return $field;
	}

}

return new WC_Korea_Postcode();
