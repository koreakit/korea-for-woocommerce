<?php defined( 'ABSPATH' ) || exit;

return apply_filters( 'wc_korea_sep_settings', [
	'daum_shopping_ep' => [
		'title'         => __( 'Daum Shopping', 'korea-for-woocommerce' ),
		'description'   => sprintf(
			__( 'Once enabled, you must add the following URL <code>%s</code> to your <a href="%s" target="_blank">%s</a>.', 'korea-for-woocommerce' ),
			add_query_arg(['wc-sep' => 'daum'], site_url('/')),
			'https://shopping.biz.daum.net/',
			__( 'Daum Shopping Console', 'korea-for-woocommerce' )
		),
		'label'         => __( 'Enable', 'korea-for-woocommerce' ),
		'type'          => 'checkbox',
		'default'       => 'no',
	],
	'naver_shopping_ep' => [
		'title'         => __( 'Naver Shopping', 'korea-for-woocommerce' ),
		'description'   => sprintf(
			__( 'Once enabled, you must add the following URL <code>%s</code> to your <a href="%s" target="_blank">%s</a>.', 'korea-for-woocommerce' ),
			add_query_arg(['wc-sep' => 'naver'], site_url('/')),
			'https://adcenter.shopping.naver.com/main.nhn',
			__( 'Naver Shopping Console', 'korea-for-woocommerce' )
		),
		'label'         => __( 'Enable', 'korea-for-woocommerce' ),
		'type'          => 'checkbox',
		'default'       => 'no',
	]
]);