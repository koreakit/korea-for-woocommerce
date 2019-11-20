<?php
/**
 * WooCommerce Korea - Premium Addons 
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Addons_Premium extends WC_Korea_Addons {

	/**
	 * Outputs addons.
	 *
	 * @since 1.0.0
	 */
	public function output() {
		if ( isset( $_GET['tab'] ) && 'addons' !== $_GET['tab'] ) {
			return;
		}

		$addons = apply_filters( 'wc_korea_addons', [
			'korea-for-woocommerce-pro' => [
				'title'       => __('Korea for WooCommerce Pro','korea-for-woocommerce'),
				'description' => __('Enchance your shop for Korean use with more options.','korea-for-woocommerce'),
				'permalink'   => __('https://greys.co/product/korea-for-woocommerce-pro/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-inicis' => [
				'title'       => __('KG INICIS Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('KG INICIS','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-inicis/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-kakaopay' => [
				'title'       => __('KakaoPay Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('KakaoPay','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-kakaopay/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-kcp' => [
				'title'       => __('NHN KCP Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('NHN KCP','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-kcp/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-lguplus' => [
				'title'       => __('LG U+ Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('LG U+','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-lguplus/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-naverpay' => [
				'title'       => __('NaverPay Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('NaverPay','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-naverpay/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-nicepay' => [
				'title'       => __('NICEPAY Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('NICEPAY','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-nicepay/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-payco' => [
				'title'       => __('Payco Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('Payco','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-payco/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
			'woocommerce-gateway-tosspay' => [
				'title'       => __('TossPay Gateway','korea-for-woocommerce'),
				'description' => sprintf(
					__('Take payments via %1$s payment methods.','korea-for-woocommerce'),
					__('TossPay','korea-for-woocommerce')
				),
				'permalink'   => __('https://greys.co/product/woocommerce-gateway-tosspay/','korea-for-woocommerce'),
				'price'       => __('$129.00','korea-for-woocommerce')
			],
		]);

		/**
		 * Addons page view.
		 *
		 * @uses $addons
		 */
		include_once WC_KOREA_ABSPATH . '/includes/admin/views/html-admin-page-korea-addons.php';
	}

}

new WC_Korea_Addons_Premium();