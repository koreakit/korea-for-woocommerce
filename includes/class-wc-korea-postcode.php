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

		if ( ! isset($this->settings['postcode_yn']) || ! wc_string_to_bool($this->settings['postcode_yn']) ) {
			return;
		}

		add_action('wp_footer', array($this, 'wp_footer'));
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		add_filter('woocommerce_get_country_locale' , array($this, 'wc_get_country_locale'));
	}

	public function wp_footer() {
		// We do not enqueue the style if it's not required
		if ( ! is_account_page() && ! is_checkout() ) {
			return;
		}
		?>
		<style type="text/css">
			#postcode_form.overlay {
				position: fixed;
				width: 100%;
				height: 100%;
				border: 1px solid #e7e7e7;
				top: 0;
				left: 0;
				z-index: 99998;
			}

			#postcode_form.embed {
				position: relative;
				width: 100%;
				height: 395px;
				border: 1px solid #e7e7e7;
			}
		</style>
		<?php
	}

	public function wp_enqueue_scripts() {
		// We do not enqueue the script if it's not required
		if ( ! is_account_page() && ! is_checkout() ) {
			return;
		}

		wp_enqueue_script('wc-korea-daum-postcode', 'https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js', [], '');
		wp_enqueue_script('wc-korea-postcode', WC_KOREA_PLUGIN_URL . '/assets/js/wc-korea-postcode.js', [], WC_KOREA_VERSION);
		wp_localize_script('wc-korea-postcode', '_postcode', [
			'theme' => [
				'displaymode'        => $this->settings['postcode_displaymode'] ?? 'overlay',
				'bgcolor'            => $this->settings['postcode_bgcolor'] ?? '#ececec',
				'searchbgcolor'      => $this->settings['postcode_searchbgcolor'] ?? '#ffffff',
				'contentbgcolor'     => $this->settings['postcode_contentbgcolor'] ?? '#ffffff',
				'pagebgcolor'        => $this->settings['postcode_pagebgcolor'] ?? '#fafafa',
				'textcolor'          => $this->settings['postcode_textcolor'] ?? '#333333',
				'querytxtcolor'      => $this->settings['postcode_querytxtcolor'] ?? '#222222',
				'postalcodetxtcolor' => $this->settings['postcode_postalcodetxtcolor'] ?? '#fa4256',
				'emphtxtcolor'       => $this->settings['postcode_emphtxtcolor'] ?? '#008bd3',
				'outlinecolor'       => $this->settings['postcode_outlinecolor'] ?? '#e0e0e0'
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

return new WC_Korea_Postcode();