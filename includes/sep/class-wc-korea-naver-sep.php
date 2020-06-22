<?php
/**
 * WooCommerce Korea - Naver SEP (Search Engine Page)
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Naver_SEP class.
 */
class WC_Korea_Naver_SEP {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$settings = get_option( 'woocommerce_korea_settings' );

		$this->enabled = isset( $settings['naver_shopping_ep'] ) && ! empty( $settings['naver_shopping_ep'] ) ? 'yes' === $settings['naver_shopping_ep'] : false;

		if ( ! $this->enabled ) {
			return;
		}

		add_action( 'template_include', array( $this, 'template_include' ) );
	}

	/**
	 * Naver SEP output
	 *
	 * @param  string $original_template Original template.
	 * @return string
	 */
	public function template_include( $original_template ) {
		$wc_sep = get_query_var( 'wc-sep' );

		if ( ! $wc_sep ) {
			return $original_template;
		}

		if ( 'naver' !== $wc_sep ) {
			return $original_template;
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

		while ( $products->have_posts() ) {
			$products->the_post();

			global $product;

			if ( empty( $product ) || ! $product->is_visible() ) {
				return;
			}

			$categories = get_the_terms( get_the_ID(), 'product_cat' );
			foreach ( $categories as $category ) {
				if ( 0 === $category->parent ) {
					$category = $category->name;
				}
			}

			$values   = array();
			$values[] = intval( get_the_ID() );
			$values[] = esc_html( get_the_title() );
			$values[] = esc_html( get_post_meta( get_the_ID(), '_regular_price', true ) );
			$values[] = esc_url( get_the_permalink() );
			$values[] = esc_url( get_the_post_thumbnail_url( get_the_ID() ) );
			$values[] = esc_html( $category );
			$values[] = '0';
			$values[] = 'u';
			$values[] = esc_html( get_the_modified_date( 'Y-m-d' ) . ' ' . get_the_modified_date( 'H:i:s' ) );

			echo PHP_EOL . implode( chr( 9 ), $values ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

}

return new WC_Korea_Naver_SEP();
