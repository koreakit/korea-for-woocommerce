<?php
/**
 * WooCommerce Korea - Naver SEP (Search Engine Page)
 */

namespace Greys\WooCommerce\Korea\ShoppingEnginePage;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Naver class.
 */
class Naver {

	/**
	 * Initialize
	 */
	public function __construct() {
		$settings = get_option( 'woocommerce_korea_settings' );

		$this->enabled = isset( $settings['naver_shopping_ep'] ) && ! empty( $settings['naver_shopping_ep'] ) ? 'yes' === $settings['naver_shopping_ep'] : false;

		if ( ! $this->enabled ) {
			return;
		}

		add_action( 'parse_request', array( $this, 'output' ) );
	}

	/**
	 * Naver SEP output
	 *
	 * @return mixed
	 */
	public function output() {
		$sep = isset( $_GET['wc-sep'] ) && ! empty( $_GET['wc-sep'] ) ? wc_clean( $_GET['wc-sep'] ) : '';
		if ( 'naver' !== $sep ) {
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

		$headers = array(
			'id',
			'title',
			'price_pc',
			'link',
			'mobile_link',
			'image_link',
			'category_name1',
			'shipping',
			'class',
			'update_time',
		);

		ob_start();

		echo implode( chr( 9 ), $headers ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		foreach ( $products as $product ) {
			if ( ! $product->is_visible() ) {
				continue;
			}

			$category_name = '';
			$categories    = $product->get_category_ids();
			foreach ( $categories as $category_id ) {
				$category      = get_term_by( 'id', $category_id, 'product_cat' );
				if ( 0 !== $category->parent ) {
					continue;
				}

				$category_name = $category->name;
			}

			$values   = array();
			$values[] = absint( $product->get_id() );
			$values[] = esc_html( $product->get_name() );
			$values[] = esc_html( $product->get_regular_price() );
			$values[] = esc_url( $product->get_permalink() );
			$values[] = esc_url( $product->get_image_url() );
			$values[] = esc_html( $category_name );
			$values[] = '0';
			$values[] = 'u';
			$values[] = esc_html( $product->get_date_modified( 'Y-m-d H:i:s' ) );

			echo PHP_EOL . implode( chr( 9 ), $values ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo ob_get_clean();
		exit;
	}

}
