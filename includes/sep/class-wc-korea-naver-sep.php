<?php
/**
 * WooCommerce Korea - Naver SEP (Search Engine Page)
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Naver_SEP {

	public function __construct() {
		$this->settings = get_option( 'woocommerce_korea_settings' );

		if ( ! isset( $this->settings['naver_shopping_ep'] ) || 'yes' !== $this->settings['naver_shopping_ep'] ) {
			return;
		}

		add_action( 'template_include', array( $this, 'template_include' ) );
	}

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

		ob_start();

		echo 'id';
		echo "\t";
		echo 'title';
		echo "\t";
		echo 'price_pc';
		echo "\t";
		echo 'link';
		echo "\t";
		echo 'image_link';
		echo "\t";
		echo 'category_name1';
		echo "\t";
		echo 'shipping';
		echo "\t";
		echo 'class';
		echo "\t";
		echo 'update_time';

		while ( $products->have_posts() ) {
			$products->the_post();

			global $product;

			if ( empty( $product ) || ! $product->is_visible() ) {
				return;
			}

			$categories = get_the_terms( get_the_ID(), 'product_cat' );
			foreach ( $categories as $category ) {
				if ( $category->parent == 0 ) {
					$category = $category->name;
				}
			}

			echo "\n";
			echo get_the_ID();
			echo "\t";
			echo get_the_title();
			echo "\t";
			echo get_post_meta( get_the_ID(), '_regular_price', true );
			echo "\t";
			echo get_the_permalink();
			echo "\t";
			echo get_the_post_thumbnail_url( get_the_ID() );
			echo "\t";
			echo $category;
			echo "\t";
			echo '0';
			echo "\t";
			echo 'u';
			echo "\t";
			echo get_the_modified_date( 'Y-m-d' ) . ' ' . get_the_modified_date( 'H:i:s' );
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

}

return new WC_Korea_Naver_SEP();
