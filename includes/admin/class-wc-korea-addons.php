<?php
/**
 * WooCommerce Korea - Addons 
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Addons {

	public function __construct() {
		$this->tab = ! empty( $_GET['tab'] ) ? sanitize_title($_GET['tab']) : 'addons';

		add_action( 'current_screen', array( $this, 'init_tab' ) );
	}

	/**
	 * Checks the current screen to output our tab and settings content.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Screen $screen the current screen
	 */
	public function init_tab( $screen ) {
		if ( 'woocommerce_page_wc-addons' !== $screen->id ) {
			return;
		}

		if ( ! wp_script_is( 'wc-korea-addons', 'enqueued' ) ) {
			wp_enqueue_script( 'wc-korea-addons', plugins_url('assets/js/admin/addons.js', WC_KOREA_MAIN_FILE), [], WC_KOREA_VERSION, true );
			wp_localize_script( 'wc-korea-addons', 'wc_korea_addons_params', [
				'title'     => __( 'Korea for WooCommerce', 'korea-for-woocommerce' ),
				'link'      => add_query_arg(['page' => 'wc-addons', 'section' => 'wc-korea'], admin_url('admin.php')),
				'is_active' => 'wc-korea' === $_GET['section'] ? true : false
			]);
		}

		if ( 'wc-korea' !== $_GET['section'] ) {
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
	 *	Get categories
	 *
	 *	@return array
	 */
	public function get_categories() {
		return [
			'addons'   => __( 'Addons', 'korea-for-woocommerce' ),
			'licenses' => __( 'Licenses', 'korea-for-woocommerce' )
		];
	}

	/**
	 *	Output categories
	 */
	public function output_categories() {
		$categories = $this->get_categories();
		
		if ( empty( $categories ) || 1 === sizeof( $categories ) ) {
			return;
		}

		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $categories );
		foreach ( $categories as $id => $label ) {
			$args = ['page' => 'wc-addons', 'section' => 'wc-korea'];
			if ( $id !== 'premium' ) {
				$args['tab'] = sanitize_title( $id );
			}

			echo '<li><a href="' . add_query_arg($args, admin_url('admin.php')) . '" class="' . ( $this->tab == $id ? 'current' : '' ) . '">' . $label . '</a></li>';
		}
		echo '</ul>';
		echo '<div class="clear"></div>';
	}

}