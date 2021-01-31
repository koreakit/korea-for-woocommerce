<?php
/**
 * WooCommerce Korea
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

return apply_filters(
	'wc_korea_support_settings',
	array(
		'kakaochannel'            => array(
			'title'       => __( 'Kakao Channel', 'korea-for-woocommerce' ),
			'description' => __( 'Once enabled, you can copy and paste the following shortcode <code>[kakaochannel]</code> directly into the page, post or widget where you\'d like the button to show up.', 'korea-for-woocommerce' ),
			'type'        => 'title',
		),
		'kakaochannel_yn'         => array(
			'title'       => __( 'Enable/Disable', 'korea-for-woocommerce' ),
			'description' => sprintf(
				/* translators: Kakao Channel registration link */
				__( 'You can create your Kakao Channel <a href="%s">here</a>.', 'korea-for-woocommerce' ),
				'https://ch.kakao.com/register'
			),
			'label'       => __( 'Enable Kakao Channel', 'korea-for-woocommerce' ),
			'type'        => 'checkbox',
			'default'     => 'no',
		),
		'kakaochannel_appkey'     => array(
			'title' => __( 'App Key', 'korea-for-woocommerce' ),
			'type'  => 'text',
			'class' => 'show_if_kakaochannel',
		),
		'kakaochannel_id'         => array(
			'title' => __( 'Channel ID', 'korea-for-woocommerce' ),
			'type'  => 'text',
			'class' => 'show_if_kakaochannel',
		),
		'kakaochannel_btntype'    => array(
			'title'   => __( 'Button Type', 'korea-for-woocommerce' ),
			'type'    => 'select',
			'class'   => 'show_if_kakaochannel',
			'options' => array(
				'add'      => __( 'Add', 'korea-for-woocommerce' ),
				'consult'  => __( 'Consult', 'korea-for-woocommerce' ),
				'question' => __( 'Question', 'korea-for-woocommerce' ),
			),
		),
		'kakaochannel_btnsize'    => array(
			'title'   => __( 'Button Size', 'korea-for-woocommerce' ),
			'type'    => 'select',
			'class'   => 'show_if_kakaochannel',
			'options' => array(
				'small' => __( 'Small', 'korea-for-woocommerce' ),
				'large' => __( 'Large', 'korea-for-woocommerce' ),
			),
		),
		'kakaochannel_btncolor'   => array(
			'title'   => __( 'Button Color', 'korea-for-woocommerce' ),
			'type'    => 'select',
			'class'   => 'show_if_kakaochannel',
			'options' => array(
				'yellow' => __( 'Yellow', 'korea-for-woocommerce' ),
				'mono'   => __( 'Mono', 'korea-for-woocommerce' ),
			),
		),
		'navertalktalk'         => array(
			'title'       => __( 'Naver TalkTalk', 'korea-for-woocommerce' ),
			'description' => __( 'Once enabled, you can copy and paste the following shortcode <code>[navertalktalk]</code> directly into the page, post or widget where you\'d like the button to show up.', 'korea-for-woocommerce' ),
			'type'        => 'title',
		),
		'navertalktalk_yn'      => array(
			'title'       => __( 'Enable/Disable', 'korea-for-woocommerce' ),
			'description' => sprintf(
				/* translators: Naver TalkTalk registration link */
				__( 'You can create your Naver TalkTalk <a href="%s">here</a>.', 'korea-for-woocommerce' ),
				'https://partner.talk.naver.com/register'
			),
			'label'       => __( 'Enable Naver TalkTalk', 'korea-for-woocommerce' ),
			'type'        => 'checkbox',
			'default'     => 'no',
		),
		'navertalktalk_id'        => array(
			'title'       => __( 'TalkTalk ID (PC)', 'korea-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter an ID for Naver TalkTalk (PC)', 'korea-for-woocommerce' ),
			'class'       => 'show_if_navertalktalk',
		),
		'navertalktalk_mobile_id' => array(
			'title'       => __( 'TalkTalk ID (mobile)', 'korea-for-woocommerce' ),
			'type'        => 'text',
			'description' => __( 'Enter an ID for Naver TalkTalk (mobile)', 'korea-for-woocommerce' ),
			'class'       => 'show_if_navertalktalk',
		),
	)
);
