<?php
/**
 * WooCommerce Korea - Daum SEP (Search Engine Page)
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Daum_SEP class.
 */
class WC_Korea_Daum_SEP {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$settings = get_option( 'woocommerce_korea_settings' );

		$this->enabled = isset( $settings['daum_shopping_ep'] ) && ! empty( $settings['daum_shopping_ep'] ) ? 'yes' === $settings['daum_shopping_ep'] : false;

		if ( ! $this->enabled ) {
			return;
		}

		add_action( 'parse_request', array( $this, 'output' ) );
	}

	/**
	 * Daum SEP output
	 *
	 * @return string
	 */
	public function output() {
		$sep = isset( $_GET['wc-sep'] ) && ! empty( $_GET['wc-sep'] ) ? wc_clean( $_GET['wc-sep'] ) : '';
		if ( 'daum' !== $sep ) {
			return;
		}

		$products = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'publish' ),
				'posts_per_page' => -1,
			)
		);

		if ( ! $products->have_posts() ) {
			return;
		}

		header( 'Content-Type: text/plain; charset=utf-8;' );

		ob_start();

		while ( $products->have_posts() ) {
			$products->the_post();

			global $product;

			if ( empty( $product ) || ! $product->is_visible() ) {
				continue;
			}

			$categories = get_the_terms( get_the_ID(), 'product_cat' );
			$class = 'U';

			/**
			 * Verify with variations
			 */
			if ( $product->get_stock_quantity() === 0 ) {
				$class = 'D';
			}

			$values   = array();
			$values[] = '<<<begin>>>';
			$values[] = '<<<mapid>>>' . absint( get_the_ID() );
			$values[] = '<<<price>>>' . esc_html( get_post_meta( get_the_ID(), '_regular_price', true ) );
			$values[] = '<<<class>>>U';
			$values[] = '<<<utime>>>' . esc_html( get_the_modified_date( 'H:i:s' ) );
			$values[] = '<<<pname>>>' . esc_html( get_the_title() );
			$values[] = '<<<pgurl>>>' . esc_url( get_the_permalink() );
			$values[] = '<<<igurl>>>' . esc_url( get_the_post_thumbnail_url( get_the_ID() ) );

			$i = 1;
			foreach ( $categories as $category ) {
				$values[] = '<<<cate' . $i . '>>>' . absint( $category->ID );
				$values[] = '<<<caid' . $i . '>>>' . esc_html( $category->name );
				++$i;
			}

			$values[] = '<<<deliv>>>0';
			$values[] = '<<<ftend>>>';

			echo implode( PHP_EOL, $values ) . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_reset_postdata();

		echo ob_get_clean();
		exit;
	}

}

return new WC_Korea_Daum_SEP();
