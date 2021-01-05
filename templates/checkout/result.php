<?php
/**
 * Checkout - Korean Payment Gateways Result/Return
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/result.php.
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
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<script type="text/javascript">
		<?php if ( isset( $message ) && ! empty( $message ) ) { ?>
			alert( '<?php echo esc_js( $message ); ?>' );
		<?php } ?>

		<?php if ( wp_is_mobile() ) { ?>
			location.href = '<?php echo esc_url( $redirect_url ); ?>';
		<?php } else { ?>
			if ( window.opener && ! window.opener.closed ) {
				window.opener.location.href = '<?php echo esc_url( $redirect_url ); ?>';
				window.close();
			} else {
				location.href = '<?php echo esc_url( $redirect_url ); ?>';
			}
		<?php } ?>
	</script>
	<body></body>
</html>
