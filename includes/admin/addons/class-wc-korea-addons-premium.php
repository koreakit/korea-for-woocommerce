<?php
/**
 * WooCommerce Korea - Premium Addons
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Addons_Premium class.
 *
 * @extends WC_Korea_Addons
 */
class WC_Korea_Addons_Premium extends WC_Korea_Addons {

	/**
	 * Outputs addons.
	 *
	 * @since 1.0.0
	 */
	public function output() {
		$tab = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'addons'; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended

		if ( 'addons' !== $tab ) {
			return;
		}

		$addons = apply_filters(
			'wc_korea_addons',
			array(
				'korea-for-woocommerce-pro'           => array(
					'title'       => __( 'Korea for WooCommerce Pro', 'korea-for-woocommerce' ),
					'description' => __( 'Enhance your shop for Korean use with more options.', 'korea-for-woocommerce' ),
					'permalink'   => __( 'https://greys.co/plugin/korea-for-woocommerce-pro/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-inicis'          => array(
					'title'       => __( 'KG INICIS Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'KG INICIS', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-inicis/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-kcp'             => array(
					'title'       => __( 'NHN KCP Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'NHN KCP', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-kcp/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-lguplus'         => array(
					'title'       => __( 'LG U+ Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'LG U+', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-lguplus/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-easypay'         => array(
					'title'       => __( 'EasyPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'EasyPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-easypay/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-nicepay'         => array(
					'title'       => __( 'NICEPAY Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'NICEPAY', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-nicepay/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-kakaopay'        => array(
					'title'       => __( 'KakaoPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'KakaoPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-kakaopay/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-naverpay'        => array(
					'title'       => __( 'NaverPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'NaverPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-naverpay/', 'korea-for-woocommerce' ),
					'price'       => __( '$59.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-payco'           => array(
					'title'       => __( 'Payco Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'Payco', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-payco/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-tosspay'         => array(
					'title'       => __( 'TossPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'TossPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-tosspay/', 'korea-for-woocommerce' ),
					'price'       => __( '$129.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-kakaotalk-notifications' => array(
					'title'       => __( 'KakaoTalk Notifications', 'korea-for-woocommerce' ),
					'description' => __( 'Send KakaoTalk order notifications to admins and customers for your WooCommerce store.', 'korea-for-woocommerce' ),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-kakaotalk-notifications/', 'korea-for-woocommerce' ),
					'price'       => __( '$59.00', 'korea-for-woocommerce' ),
				),
				'woocommerce-kras'                    => array(
					'title'       => __( 'Korean Authentication Services', 'korea-for-woocommerce' ),
					'description' => __( 'Korean Authentication Services (i-PIN, mobile phone) for WooCommerce', 'korea-for-woocommerce' ),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-kras/', 'korea-for-woocommerce' ),
					'price'       => __( '$249.00', 'korea-for-woocommerce' ),
				),
			)
		);

		/**
		 * Addons page view.
		 *
		 * @uses $addons
		 */
		include_once WC_KOREA_ABSPATH . '/includes/admin/views/html-admin-page-korea-addons.php';
	}

}

new WC_Korea_Addons_Premium();
