<?php
/**
 * Healy Support Center - Settings
 *
 * @package WooCommerce\Admin
 */

namespace Greys\WooCommerce\Korea\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Greys\WooCommerce\Korea\Abstracts\SettingsPage;

/**
 * General class.
 */
class General extends SettingsPage {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'korea-for-woocommerce' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {
		$settings =
			array(
				array(
					'id'    => 'postcode',
					'title' => __( 'Postcode Finder', 'korea-for-woocommerce' ),
					'desc'  => __( 'Helps the users to experience in autofill billing and / or shipping address field.', 'korea-for-woocommerce' ),
					'type'  => 'title',
				),
				array(
					'id'      => 'postcode_yn',
					'title'   => __( 'Enable/Disable', 'korea-for-woocommerce' ),
					'label'   => __( 'Enable Korean Postcode', 'korea-for-woocommerce' ),
					'type'    => 'checkbox',
					'default' => 'no',
				),
				array(
					'id'      => 'postcode_displaymode',
					'title'   => __( 'Display mode', 'korea-for-woocommerce' ),
					'desc'    => __( 'Choose the display mode', 'korea-for-woocommerce' ),
					'type'    => 'select',
					'class'   => 'show_if_postcode',
					'default' => 'embed',
					'options' => array(
						'embed'   => __( 'Embed', 'korea-for-woocommerce' ),
						'overlay' => __( 'Overlay', 'korea-for-woocommerce' )
					),
					'custom_attributes' => array( 'required' => 'required' ),
				),
				array(
					'id'    => 'postcode_bgcolor',
					'title' => __( 'Background Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_searchbgcolor',
					'title' => __( 'Search Background Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_contentbgcolor',
					'title' => __( 'Content Background Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_pagebgcolor',
					'title' => __( 'Page Background Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_textcolor',
					'title' => __( 'Text Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_querytxtcolor',
					'title' => __( 'Query Text Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_postalcodetxtcolor',
					'title' => __( 'Postal Code Text Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_emphtxtcolor',
					'title' => __( 'Highlight Text Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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
				array(
					'id'    => 'postcode_outlinecolor',
					'title' => __( 'Outline Color', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
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

				array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_korea_general_settings',
				),
			);

		return apply_filters( 'woocommerce_korea_general_settings', $settings );
	}

}
