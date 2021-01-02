<?php
/**
 * WooCommerce Korea
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

return apply_filters(
	'wc_korea_general_settings',
	array(
		'postcode'                    => array(
			'title' => __( 'Korean Postcode', 'korea-for-woocommerce' ),
			'type'  => 'title',
		),
		'postcode_yn'                 => array(
			'title'   => __( 'Enable/Disable', 'korea-for-woocommerce' ),
			'label'   => __( 'Enable Korean Postcode', 'korea-for-woocommerce' ),
			'type'    => 'checkbox',
			'default' => 'no',
		),
		'postcode_displaymode'        => array(
			'title'             => __( 'Display mode', 'korea-for-woocommerce' ),
			'description'       => __( 'Choose the display mode', 'korea-for-woocommerce' ),
			'type'              => 'select',
			'class'             => 'show_if_postcode',
			'default'           => 'embed',
			'options'           => array(
				'embed'   => __( 'Embed', 'korea-for-woocommerce' ),
				'overlay' => __( 'Overlay', 'korea-for-woocommerce' )
			),
			'custom_attributes' => array( 'required' => 'required' ),
		),
		'postcode_bgcolor'            => array(
			'title'             => __( 'Background Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#ececec'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#ececec',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_searchbgcolor'      => array(
			'title'             => __( 'Search Background Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#ffffff'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#ffffff',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_contentbgcolor'     => array(
			'title'             => __( 'Content Background Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#ffffff'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#ffffff',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_pagebgcolor'        => array(
			'title'             => __( 'Page Background Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#fafafa'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#fafafa',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_textcolor'          => array(
			'title'             => __( 'Text Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#333333'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#333333',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_querytxtcolor'      => array(
			'title'             => __( 'Query Text Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#222222'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#222222',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_postalcodetxtcolor' => array(
			'title'             => __( 'Postal Code Text Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#fa4256'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#fa4256',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_emphtxtcolor'       => array(
			'title'             => __( 'Highlight Text Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#008bd3'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#008bd3',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
		'postcode_outlinecolor'       => array(
			'title'             => __( 'Outline Color', 'korea-for-woocommerce' ),
			'description'       => sprintf(
				/* translators: 1) hexadecimal color */
				__( 'Default value: <code>%s</code>', 'korea-for-woocommerce' ),
				'#e0e0e0'
			),
			'type'              => 'color',
			'class'             => 'show_if_postcode',
			'default'           => '#e0e0e0',
			'custom_attributes' => array(
				'required' => 'required',
			),
		),
	)
);
