<?php
/**
 * WooCommerce Korea - Integration
 *
 * @package WC_Korea
 * @author  @jgreys
 */

namespace Greys\WooCommerce\Korea\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use const Greys\WooCommerce\Korea\MainFile as MAIN_FILE;
use const Greys\WooCommerce\Korea\Basename as BASENAME;
use const Greys\WooCommerce\Korea\Version as VERSION;

/**
 * Integration class.
 */
class Integration extends \WC_Integration {

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->id                 = 'korea';
		$this->method_title       = __( 'Korea for WooCommerce', 'korea-for-woocommerce' );
		$this->method_description = '';
		$this->category           = ! empty( $_GET['cat'] ) ? sanitize_key( wp_unslash( $_GET['cat'] ) ) : 'general'; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// JS Library.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Actions.
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		switch ( $this->category ) {
			case 'advanced':
				$this->form_fields = apply_filters(
					'woocommerce_korea_advanced_settings',
					array(
						'naver_analytics' => array(
							'title'       => __( 'Naver Analytics ID', 'korea-for-woocommerce' ),
							'description' => sprintf(
								/* translators: 1) Naver Analytics link */
								__( 'You can get your Naver Analytics ID <a href="%s">here</a>.', 'korea-for-woocommerce' ),
								'https://analytics.naver.com/management/mysites.html'
							),
							'type'        => 'text',
							'default'     => '',
						),
					)
				);
				break;

			case 'sep':
				$this->form_fields = apply_filters(
					'woocommerce_korea_sep_settings',
					array(
						'daum_shopping_ep'  => array(
							'title'       => __( 'Daum Shopping', 'korea-for-woocommerce' ),
							'description' => sprintf(
								/* translators: 1) Daum SEP link, 2) Daum Shopping Console link, 3) Daum Shopping Console name */
								__( 'Once enabled, you must add the following URL <code>%1$s</code> to your <a href="%2$s" target="_blank">%3$s</a>.', 'korea-for-woocommerce' ),
								add_query_arg( array( 'wc-sep' => 'daum' ), site_url( '/' ) ),
								'https://shopping.biz.daum.net/',
								__( 'Daum Shopping Console', 'korea-for-woocommerce' )
							),
							'label'       => __( 'Enable', 'korea-for-woocommerce' ),
							'type'        => 'checkbox',
							'default'     => 'no',
						),
						'naver_shopping_ep' => array(
							'title'       => __( 'Naver Shopping', 'korea-for-woocommerce' ),
							'description' => sprintf(
								/* translators: 1) Naver SEP link, 2) Naver Shopping Console link, 3) Naver Shopping Console name */
								__( 'Once enabled, you must add the following URL <code>%1$s</code> to your <a href="%2$s" target="_blank">%3$s</a>.', 'korea-for-woocommerce' ),
								add_query_arg( array( 'wc-sep' => 'naver' ), site_url( '/' ) ),
								'https://adcenter.shopping.naver.com/main.nhn',
								__( 'Naver Shopping Console', 'korea-for-woocommerce' )
							),
							'label'       => __( 'Enable', 'korea-for-woocommerce' ),
							'type'        => 'checkbox',
							'default'     => 'no',
						),
					)
				);
				break;

			case 'support':
				$this->form_fields = apply_filters(
					'woocommerce_korea_support_settings',
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
				break;

			case 'general':
			default:
				$this->form_fields = apply_filters(
					'woocommerce_korea_general_settings',
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
				break;
		}
	}

	/**
	 * Load admin scripts
	 */
	public function admin_scripts() {
		if ( 'woocommerce_page_wc-settings' !== get_current_screen()->id ) {
			return;
		}

		$section = isset( $_GET['section'] ) && ! empty( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : null; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended
		$tab = isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : null; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended
		if ( 'korea' !== $section && 'integration' !== $tab ) {
			return;
		}

		wp_enqueue_script( 'wc-korea-admin', plugins_url( 'assets/js/admin/settings.js', MAIN_FILE ), array(), VERSION, true );
	}

	/**
	 *  Get categories
	 *
	 *  @return array
	 */
	public function get_categories() {
		return array(
			'general'  => __( 'General', 'korea-for-woocommerce' ),
			'sep'      => __( 'Search Engine Page', 'korea-for-woocommerce' ),
			'support'  => __( 'Support', 'korea-for-woocommerce' ),
			'advanced' => __( 'Advanced', 'korea-for-woocommerce' ),
		);
	}

	/**
	 *  Output categories
	 */
	public function output_categories() {
		$categories = $this->get_categories();

		if ( empty( $categories ) || 1 === count( $categories ) ) {
			return;
		}

		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $categories );
		foreach ( $categories as $id => $label ) {
			$args = array(
				'page'    => 'wc-settings',
				'tab'     => 'integration',
				'section' => $this->id,
			);

			if ( 'general' !== $id ) {
				$args['cat'] = sanitize_title( $id );
			}

			?>
			<li>
				<a href="<?php echo esc_url( add_query_arg( $args, admin_url( 'admin.php' ) ) ); ?>" class="<?php echo esc_attr( $this->category === $id ? 'current' : '' ); ?>">
					<?php echo wp_kses_post( wptexturize( $label ) ); ?>
				</a>
				<?php echo ( end( $array_keys ) === $id ? '' : '|' ); ?>
			</li>
			<?php
		}
		echo '</ul><br class="clear" />';
	}

	/**
	 * Output the admin options table
	 */
	public function admin_options() {
		echo '<h2>' . esc_html( $this->get_method_title() ) . '</h2>';
		$this->output_categories();
		echo wp_kses_post( wpautop( $this->get_method_description() ) );
		echo '<div><input type="hidden" name="section" value="' . esc_attr( $this->id ) . '" /></div>';
		echo '<table class="form-table">' . $this->generate_settings_html( $this->get_form_fields(), false ) . '</table>'; // @codingStandardsIgnoreLine WordPress.Security.EscapeOutput.OutputNotEscaped
	}

}
