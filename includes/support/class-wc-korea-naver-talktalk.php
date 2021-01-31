<?php
/**
 * WooCommerce Korea - Naver TalkTalk
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Naver_TalkTalk class.
 */
class WC_Korea_Naver_TalkTalk {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->settings = get_option( 'woocommerce_korea_settings' );

		$this->enabled = isset( $this->settings['navertalktalk_yn'] ) && ! empty( $this->settings['navertalktalk_yn'] ) ? 'yes' === $this->settings['navertalktalk_yn'] : false;
		if ( ! $this->enabled ) {
			return;
		}

		$this->id = isset( $this->settings['navertalktalk_id'] ) && ! empty( $this->settings['navertalktalk_id'] ) ? $this->settings['navertalktalk_id'] : '';
		if ( wp_is_mobile() ) {
			$this->id = isset( $this->settings['navertalktalk_mobile_id'] ) && ! empty( $this->settings['navertalktalk_mobile_id'] ) ? $this->settings['navertalktalk_mobile_id'] : $this->id;
		}

		// Enqueue scripts/styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ), -1 );
		add_action( 'wp_head', array( $this, 'add_styles' ) );

		// Shortcodes.
		add_shortcode( 'navertalktalk', array( $this, 'shortcode_output' ) );

		// Add Naver TalkTalk.
		add_action( 'wp_footer', array( $this, 'output' ), 90 );
	}

	/**
	 * Load Naver TalkTalk scripts.
	 */
	public function add_scripts() {
		//wp_enqueue_script( 'wc-korea-naver-talktalk', 'https://partner.talk.naver.com/banners/script', array(), null, true );
	}

	/**
	 * Load Naver TalkTalk styles.
	 */
	public function add_styles() {
		ob_start();
		?>
		<style type="text/css">
			#navertalktalk-button {
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
	 * Naver TalkTalk Shortcode
	 *
	 * @param array  $atts Attributes.
	 * @param string $content
	 */
	public function shortcode_button( $atts, $content = null ) {
		global $post;

		$atts = shortcode_atts(
			array(
				'id'  => $this->id,
				'ref' => get_permalink( $post->ID ),
			),
			$atts,
			'navertalktalk'
		);

		ob_start();
		?>
		<div class="talk_banner_div"
			data-id="<?php echo esc_attr( $id ); ?>"
			data-ref="<?php echo esc_url( $ref ); ?>">
		</div>
		<?php
		ob_end_flush();
	}

	/**
	 * Output Naver TalkTalk button
	 */
	public function output() {
		global $post;

		?>
		<script type="text/javascript" src="https://partner.talk.naver.com/banners/script"></script>
		<div class="talk_banner_div" data-id="<?php echo esc_attr( $this->id ); ?>">
		</div>
		<?php
	}

}

return new WC_Korea_Naver_TalkTalk();
