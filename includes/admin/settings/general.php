<?php defined( 'ABSPATH' ) || exit;

return apply_filters( 'wc_korea_general_settings', [
	'postcode' => [
		'title'        => __('Korean Postcode', 'korea-for-woocommerce'),
		'type'         => 'title'
	],
	'postcode_yn' => [
		'title'         => __( 'Enable/Disable', 'korea-for-woocommerce' ),
		'label'         => __( 'Enable Korean Postcode', 'korea-for-woocommerce' ),
		'type'          => 'checkbox',
		'default'       => 'no',
	],
	'postcode_displaymode' => [
		'title'             => __( 'Display mode', 'korea-for-woocommerce' ),
		'description'       => __( 'Choose the display mode', 'korea-for-woocommerce' ),
		'type'              => 'select',
		'class'             => 'show_if_postcode',
		'default'           => 'overlay',
		'options'           => [
			'overlay' => __('Overlay','korea-for-woocommerce'),
			'embed'   => __('Embed','korea-for-woocommerce')
		],
		'custom_attributes' => [ 'required' => 'required' ]
	],
	'postcode_bgcolor' => [
		'title'             => __( 'Background Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#ececec'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#ececec',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_searchbgcolor' => [
		'title'             => __( 'Search Background Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#ffffff'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#ffffff',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_contentbgcolor' => [
		'title'             => __( 'Content Background Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#ffffff'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#ffffff',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_pagebgcolor' => [
		'title'             => __( 'Page Background Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#fafafa'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#fafafa',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_textcolor' => [
		'title'             => __( 'Text Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#333333'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#333333',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_querytxtcolor' => [
		'title'             => __( 'Query Text Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#222222'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#222222',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_postalcodetxtcolor' => [
		'title'             => __( 'Postal Code Text Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#fa4256'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#fa4256',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_emphtxtcolor' => [
		'title'             => __( 'Highlight Text Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#008bd3'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#008bd3',
		'custom_attributes' => [
			'required' => 'required'
		]
	],
	'postcode_outlinecolor' => [
		'title'             => __( 'Outline Color', 'korea-for-woocommerce' ),
		'description'       => sprintf(
			__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
			'#e0e0e0'
		),
		'type'              => 'color',
		'class'             => 'show_if_postcode',
		'default'           => '#e0e0e0',
		'custom_attributes' => [
			'required' => 'required'
		]
	]
]);