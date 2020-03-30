<?php
/**
 * WooCommerce Korea - Kakao Channel
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Kakao_Channel {

	public function __construct() {
		$this->settings = get_option('woocommerce_korea_settings');

		if ( ! isset($this->settings['kakaochannel_yn']) || 'yes' !== $this->settings['kakaochannel_yn'] ) {
			return;
		}

		// Shortcodes
		add_shortcode('kakaochannel', array($this, 'shortcode_addchannel_button'));

		// Add Kakao Channel snippet
		add_action( 'wp_footer', array($this, 'add_kakaochannel_snippet') );
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_script('kakao-sdk', '//developers.kakao.com/sdk/js/kakao.min.js', false, WC_KOREA_VERSION);	
	}

	public function add_kakaochannel_snippet() {
		switch ($this->settings['kakaochannel_btntype']) {
			case 'consult':
			case 'question':
				$fn = 'createChatButton';
				break;

			case 'add':
				$fn = 'createAddChannelButton';
				break;
		}

		ob_start(); ?>
		<style type="text/css">
			#kakaochannel-button {
				position: fixed;
				z-index : 9999;
				bottom  : 20px;
				right   : 30px;
			}
		</style>
		<div id="kakaochannel-button" data-channel-public-id="<?php echo esc_attr($this->settings['kakaochannel_id']); ?>" data-title="<?php echo esc_attr($this->settings['kakaochannel_btntype']); ?>" data-size="<?php echo esc_attr($this->settings['kakaochannel_btnsize']); ?>" data-color="<?php echo esc_attr($this->settings['kakaochannel_btncolor']); ?>" data-shape="<?php echo wp_is_mobile() ? 'mobile' : 'pc'; ?>" data-support-multiple-densities="true"></div>
		<script type='text/javascript'>
			//<![CDATA[
				window.kakaoAsyncInit = function () {
					Kakao.init('<?php echo $this->settings['kakaochannel_appkey']; ?>');
					Kakao.Channel.<?php echo $fn; ?>({
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
		<?php echo ob_get_clean();
	}

	public function shortcode_button( $args ) {
		extract(shortcode_atts([
			'id'     => $this->settings['kakaochannel_id'],
			'size'   => $this->settings['kakaochannel_btnsize'] ?: 'small',
			'type'   => $this->settings['kakaochannel_btntype'] ?: 'add',
			'color'  => $this->settings['kakaochannel_btncolor'] ?: 'yellow'
		], $args ));

		switch ($type) {
			case 'consult':
			case 'question':
				$fn = 'createChatButton';
				break;

			case 'add':
				$fn = 'createAddChannelButton';
				break;
		}
		
		ob_start(); ?>
		<style type="text/css">
			#kakaochannel-button {
				position: fixed;
				z-index : 9999;
				bottom  : 20px;
				right   : 30px;
			}
		</style>
		<div id="kakaochannel-button" data-channel-public-id="<?php echo esc_attr($id); ?>" data-title="<?php echo esc_attr($type); ?>" data-size="<?php echo esc_attr($size); ?>" data-color="<?php echo esc_attr($color); ?>" data-shape="<?php echo wp_is_mobile() ? 'mobile' : 'pc'; ?>" data-support-multiple-densities="true"></div>
		<script type='text/javascript'>
			//<![CDATA[
				window.kakaoAsyncInit = function () {
					Kakao.init('<?php echo $this->settings['kakaochannel_appkey']; ?>');
					Kakao.Channel.<?php echo $fn; ?>({
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
		<?php echo ob_get_clean();
	}

}

new WC_Korea_Kakao_Channel();