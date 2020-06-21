<?php
/**
 * WooCommerce Korea
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

return apply_filters(
	'wc_korea_advanced_settings',
	array(
		'naver_analytics' => array(
			'title'       => __( 'Naver Analytics ID', 'korea-for-woocommerce' ),
			'description' => sprintf(
				/* translators: 1) Naver Analytics link */
				__( 'You can get your Naver Analytics ID <a href="%s">here</a>.', 'korea-for-woocommerce' ),
				'https://analytics.naver.com/management/mysites.html'
			),
			'type'        => 'text',
			'default'     => '',
		),
	)
);
