<?php
/**
 * Admin View: Page - Licenses Korea Addons
 *
 * @package WC_Korea
 * @author  @jgreys
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wc-korea container">
	<form method="post" id="mainform" action="options.php" enctype="multipart/form-data">
		<div class="wrap wrap-korea-licenses">
			<h2><?php esc_html_e( 'Korea for WooCommerce', 'korea-for-woocommerce' ); ?></h2>
			<?php $this->output_categories(); ?>
			<?php if ( empty( $licenses ) ) { ?>
				<div class="notice-info notice inline">
					<p><?php echo wp_kses_post( __( 'There are no active addons that require license keys. <a href="https://greys.co/products/woocommerce-korea-addons/">View our addons</a>', 'korea-for-woocommerce' ) ); ?></p>
				</div>
			<?php } else { ?>
				<table class="form-table">
					<p style="margin-top:0;">
						<?php esc_html_e( 'Enter your plugin license keys here to receive automatic updates.', 'korea-for-woocommerce' ); ?>
					</p>

					<?php
						settings_fields( 'wc_korea_plugin_license_settings' );
						do_settings_sections( 'wc_korea_plugin_license_settings_section' );
						submit_button();
					?>
				</table>
			<?php } ?>
		</div>
	</form>
</div>
