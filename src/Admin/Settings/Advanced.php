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
 * Advanced class.
 */
class Advanced extends SettingsPage {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'advanced';
		$this->label = __( 'Advanced', 'korea-for-woocommerce' );

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
					'id'    => 'woocommerce_korea_advanced_settings',
				),

				array(
					'id'    => 'naver_analytics_id',
					'title' => __( 'Naver Analytics ID', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
						/* translators: 1) Naver Analytics link */
						__( 'You can get your Naver Analytics ID <a href="%s">here</a>.', 'korea-for-woocommerce' ),
						'https://analytics.naver.com/management/mysites.html'
					),
					'type'        => 'text',
					'default'     => '',
				),

				array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_korea_advanced_settings',
				),
			);

		return apply_filters( 'woocommerce_korea_advanced_settings', $settings );
	}
}
