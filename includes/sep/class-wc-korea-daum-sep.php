<?php
/**
 * WooCommerce Korea - Daum SEP (Search Engine Page)
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Daum_SEP {

	public function __construct() {
		$this->settings = get_option('woocommerce_korea_settings');

		if ( ! isset($this->settings['daum_shopping_ep']) || 'yes' !== $this->settings['daum_shopping_ep'] ) {
			return;
		}

		add_action('template_include', array($this, 'template_include'));
	}

	public function template_include( $original_template ) {
		$wc_sep = get_query_var('wc-sep');

		if ( ! $wc_sep ) {
			return $original_template;
		}

		if ( 'daum' !== $wc_sep ) {
			return $original_template;
		}

		$products = new WP_Query([
			'post_type'      => 'product',
			'post_status'    => ['publish'],
			'posts_per_page' => -1,
		]);

		if ( ! $products->have_posts() ) {
			return;
		}

		ob_start();

		while ( $products->have_posts() ) {
			$products->the_post();

			global $product;

			if ( empty( $product ) || ! $product->is_visible() ) {
				return;
			}

			$categories = get_the_terms( get_the_ID(), 'product_cat' );
			foreach ( $categories as $category ) {
				$category_id   = $category->ID;
				$category_name = $category->name;
			}

			$class = 'U';
			
			/**
			 * @todo verifier avec les variations
			 */
			if ( $product->get_stock_quantity() == 0 ) {
				$class = 'D';
			}

			echo $lt .'begin'. $gt . PHP_EOL;
			echo $lt .'mapid'. $gt . get_the_ID() . PHP_EOL;
			echo $lt .'price'. $gt . get_post_meta( get_the_ID(), '_regular_price', true) . PHP_EOL;
		    echo $lt .'class'. $gt .'U' . PHP_EOL;
			echo $lt .'utime'. $gt . get_the_modified_date('H:i:s') . PHP_EOL;
			echo $lt .'pname'. $gt . get_the_title() . PHP_EOL;
			echo $lt .'pgurl'. $gt . get_the_permalink() . PHP_EOL;
			echo $lt .'igurl'. $gt . get_the_post_thumbnail_url(get_the_ID()) . PHP_EOL;

			$i = 1;
			foreach ( $categories as $category ) {
				echo $lt .'cate'. $i . $gt . $category->ID . PHP_EOL;
				echo $lt .'caid'. $i . $gt . $category->name . PHP_EOL;
				++$i;
			}
		   
			echo $lt .'deliv'. $gt .'0' . PHP_EOL;
		    echo $lt .'ftend'. $gt . PHP_EOL;
		}

		wp_reset_postdata();
		
		return ob_get_clean();
	}

}

new WC_Korea_Daum_SEP();