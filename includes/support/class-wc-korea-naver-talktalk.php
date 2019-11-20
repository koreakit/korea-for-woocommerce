<?php
/**
 * WooCommerce Korea - Naver TalkTalk
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Naver_TalkTalk {

	public function __construct() {
		$this->settings = get_option('woocommerce_korea_settings');

		$this->enabled           = 'yes' === $this->settings['navertalktalk_yn'];
		$this->pc_productkey     = $this->settings['navertalktalk_pc_productkey'];
		$this->mobile_productkey = $this->settings['navertalktalk_mobile_productkey'];

		if ( ! $this->enabled ) {
			return;
		}

		// Load JS Library
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		
		// Hook Product Page
		if ( ! empty($this->settings['navertalktalk_productpage']) ) {
			add_action('woocommerce_'. $this->settings['navertalktalk_productpage'], array($this, 'display_button'));
		}

		// Hook Thank You Page
		if ( ! empty($this->settings['navertalktalk_thankyoupage']) ) {
			add_action('woocommerce_'. $this->settings['navertalktalk_thankyoupage'], array($this, 'display_button'));
		}

		// Shortcodes
		add_shortcode('navertalktalk', array($this, 'shortcode_button'));
	}

	/**
     * Load payment scripts.
     */
	public function frontend_scripts() {
		wp_enqueue_script( 'wc-korea-naver-talktalk', 'https://partner.talk.naver.com/banners/script', [], WC_KOREA_VERSION );
	}

	public function display_button() {
		global $post;

		$id  = wp_is_mobile() ? $this->mobile_product_key : $this->pc_product_key;
		$ref = urlencode(get_permalink($post->ID));
		
		ob_start(); ?>
		<div class="talk_banner_div" data-id="<?php echo esc_attr($this->settings['navertalktalk_id']); ?>" data-ref="<?php echo urlencode(get_permalink($post->ID)); ?>"></div>
		<?php echo ob_get_clean();
	}

	public function shortcode_button() {
		$id  = wp_is_mobile() ? $this->mobile_productkey : $this->pc_productkey;
		$ref = urlencode(get_permalink($post->ID));
		
		ob_start(); ?>
		<div class="talk_banner_div" data-id="<?php echo esc_attr($id); ?>" data-ref="<?php echo urlencode(get_permalink($post->ID)); ?>"></div>
		<?php echo ob_get_clean();
	}

}

new WC_Korea_Naver_TalkTalk();