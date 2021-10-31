<?php
/**
 * Admin View: Page - Korea Addons
 *
 * @package WC_Korea
 * @author  @jgreys
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wc-korea container">
	<div class="wrap wrap-korea-addons">
		<h2><?php esc_html_e( 'Korea for WooCommerce', 'korea-for-woocommerce' ); ?></h2>
		<?php $this->output_categories(); ?>
		<?php if ( empty( $addons ) ) { ?>
			<div class="notice-info notice inline"><p><?php echo wp_kses_post( __( 'Our addons can be found here: <a href="https://greys.co/products/woocommerce-korea-addons/">Korea for WooCommerce Addons</a>', 'korea-for-woocommerce' ) ); ?></p></div>
		<?php } else { ?>
			<ul class="products">
				<?php foreach ( $addons as $slug => $addon ) { ?>
					<li class="product">
						<a href="<?php echo esc_attr( $addon['permalink'] ); ?>">
							<h2><?php echo wp_kses_post( $addon['title'] ); ?></h2>
							<p><?php echo wp_kses_post( $addon['description'] ); ?></p>
						</a>
					</li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
</div>
