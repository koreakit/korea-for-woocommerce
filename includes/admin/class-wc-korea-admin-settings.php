<?php
/**
 * WooCommerce Korea - General
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

class WC_Korea_Admin_Settings extends WC_Integration {
	
	public function __construct() {
		$this->id                 = 'korea';
		$this->method_title       = __( 'Korea for WooCommerce', 'korea-for-woocommerce' );
		$this->method_description = __( 'All settings for Korea for WooCommerce can be adjusted here.', 'korea-for-woocommerce' );
		
		$this->init_form_fields();
		$this->init_settings();
		$this->includes();

		$this->postcode_theme = [
			'bgcolor'            => $this->get_option('postcode_bgcolor'),
			'searchbgcolor'      => $this->get_option('postcode_searchbgcolor'),
			'contentbgcolor'     => $this->get_option('postcode_contentbgcolor'),
			'pagebgcolor'        => $this->get_option('postcode_pagebgcolor'),
			'textcolor'          => $this->get_option('postcode_textcolor'),
			'querytxtcolor'      => $this->get_option('postcode_querytxtcolor'),
			'postalcodetxtcolor' => $this->get_option('postcode_postalcodetxtcolor'),
			'emphtxtcolor'       => $this->get_option('postcode_emphtxtcolor'),
			'outlinecolor'       => $this->get_option('postcode_outlinecolor')
		];

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function admin_scripts() {
		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		if ( isset( $_GET['section'] ) && 'korea' !== $_GET['section']
			&& isset( $_GET['tab'] ) && 'integration' !== $_GET['tab'] ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-admin', plugins_url('assets/js/admin/settings.js', WC_KOREA_MAIN_FILE), [], WC_KOREA_VERSION, true );
	}

	public function init_form_fields() {
		$this->form_fields = [
			'checkout' => [
				'title'         => __( 'Checkout', 'korea-for-woocommerce' ),
				'description'   => __( 'Enhance the checkout experience of your Korean customers.', 'korea-for-woocommerce' ),
				'type'          => 'title'
			],
			'postcode' => [
				'title'         => __( 'Korean Postcode Search', 'korea-for-woocommerce' ),
				'label'         => __( 'Enable', 'korea-for-woocommerce' ),
				'type'          => 'checkbox',
				'default'       => 'no',
			],
			'postcode_bgcolor' => [
				'title'         => __( 'Background Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#ececec',
			],
			'postcode_searchbgcolor' => [
				'title'         => __( 'Search Background Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#ffffff',
			],
			'postcode_contentbgcolor' => [
				'title'         => __( 'Content Background Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#ffffff',
			],
			'postcode_pagebgcolor' => [
				'title'         => __( 'Page Background Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#fafafa',
			],
			'postcode_textcolor' => [
				'title'         => __( 'Text Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#333333',
			],
			'postcode_querytxtcolor' => [
				'title'         => __( 'Query Text Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#222222',
			],
			'postcode_postalcodetxtcolor' => [
				'title'         => __( 'Postal Code Text Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#fa4256',
			],
			'postcode_emphtxtcolor' => [
				'title'         => __( 'Highlight Text Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#008bd3',
			],
			'postcode_outlinecolor' => [
				'title'         => __( 'Outline Color', 'korea-for-woocommerce' ),
				'type'          => 'color',
				'class'         => 'show_if_postcode',
				'default'       => '#e0e0e0',
			],
			'naver_analytics' => [
				'title'         => __( 'Naver Analytics', 'korea-for-woocommerce' ),
				'description'   => __( 'Naver Analytics is a free web analytics tool. Korea for WooCommerce connects your store to your Naver Analytics account to provide basic eCommerce and site analytics, using enhanced eCommerce tracking to provide valuable metrics on your store’s performance.', 'korea-for-woocommerce' ),
				'type'          => 'title'
			],
			'naver_analytics_id' => [
				'title'         => __( 'Naver Analytics ID', 'korea-for-woocommerce' ),
				'description'   => sprintf(
					__( 'You can get your Naver Analytics ID <a href="%s">here</a>.', 'korea-for-woocommerce' ),
					'https://analytics.naver.com/management/mysites.html'
				),
				'type'          => 'text',
				'default'       => ''
			],
			'sep' => [
				'title'         => __( 'Search Engine Page (SEP)', 'korea-for-woocommerce' ),
				'description'   => __( 'EP(=DB URL) is a summary of your shop, it contains a variety of information such as product name / price / delivery fee.<br />Once the service is enabled & configured, the Engine Page(=DB URL) shall be set in your Shopping Engine Page service.', 'korea-for-woocommerce' ),
				'type'          => 'title'
			],
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
			],
			'cs' => [
				'title'         => __( 'Chat Services', 'korea-for-woocommerce' ), // 1:1 문의
				'description'   => __( 'Chat services for customer support and online sales.<br />It\'s designed to help businesses manage communications and interactions with your customers.', 'korea-for-woocommerce' ),
				'type'          => 'title'
			],
			'kakaochannel' => [
				'title'         => __( 'Kakao Channel', 'korea-for-woocommerce' ),
				'description'   => sprintf(
					__( 'You can create your Kakao Channel <a href="%s">here</a>.', 'korea-for-woocommerce' ),
					'https://ch.kakao.com/register'
				),
				'type'          => 'checkbox',
				'default'       => 'no'
			],
			'kakaochannel_id' => [
				'title'         => __( 'Kakao Channel ID', 'korea-for-woocommerce' ),
				'type'          => 'text',
				'class'         => 'show_if_kakaochannel',
			],
			'kakaochannel_btntype' => [
				'title'         => __( 'Button Type', 'korea-for-woocommerce' ),
				'type'          => 'select',
				'class'         => 'show_if_kakaochannel',
				'options'       => [
					'add'      => __('Add','korea-for-woocommerce'),
					'consult'  => __('Consult','korea-for-woocommerce'),
					'question' => __('Question','korea-for-woocommerce')
				]
			],
			'kakaochannel_btnsize' => [
				'title'         => __( 'Button Size', 'korea-for-woocommerce' ),
				'type'          => 'select',
				'class'         => 'show_if_kakaochannel',
				'options'       => [
					'small'  => __('Small','korea-for-woocommerce'),
					'large'  => __('Large','korea-for-woocommerce')
				]
			],
			'kakaochannel_btncolor' => [
				'title'         => __( 'Button Color', 'korea-for-woocommerce' ),
				'type'          => 'select',
				'class'         => 'show_if_kakaochannel',
				'options'       => [
					'yellow'  => __('Yellow','korea-for-woocommerce'),
					'mono'    => __('Mono','korea-for-woocommerce')
				]
			],
			'navertalktalk' => [
				'title'         => __( 'Naver TalkTalk', 'korea-for-woocommerce' ),
				'description'   => sprintf(
					__( 'You can create your Naver TalkTalk <a href="%s">here</a>.', 'korea-for-woocommerce' ),
					'https://partner.talk.naver.com/register'
				),
				'type'          => 'checkbox',
				'default'       => 'no'
			],
			'navertalktalk_id' => [
				'title'         => __( 'Naver TalkTalk ID', 'korea-for-woocommerce' ),
				'type'          => 'text',
				'class'         => 'show_if_navertalktalk',
			],
		];
	}

}