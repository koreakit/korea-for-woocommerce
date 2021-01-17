<?php
/**
 * WooCommerce Korea - Integration
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Integration class.
 *
 * @extends WC_Integration
 */
class WC_Korea_Integration extends WC_Integration {

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
		$filename = sanitize_title( $this->category ) . '.php';

		if ( ! file_exists( WC_KOREA_PLUGIN_PATH . '/includes/admin/settings/' . $filename ) ) {
			$this->form_fields = array();
			return;
		}

		$this->form_fields = require WC_KOREA_PLUGIN_PATH . '/includes/admin/settings/' . $filename;
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

		wp_enqueue_script( 'wc-korea-admin', plugins_url( 'assets/js/admin/settings.js', WC_KOREA_MAIN_FILE ), array(), WC_KOREA_VERSION, true );
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
