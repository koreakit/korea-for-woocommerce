<?php
/**
 * WooCommerce Korea - Kakao Channel
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Kakao_Channel class.
 */
class WC_Korea_Kakao_Channel {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$settings = get_option( 'woocommerce_korea_settings' );

		$this->enabled = isset( $settings['kakaochannel_yn'] ) && ! empty( $settings['kakaochannel_yn'] ) ? 'yes' === $settings['kakaochannel_yn'] : false;
		if ( ! $this->enabled ) {
			return;
		}

		$this->id       = isset( $settings['kakaochannel_id'] ) && ! empty( $settings['kakaochannel_id'] ) ? $settings['kakaochannel_id'] : null;
		$this->appkey   = isset( $settings['kakaochannel_appkey'] ) && ! empty( $settings['kakaochannel_appkey'] ) ? $settings['kakaochannel_appkey'] : null;
		$this->btntype  = isset( $settings['kakaochannel_btntype'] ) && ! empty( $settings['kakaochannel_btntype'] ) ? $settings['kakaochannel_btntype'] : 'add';
		$this->btnsize  = isset( $settings['kakaochannel_btnsize'] ) && ! empty( $settings['kakaochannel_btnsize'] ) ? $settings['kakaochannel_btnsize'] : 'small';
		$this->btncolor = isset( $settings['kakaochannel_btncolor'] ) && ! empty( $settings['kakaochannel_btncolor'] ) ? $settings['kakaochannel_btncolor'] : 'yellow';

		// Enqueue styles.
		add_action( 'wp_head', array( $this, 'add_styles' ) );

		// Shortcodes.
		add_shortcode( 'kakaochannel', array( $this, 'shortcode_output' ) );

		// Add Kakao Channel.
		add_action( 'wp_footer', array( $this, 'output' ) );
	}

	/**
	 * Load Naver TalkTalk styles.
	 */
	public function add_styles() {
		ob_start();
		?>
		<style type="text/css">
			#kakaochannel-button {
				position: fixed;
				z-index : 9999;
				bottom  : 20px;
				right   : 30px;
			}
		</style>
		<?php
		ob_end_flush();
	}

	/**
	 * KakaoChannel Shortcode
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function shortcode_output( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'    => $this->id,
				'size'  => $this->btnsize,
				'type'  => $this->btntype,
				'color' => $this->btncolor,
			),
			$atts,
			'kakaochannel'
		);

		switch ( $atts['type'] ) {
			case 'consult':
			case 'question':
				$fn = 'createChatButton';
				break;

			case 'add':
				$fn = 'createAddChannelButton';
				break;
		}

		ob_start();
		?>
		<div id="kakaochannel-button" data-channel-public-id="<?php echo esc_attr( $atts['id'] ); ?>" data-title="<?php echo esc_attr( $atts['type'] ); ?>" data-size="<?php echo esc_attr( $atts['size'] ); ?>" data-color="<?php echo esc_attr( $atts['color'] ); ?>" data-shape="<?php echo wp_is_mobile() ? 'mobile' : 'pc'; ?>" data-support-multiple-densities="true"></div>
		<script type='text/javascript'>
			//<![CDATA[
				window.kakaoAsyncInit = function () {
					Kakao.init('<?php echo esc_js( $this->appkey ); ?>');
					Kakao.Channel.<?php echo esc_js( $fn ); ?>({
						container: '#kakaochannel-button'
					});
				};

				(function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//developers.kakao.com/sdk/js/kakao.plusfriend.min.js";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'kakao-js-sdk'));
			//]]>
		</script>
		<?php
		ob_end_flush();
	}

	/**
	 * Add KakaoChannel snippet
	 */
	public function output() {
		switch ( $this->btntype ) {
			case 'consult':
			case 'question':
				$fn = 'createChatButton';
				break;

			case 'add':
				$fn = 'createAddChannelButton';
				break;
		}

		ob_start();
		?>
		<div id="kakaochannel-button" data-channel-public-id="<?php echo esc_attr( $this->id ); ?>" data-title="<?php echo esc_attr( $this->btntype ); ?>" data-size="<?php echo esc_attr( $this->btnsize ); ?>" data-color="<?php echo esc_attr( $this->btncolor ); ?>" data-shape="<?php echo wp_is_mobile() ? 'mobile' : 'pc'; ?>" data-support-multiple-densities="true"></div>
		<script type='text/javascript'>
			//<![CDATA[
				window.kakaoAsyncInit = function () {
					Kakao.init('<?php echo esc_js( $this->appkey ); ?>');
					Kakao.Channel.<?php echo esc_js( $fn ); ?>({
						container: '#kakaochannel-button'
					});
				};

				(function (d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//developers.kakao.com/sdk/js/kakao.plusfriend.min.js";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'kakao-js-sdk'));
			//]]>
		</script>
		<?php
		ob_end_flush();
	}

}

return new WC_Korea_Kakao_Channel();
