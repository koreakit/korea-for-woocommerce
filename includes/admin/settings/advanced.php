<?php defined( 'ABSPATH' ) || exit;

return apply_filters( 'wc_korea_advanced_settings', [
	'naver_analytics' => [
		'title'         => __( 'Naver Analytics ID', 'korea-for-woocommerce' ),
		'description'   => sprintf(
			__( 'You can get your Naver Analytics ID <a href="%s">here</a>.', 'korea-for-woocommerce' ),
			'https://analytics.naver.com/management/mysites.html'
		),
		'type'          => 'text',
		'default'       => ''
	]
]);