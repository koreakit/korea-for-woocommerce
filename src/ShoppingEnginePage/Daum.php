<?php
/**
 * WooCommerce Korea - Daum SEP (Search Engine Page)
 */

namespace Greys\WooCommerce\Korea\ShoppingEnginePage;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daum class.
 */
class Daum {

	/**
	 * Enabled.
	 */
	private static $enabled;

	/**
	 * Initialize
	 */
	public function __construct() {
		$settings = get_option( 'woocommerce_korea_settings' );

		self::$enabled = isset( $settings['daum_shopping_ep'] ) && ! empty( $settings['daum_shopping_ep'] ) ? 'yes' === $settings['daum_shopping_ep'] : false;

		if ( ! self::$enabled ) {
			return;
		}

		add_action( 'parse_request', [ __CLASS__, 'output' ] );
	}

	/**
	 * Daum SEP output
	 *
	 * @return mixed
	 */
	public static function output() {
		$sep = isset( $_GET['wc-sep'] ) && ! empty( $_GET['wc-sep'] ) ? wc_clean( $_GET['wc-sep'] ) : '';
		if ( 'daum' !== $sep ) {
			return;
		}

		$products = wc_get_products(
			array(
				'status'         => 'publish',
				'limit'          => -1,
			)
		);

		if ( empty( $products ) ) {
			return;
		}

		header( 'Content-Type: text/plain; charset=utf-8;' );

		ob_start();

		foreach ( $products as $product ) {
			if ( ! $product->is_visible() ) {
				continue;
			}

			$categories = $product->get_category_ids();
			$class      = 'U';

			/**
			 * Verify with variations
			 */
			if ( $product->get_stock_quantity() === 0 ) {
				$class = 'D';
			}

			$values   = array();
			$values[] = '<<<begin>>>';
			$values[] = '<<<mapid>>>' . absint( $product->get_id() );
			$values[] = '<<<price>>>' . esc_html( $product->get_regular_price() );
			$values[] = '<<<class>>>' . esc_html( $class );
			$values[] = '<<<utime>>>' . esc_html( $product->get_modified_date( 'H:i:s' ) );
			$values[] = '<<<pname>>>' . esc_html( $product->get_name() );
			$values[] = '<<<pgurl>>>' . esc_url( $product->get_permalink() );
			$values[] = '<<<igurl>>>' . esc_url( $product->get_image_url() );

			$i = 1;
			foreach ( $categories as $category_id ) {
				$category = get_term( $category_id, 'product_cat' );
				if ( ! $category || is_wp_error( $category ) ) {
					continue;
				}

				$values[] = '<<<cate' . $i . '>>>' . absint( $category->term_id );
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
