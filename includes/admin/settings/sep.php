<?php
/**
 * WooCommerce Korea
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

return apply_filters(
	'wc_korea_sep_settings',
	array(
		'daum_shopping_ep'  => array(
			'title'       => __( 'Daum Shopping', 'korea-for-woocommerce' ),
			'description' => sprintf(
				/* translators: 1) Daum SEP link, 2) Daum Shopping Console link, 3) Daum Shopping Console name */
				__( 'Once enabled, you must add the following URL <code>%1$s</code> to your <a href="%2$s" target="_blank">%3$s</a>.', 'korea-for-woocommerce' ),
				add_query_arg( array( 'wc-sep' => 'daum' ), site_url( '/' ) ),
				'https://shopping.biz.daum.net/',
				__( 'Daum Shopping Console', 'korea-for-woocommerce' )
			),
			'label'       => __( 'Enable', 'korea-for-woocommerce' ),
			'type'        => 'checkbox',
			'default'     => 'no',
		),
		'naver_shopping_ep' => array(
			'title'       => __( 'Naver Shopping', 'korea-for-woocommerce' ),
			'description' => sprintf(
				/* translators: 1) Naver SEP link, 2) Naver Shopping Console link, 3) Naver Shopping Console name */
				__( 'Once enabled, you must add the following URL <code>%1$s</code> to your <a href="%2$s" target="_blank">%3$s</a>.', 'korea-for-woocommerce' ),
				add_query_arg( array( 'wc-sep' => 'naver' ), site_url( '/' ) ),
				'https://adcenter.shopping.naver.com/main.nhn',
				__( 'Naver Shopping Console', 'korea-for-woocommerce' )
			),
			'label'       => __( 'Enable', 'korea-for-woocommerce' ),
			'type'        => 'checkbox',
			'default'     => 'no',
		),
	)
);
