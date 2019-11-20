<?php
/**
 * WooCommerce Korea - Daum SEP (Search Engine Page)
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Postcode {

	public function __construct() {
		$this->settings = get_option('woocommerce_korea_settings');

		if ( ! isset($this->settings['postcode_yn']) || 'yes' !== $this->settings['postcode_yn'] ) {
			return;
		}

		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		add_filter('woocommerce_get_country_locale' , array($this, 'wc_get_country_locale'));
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_script('wc-korea-daum-postcode', 'https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js', [], '');
		wp_enqueue_script('wc-korea-postcode', WC_KOREA_PLUGIN_URL . '/assets/js/wc-korea-postcode.js', [], WC_KOREA_VERSION);
		wp_localize_script('wc-korea-postcode', '_postcode', [
			'theme' => [
				'bgcolor'            => $this->settings['postcode_bgcolor'],
				'searchbgcolor'      => $this->settings['postcode_searchbgcolor'],
				'contentbgcolor'     => $this->settings['postcode_contentbgcolor'],
				'pagebgcolor'        => $this->settings['postcode_pagebgcolor'],
				'textcolor'          => $this->settings['postcode_textcolor'],
				'querytxtcolor'      => $this->settings['postcode_querytxtcolor'],
				'postalcodetxtcolor' => $this->settings['postcode_postalcodetxtcolor'],
				'emphtxtcolor'       => $this->settings['postcode_emphtxtcolor'],
				'outlinecolor'       => $this->settings['postcode_outlinecolor']
			]
		]);
	}

	public function wc_get_country_locale( $locale ) {
		$locale['KR']['postcode']['priority'] = 40;
		$locale['KR']['postcode']['required'] = true;
		$locale['KR']['country']['priority']  = 30;
		
		return $locale;
	}

}

new WC_Korea_Postcode();