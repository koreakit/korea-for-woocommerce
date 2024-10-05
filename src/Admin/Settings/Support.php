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
 * Support class.
 */
class Support extends SettingsPage {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'support';
		$this->label = __( 'Support', 'korea-for-woocommerce' );

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
					'id'    => 'kakaochannel',
					'title' => __( 'Kakao Channel', 'korea-for-woocommerce' ),
					'desc'  => __( 'Once enabled, you can copy and paste the following shortcode <code>[kakaochannel]</code> directly into the page, post or widget where you\'d like the button to show up.', 'korea-for-woocommerce' ),
					'type'  => 'title',
				),
				array(
					'id'    => 'kakaochannel_yn',
					'title' => __( 'Enable/Disable', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
						/* translators: Kakao Channel registration link */
						__( 'You can create your Kakao Channel <a href="%s">here</a>.', 'korea-for-woocommerce' ),
						'https://ch.kakao.com/register'
					),
					'label'   => __( 'Enable Kakao Channel', 'korea-for-woocommerce' ),
					'type'    => 'checkbox',
					'default' => 'no',
				),
				array(
					'id'    => 'kakaochannel_appkey',
					'title' => __( 'App Key', 'korea-for-woocommerce' ),
					'type'  => 'text',
					'class' => 'show_if_kakaochannel',
				),
				array(
					'id'    => 'kakaochannel_id',
					'title' => __( 'Channel ID', 'korea-for-woocommerce' ),
					'type'  => 'text',
					'class' => 'show_if_kakaochannel',
				),
				array(
					'id'      => 'kakaochannel_btntype',
					'title'   => __( 'Button Type', 'korea-for-woocommerce' ),
					'type'    => 'select',
					'class'   => 'show_if_kakaochannel',
					'options' => array(
						'add'      => __( 'Add', 'korea-for-woocommerce' ),
						'consult'  => __( 'Consult', 'korea-for-woocommerce' ),
						'question' => __( 'Question', 'korea-for-woocommerce' ),
					),
				),
				array(
					'id'      => 'kakaochannel_btnsize',
					'title'   => __( 'Button Size', 'korea-for-woocommerce' ),
					'type'    => 'select',
					'class'   => 'show_if_kakaochannel',
					'options' => array(
						'small' => __( 'Small', 'korea-for-woocommerce' ),
						'large' => __( 'Large', 'korea-for-woocommerce' ),
					),
				),
				array(
					'id'      => 'kakaochannel_btncolor',
					'title'   => __( 'Button Color', 'korea-for-woocommerce' ),
					'type'    => 'select',
					'class'   => 'show_if_kakaochannel',
					'options' => array(
						'yellow' => __( 'Yellow', 'korea-for-woocommerce' ),
						'mono'   => __( 'Mono', 'korea-for-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_korea_support_kakaochannel_settings',
				),
				array(
					'id'    => 'navertalktalk',
					'title' => __( 'Naver TalkTalk', 'korea-for-woocommerce' ),
					'desc'  => __( 'Once enabled, you can copy and paste the following shortcode <code>[navertalktalk]</code> directly into the page, post or widget where you\'d like the button to show up.', 'korea-for-woocommerce' ),
					'type'  => 'title',
				),
				array(
					'id'    => 'navertalktalk_yn',
					'title' => __( 'Enable/Disable', 'korea-for-woocommerce' ),
					'desc'  => sprintf(
						/* translators: Naver TalkTalk registration link */
						__( 'You can create your Naver TalkTalk <a href="%s">here</a>.', 'korea-for-woocommerce' ),
						'https://partner.talk.naver.com/register'
					),
					'label'   => __( 'Enable Naver TalkTalk', 'korea-for-woocommerce' ),
					'type'    => 'checkbox',
					'default' => 'no',
				),
				array(
					'id'    => 'navertalktalk_id',
					'title' => __( 'TalkTalk ID (PC)', 'korea-for-woocommerce' ),
					'type'  => 'text',
					'desc'  => __( 'Enter an ID for Naver TalkTalk (PC)', 'korea-for-woocommerce' ),
					'class' => 'show_if_navertalktalk',
				),
				array(
					'id'    => 'navertalktalk_mobile_id',
					'title' => __( 'TalkTalk ID (mobile)', 'korea-for-woocommerce' ),
					'type'  => 'text',
					'desc'  => __( 'Enter an ID for Naver TalkTalk (mobile)', 'korea-for-woocommerce' ),
					'class' => 'show_if_navertalktalk',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_korea_support_navertalktalk_settings',
				),
			);

		return apply_filters( 'woocommerce_korea_support_settings', $settings );
	}

}
