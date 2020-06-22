<?php
/**
 * WooCommerce Korea - Addons
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Addons class.
 */
class WC_Korea_Addons {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->tab = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'addons'; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended

		add_action( 'current_screen', array( $this, 'init_tab' ) );
	}

	/**
	 * Checks the current screen to output our tab and settings content.
	 *
	 * @param \WP_Screen $screen the current screen.
	 */
	public function init_tab( $screen ) {
		if ( 'woocommerce_page_wc-addons' !== $screen->id ) {
			return;
		}

		$section = isset( $_GET['section'] ) && ! empty( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : null; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended

		if ( ! wp_script_is( 'wc-korea-addons', 'enqueued' ) ) {
			wp_enqueue_script( 'wc-korea-addons', plugins_url( 'assets/js/admin/addons.js', WC_KOREA_MAIN_FILE ), array(), WC_KOREA_VERSION, true );
			wp_localize_script(
				'wc-korea-addons',
				'wc_korea_addons_params',
				array(
					'title'     => __( 'Korea for WooCommerce', 'korea-for-woocommerce' ),
					'link'      => add_query_arg(
						array(
							'page'    => 'wc-addons',
							'section' => 'wc-korea',
						),
						admin_url( 'admin.php' )
					),
					'is_active' => 'wc-korea' === $section ? true : false,
				)
			);
		}

		if ( 'wc-korea' !== $section ) {
			return;
		}

		// WooCommerce Helper Remove Admin Notices
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

		// WooCommerce Empty Addons Sections
		add_filter( 'woocommerce_addons_sections', '__return_empty_array' );

		// Output the content
		$this->output();
	}

	/**
	 *  Get categories
	 *
	 *  @return array
	 */
	public function get_categories() {
		return array(
			'addons'   => __( 'Addons', 'korea-for-woocommerce' ),
			'licenses' => __( 'Licenses', 'korea-for-woocommerce' ),
		);
	}

	/**
	 *  Output categories
	 */
	public function output_categories() {
		$categories = $this->get_categories();

		if ( empty( $categories ) || 1 === count( $categories ) ) {
			return;
		}

		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $categories );
		foreach ( $categories as $id => $label ) {
			$args = array(
				'page'    => 'wc-addons',
				'section' => 'wc-korea',
			);

			if ( 'premium' !== $id ) {
				$args['tab'] = sanitize_title( $id );
			}

			echo '<li><a href="' . esc_url( add_query_arg( $args, admin_url( 'admin.php' ) ) ) . '" class="' . esc_attr( $this->tab === $id ? 'current' : '' ) . '">' . esc_html( $label ) . '</a></li>';
		}
		echo '</ul>';
		echo '<div class="clear"></div>';
	}

}
