<?php
/**
 * WooCommerce Korea - License Addons
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Addons_Licenses class.
 *
 * @extends WC_Korea_Addons
 */
class WC_Korea_Addons_Licenses extends WC_Korea_Addons {

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Outputs settings for all license sections.
	 *
	 * @since 1.0.0
	 */
	public function output() {
		$tab = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : null; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended

		if ( 'licenses' !== $tab ) {
			return;
		}

		// Licenses
		$licenses = apply_filters( 'wc_korea_plugin_license_settings', array() );

		/**
		 * Licenses page view.
		 *
		 * @uses $licenses
		 */
		include_once WC_KOREA_ABSPATH . '/includes/admin/views/html-admin-page-korea-licenses.php';
	}

	/**
	 * Registers settings using the WP settings API.
	 *
	 * @since 1.0.3
	 */
	public function register_settings() {
		// Licenses
		$licenses = apply_filters( 'wc_korea_plugin_license_settings', array() );

		add_settings_section(
			'wc_korea_plugin_license_settings_section',
			__return_null(),
			'__return_false',
			'wc_korea_plugin_license_settings_section'
		);

		foreach ( $licenses as $license ) {
			$license = wp_parse_args(
				$license,
				array(
					'section'       => 'wc-addons',
					'id'            => null,
					'desc'          => '',
					'name'          => '',
					'size'          => null,
					'options'       => '',
					'std'           => '',
					'min'           => null,
					'max'           => null,
					'step'          => null,
					'chosen'        => null,
					'multiple'      => null,
					'placeholder'   => null,
					'allow_blank'   => true,
					'readonly'      => false,
					'faux'          => false,
					'tooltip_title' => false,
					'tooltip_desc'  => false,
					'field_class'   => '',
				)
			);

			add_settings_field(
				$license['id'],
				str_replace( 'WooCommerce ', '', $license['name'] ),
				array( $this, 'license_key_callback' ),
				'wc_korea_plugin_license_settings_section',
				'wc_korea_plugin_license_settings_section',
				$license
			);

			register_setting( 'wc_korea_plugin_license_settings', $license['id'], array( $this, 'settings_sanitize' ) );
		}
	}

	/**
	 * Registers the license field callback for Software Licensing.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args arguments passed by the setting.
	 */
	public function license_key_callback( $args ) {

		$wc_korea_option = get_option( $args['id'] );
		$messages        = array();
		$license         = get_option( $args['options']['is_valid_license_option'] );

		if ( $wc_korea_option ) {
			$value = $wc_korea_option;
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		if ( ! empty( $license ) && is_object( $license ) ) {

			// activate_license 'invalid' on anything other than valid, so if there was an error capture it
			if ( false === $license->success ) {

				switch ( $license->error ) {

					case 'expired':
						$class      = 'expired';
						$messages[] = sprintf(
							/* translators: 1) expiration date, 2) renew license link start, 3) renew license link end */
							__( 'Your license key expired on %1$s. Please %2$srenew your license key%3$s.', 'korea-for-woocommerce' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license->expires, time() ) ),
							'<a href="https://greys.co/checkout/?edd_license_key=' . $value . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired" target="_blank">',
							'</a>'
						);
						$license_status = 'license-' . $class . '-notice';
						break;

					case 'revoked':
						$class      = 'error';
						$messages[] = sprintf(
							/* translators: 1) contact link start, 2) contact link end */
							__( 'Your license key has been disabled. Please %1$scontact support%2$s for more information.', 'korea-for-woocommerce' ),
							'<a href="https://greys.co/contact?utm_campaign=admin&utm_source=licenses&utm_medium=revoked" target="_blank">',
							'</a>'
						);
						$license_status = 'license-' . $class . '-notice';
						break;

					case 'missing':
						$class      = 'error';
						$messages[] = sprintf(
							/* translators: 1) purchase history link start, 2) purchase history link end */
							__( 'Invalid license. Please %1$svisit your account page%2$s and verify it.', 'korea-for-woocommerce' ),
							'<a href="https://greys.co/checkout/purchase-history/?utm_campaign=admin&utm_source=licenses&utm_medium=missing" target="_blank">',
							'</a>'
						);
						$license_status = 'license-' . $class . '-notice';
						break;

					case 'invalid':
					case 'site_inactive':
						$class      = 'error';
						$messages[] = sprintf(
							/* translators: 1) plugin name, 2) purchase history link start, 3) purchase history link end */
							__( 'Your %1$s is not active for this URL. Please %2$svisit your account page%3$s to manage your license key URLs.', 'korea-for-woocommerce' ),
							$args['name'],
							'<a href="https://greys.co/checkout/purchase-history/?utm_campaign=admin&utm_source=licenses&utm_medium=invalid" target="_blank">',
							'</a>'
						);
						$license_status = 'license-' . $class . '-notice';
						break;

					case 'item_name_mismatch':
						$class = 'error';
						/* translators: 1) plugin name */
						$messages[]     = sprintf( __( 'This appears to be an invalid license key for %s.', 'korea-for-woocommerce' ), $args['name'] );
						$license_status = 'license-' . $class . '-notice';
						break;

					case 'no_activations_left':
						$class = 'error';
						/* translators: 1) purchase history start, 2) purchase history end */
						$messages[]     = sprintf( __( 'Your license key has reached its activation limit. %1$sView possible upgrades%2$s now.', 'korea-for-woocommerce' ), '<a href="https://greys.co/checkout/purchase-history/">', '</a>' );
						$license_status = 'license-' . $class . '-notice';
						break;

					case 'license_not_activable':
						$class          = 'error';
						$messages[]     = __( 'The key you entered belongs to a bundle, please use the product specific license key.', 'korea-for-woocommerce' );
						$license_status = 'license-' . $class . '-notice';
						break;

					default:
						$class = 'error';
						$error = ! empty( $license->error ) ? $license->error : 'unknown_error';
						/* translators: 1) plugin name, 2) contact link end, 3) contact link end */
						$messages[]     = sprintf( __( 'There was an error with this license key: %1$s. Please %2$scontact our support team%3$s.', 'korea-for-woocommerce' ), $error, '<a href="https://greys.co/contact/">', '</a>' );
						$license_status = 'license-' . $class . '-notice';
						break;
				}
			} else {

				switch ( $license->license ) {

					case 'valid':
					default:
						$class      = 'valid';
						$now        = time();
						$expiration = strtotime( $license->expires, $now );

						if ( 'lifetime' === $license->expires ) {

							$messages[]     = __( 'License key never expires.', 'korea-for-woocommerce' );
							$license_status = 'license-lifetime-notice';

						} elseif ( $expiration > $now && $expiration - $now < ( DAY_IN_SECONDS * 30 ) ) {

							$messages[] = sprintf(
								/* translators: 1) expiration date, 2) renew license link start, 3) renew license link end */
								__( 'Your license key expires soon! It expires on %1$s. %2$sRenew your license key%3$s.', 'korea-for-woocommerce' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license->expires, time() ) ),
								'<a href="https://greys.co/checkout/?edd_license_key=' . $value . '&utm_campaign=admin&utm_source=licenses&utm_medium=renew" target="_blank">',
								'</a>'
							);
							$license_status = 'license-expires-soon-notice';

						} else {

							$messages[] = sprintf(
								/* translators: 1) expiration date */
								__( 'Your license key expires on %s.', 'korea-for-woocommerce' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license->expires, time() ) )
							);
							$license_status = 'license-expiration-date-notice';
						}
						break;
				}
			}
		} else {
			$class = 'empty';
			/* translators: 1) plugin name */
			$messages[]     = sprintf( __( 'To receive updates, please enter your valid %s license key.', 'korea-for-woocommerce' ), $args['name'] );
			$license_status = null;
		}

		$class .= ' ' . sanitize_html_class( $args['field_class'] );

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . sanitize_html_class( $size ) . '-text" id="' . sanitize_text_field( $args['id'] ) . '" name="' . sanitize_text_field( $args['id'] ) . '" value="' . esc_attr( $value ) . '"/>';

		if ( ( is_object( $license ) && 'valid' === $license->license ) || 'valid' === $license ) {
			$html .= '<input type="submit" class="button-secondary" name="' . $args['id'] . '_deactivate" value="' . __( 'Deactivate License', 'korea-for-woocommerce' ) . '"/>';
		}

		$html .= '<label for="' . sanitize_text_field( $args['id'] ) . '"> ' . wp_kses_post( $args['desc'] ) . '</label>';

		if ( ! empty( $messages ) ) {
			foreach ( $messages as $message ) {
				$html .= '<div class="wc-korea-license-data wc-korea-license-' . $class . ' ' . $license_status . '">';
				$html .= '<p>' . $message . '</p></div>';
			}
		}

		wp_nonce_field( sanitize_text_field( $args['id'] ) . '-nonce', sanitize_text_field( $args['id'] ) . '-nonce' );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Settings sanitization.
	 *
	 * Adds a settings error (for the updated message).
	 *
	 * @since 1.0.0
	 *
	 * @param array $input the value inputted in the field.
	 * @return string $input sanitized value
	 */
	public function settings_sanitize( $input = array() ) {
		$setting_types = array( 'text' );

		foreach ( $setting_types as $type ) {
			switch ( $type ) {
				case 'text':
					$input = sanitize_text_field( $input );
					break;
			}
		}

		add_settings_error(
			'wc-korea-license-settings',
			'',
			__( 'Settings updated.', 'korea-for-woocommerce' ),
			'updated'
		);

		return $input;
	}

}

new WC_Korea_Addons_Licenses();
