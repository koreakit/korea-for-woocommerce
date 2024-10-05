<?php
/**
 * WooCommerce Korea - Naver Analytics
 */

namespace Greys\WooCommerce\Korea\Analytics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use const Greys\WooCommerce\Korea\Version as VERSION;

/**
 * Naver class.
 */
class Naver {

	/**
	 * Initialize
	 */
	public function __construct() {
		$settings = get_option( 'woocommerce_korea_settings' );

		$this->id = isset( $settings['naver_analytics'] ) && ! empty( $settings['naver_analytics'] ) ? sanitize_text_field( $settings['naver_analytics'] ) : null;

		if ( ! $this->id ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
	}

	/**
	 * Enqueue Naver Analytics script
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_script( 'wc-korea-naver-analytics', '//wcs.naver.net/wcslog.js', array(), VERSION, true );
	}

	/**
	 * Add Naver Analytics
	 */
	public function wp_footer() {
		?>
		<script type="text/javascript">
			if ( !wcs_add ) {
				var wcs_add = {};
			}
			wcs_add['wa'] = "<?php echo esc_js( $this->id ); ?>";
			wcs_do();
		</script>
		<?php
	}

}
