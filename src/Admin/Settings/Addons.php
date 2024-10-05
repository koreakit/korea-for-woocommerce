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

use const Greys\WooCommerce\Korea\Basename as BASENAME;
use Greys\WooCommerce\Korea\Abstracts\SettingsPage;

/**
 * Addons class.
 */
class Addons extends SettingsPage {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'addons';
		$this->label = __( 'Addons', 'korea-for-woocommerce' );

		add_action( 'woocommerce_korea_admin_field_addons', array( $this, 'addons_field' ) );

		parent::__construct();
	}

	/**
	 * Get own sections.
	 *
	 * @return array
	 */
	protected function get_own_sections() {
		$sections = array(
			'' => __( 'Addons', 'korea-for-woocommerce' )
		);

		return $sections;
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
					'id'    => 'woocommerce_korea_addons_settings',
					'title' => __( 'Addons', 'korea-for-woocommerce' ),
					'type'  => 'title',
				),

				array( 'type' => 'addons' ),

				array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_korea_addons_settings',
				),
			);

		return apply_filters( 'woocommerce_korea_addons_settings', $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $hide_save_button;

		$hide_save_button = true;
		parent::output();
	}

	/**
	 * Outputs addons.
	 */
	public function addons_field() {
		$addons = apply_filters(
			'woocommerce_korea_addons',
			array(
				'woocommerce-gateway-inicis' => [
					'title'       => __( 'KG INICIS Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'KG INICIS', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-inicis/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				],
				'woocommerce-gateway-kcp' => [
					'title'       => __( 'NHN KCP Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'NHN KCP', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-kcp/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				],
				'woocommerce-gateway-lguplus' => [
					'title'       => __( 'LG U+ Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'LG U+', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-lguplus/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				],
				'woocommerce-gateway-easypay' => [
					'title'       => __( 'EasyPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'EasyPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-easypay/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				],
				'woocommerce-gateway-nicepay'         => array(
					'title'       => __( 'NICEPAY Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'NICEPAY', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-nicepay/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-kakaopay'        => array(
					'title'       => __( 'KakaoPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'KakaoPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-kakaopay/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-naverpay'        => array(
					'title'       => __( 'NaverPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'NaverPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-naverpay/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-payco'           => array(
					'title'       => __( 'Payco Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'Payco', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-payco/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				),
				'woocommerce-gateway-tosspay'         => array(
					'title'       => __( 'TossPay Gateway', 'korea-for-woocommerce' ),
					'description' => sprintf(
						/* translators: 1) plugin description, 2) plugin name */
						__( 'Take payments via %1$s payment methods.', 'korea-for-woocommerce' ),
						__( 'TossPay', 'korea-for-woocommerce' )
					),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-gateway-tosspay/', 'korea-for-woocommerce' ),
					'price'       => __( '$79', 'korea-for-woocommerce' ),
				),
				'woocommerce-kakaotalk-notifications' => array(
					'title'       => __( 'KakaoTalk Notifications', 'korea-for-woocommerce' ),
					'description' => __( 'Send KakaoTalk order notifications to admins and customers for your WooCommerce store.', 'korea-for-woocommerce' ),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-kakaotalk-notifications/', 'korea-for-woocommerce' ),
					'price'       => __( '$249', 'korea-for-woocommerce' ),
				),
				'woocommerce-kras'                    => array(
					'title'       => __( 'Korean Authentication Services', 'korea-for-woocommerce' ),
					'description' => __( 'Korean Authentification Services (i-PIN, mobile phone) for WooCommerce', 'korea-for-woocommerce' ),
					'permalink'   => __( 'https://greys.co/plugin/woocommerce-kras/', 'korea-for-woocommerce' ),
					'price'       => __( '$249', 'korea-for-woocommerce' ),
				),
			)
		);

		?>
		<style type="text/css">
			@media only screen and (min-width: 600px) {
				.wc_addons_wrap {
					grid-template-columns: repeat(3, 1fr);
				}
			}

			@media only screen and (min-width: 768px) {
				.wc_addons_wrap {
					grid-template-columns: repeat(4, 1fr);
				}
			}

			.wc_addons_wrap .addon {
				height: 230px;
				position: relative;
				padding: 0;
				vertical-align: top;
				width: 100%;
				overflow: hidden;
				background: #fff;
				border-radius: 5px;
				outline: 1.5px solid #ddd;
			}

			.wc_addons_wrap .addon:hover {
				outline: 1.5px solid var(--wp-admin-theme-color)
			}

			/*
			.wc_addons_wrap .addon a {
				text-decoration: none;
				color: inherit;
				display: block;
				height: 100%;
			}
			*/

			.wc_addons_wrap .addon > .title {
				margin: 0;
				padding: 20px 12px;
				background: #fff;
			}

			.wc_addons_wrap .addon > .desc {
				margin: 0;
				padding: 12px;
				border-top: 1px solid #fff;
			}

			.wc_addons_wrap .addon .regular-text {
				width: 100% !important;
			}

			@media (max-width: 782px) {
				.wc_addons_wrap .addon .regular-text {
					height: 30px !important;
					min-height: auto;
					line-height: 1;
					font-size: 13px;
				}
			}

			.wc_addons_wrap .addon > .bottom {
				width: 100%;
				position: absolute;
				left: 0;
				bottom: 2px;
				box-sizing: border-box;
			}
		</style>
		<div class="wc_addons_wrap">
			<?php foreach ( $addons as $id => $addon ) { ?>
				<div class="addon">
					<h2 class="title"><?php echo wp_kses_post( $addon['title'] ); ?></h2>
					<?php if ( is_plugin_active( "{$id}/{$id}.php" ) ) { ?>
						<p class="desc">Your license key expires soon! It expires on %1$s. %2$sRenew your license key%3$s.</p>
					<?php } else { ?>
						<p class="desc"><?php echo wp_kses_post( $addon['description'] ); ?></p>
					<?php } ?>
					<div class="bottom" style="display: flex; align-items: center; width: 100%;">
						<?php if ( is_plugin_active( "{$id}/{$id}.php" ) ) { ?>
							<div style="display: block; width: 100%; padding: 10px 12px; background: #ffffff;">
								<a href="#" style="text-decoration: none; color: #1a1a1a; font-weight: 600;"><?php esc_html_e( 'Deactivate', 'korea-for-woocommerce' ); ?></a>
							</div>
						<?php } else { ?>
							<div style="display: block; width: 100%; padding: 10px 12px; background: #ffffff;">
								<a href="#" style="text-decoration: none; color: #1a1a1a; font-weight: 600;">From <?php echo $addon['price']; ?></a>
							</div>
							<div style="display: block; width: 100%; text-align: right; padding: 10px 12px; background: white;">
								<a href="mailto:contact@greys.co" class="button button-primary"><?php esc_html_e( 'Contact us', 'korea-for-woocommerce' ); ?></a>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

}
