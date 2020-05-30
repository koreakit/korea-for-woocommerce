<?php
/**
 * Order View - Virtual Bank Information
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/order-view-vbank.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

?>
<section class="woocommerce-vbank-details">
	<?php if ( ! empty( $account_name ) ) { ?>
		<h3 class="wc-vbank-details-account-name">
			<?php wp_kses_post( wp_unslash( $account_name ) ); ?>
		</h3>
	<?php } ?>
	<h2 class="wc-vbank-details-heading"><?php esc_html__( 'Virtual Bank details', 'korea-for-woocommerce' ); ?></h2>
	<div style="margin-bottom: 40px;">
		<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
			<tbody>
				<tr>
					<?php
					foreach ( $account_fields as $field_key => $field ) {
						if ( ! empty( $field['value'] ) ) {
							?>
							<th class="td" style="text-align:center;">
								<?php wp_kses_post( wptexturize( $field['label'] ) ); ?>
							</th>
							<?php
						}
					}
					?>
				</tr>
				<tr>
					<?php
					foreach ( $account_fields as $field_key => $field ) {
						if ( ! empty( $field['value'] ) ) {
							?>
							<td class="td" style="text-align:center;">
								<?php wp_kses_post( wptexturize( $field['value'] ) ); ?>
							</td>
							<?php
						}
					}
					?>
				</tr>
			</tbody>
		</table>
	</div>
</section>
