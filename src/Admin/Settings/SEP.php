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
 * Addons class.
 */
class SEP extends SettingsPage {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'sep';
		$this->label = __( 'SEP', 'korea-for-woocommerce' );

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
					'type'  => 'title',
					'id'    => 'woocommerce_korea_sep_settings',
				),
				array(
					'id'    => 'daum_shopping_ep',
					'title' => __( 'Daum Shopping', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
						/* translators: 1) Daum SEP link, 2) Daum Shopping Console link, 3) Daum Shopping Console name */
						__( 'Once enabled, you must add the following URL <code>%1$s</code> to your <a href="%2$s" target="_blank">%3$s</a>.', 'korea-for-woocommerce' ),
						add_query_arg( array( 'wc-sep' => 'daum' ), site_url( '/' ) ),
						'https://shopping.biz.daum.net/',
						__( 'Daum Shopping Console', 'korea-for-woocommerce' )
					),
					'label'    => __( 'Enable', 'korea-for-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
					'desc_tip' => true,
				),
				array(
					'id'     => 'naver_shopping_ep',
					'title'  => __( 'Naver Shopping', 'korea-for-woocommerce' ),
					'desc'   => sprintf(
						/* translators: 1) Naver SEP link, 2) Naver Shopping Console link, 3) Naver Shopping Console name */
						__( 'Once enabled, you must add the following URL <code>%1$s</code> to your <a href="%2$s" target="_blank">%3$s</a>.', 'korea-for-woocommerce' ),
						add_query_arg( array( 'wc-sep' => 'naver' ), site_url( '/' ) ),
						'https://adcenter.shopping.naver.com/main.nhn',
						__( 'Naver Shopping Console', 'korea-for-woocommerce' )
					),
					'label'    => __( 'Enable', 'korea-for-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
					'desc_tip' => true,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_korea_sep_settings',
				),
			);

		return apply_filters( 'woocommerce_korea_sep_settings', $settings );
	}

}
