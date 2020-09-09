<?php
/**
 * Abstract class that will be inherited by all korean payment methods.
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Payment_Gateway abstract class.
 *
 * @extends WC_Payment_Gateway
 */
abstract class WC_Korea_Payment_Gateway extends WC_Payment_Gateway {

	/**
	 * Returns all supported currencies for the payment method.
	 *
	 * @return array
	 */
	public function get_supported_currencies() {
		return apply_filters(
			'wc_gateway_' . $this->id . '_supported_currencies',
			array( 'KRW' )
		);
	}

	/**
	 * Check if the gateway is available for use.
	 *
	 * @return bool
	 */
	public function is_available() {
		if ( ! in_array( get_woocommerce_currency(), $this->get_supported_currencies(), true ) ) {
			return false;
		}

		return parent::is_available();
	}

	/**
	 * Generate Select2 HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @return string
	 */
	public function generate_select2_html( $key, $data ) {
		$defaults = array(
			'title'       => '',
			'disabled'    => false,
			'class'       => '',
			'css'         => '',
			'placeholder' => '',
			'type'        => 'text',
			'desc_tip'    => false,
			'description' => '',
			'multiple'    => false,
			'options'     => array(),
		);

		$data        = wp_parse_args( $data, $defaults );
		$field_key   = $this->get_field_key( $key );
		$field_key  .= true === $data['multiple'] ? '[]' : '';
		$is_multiple = true === $data['multiple'] ? 'multiple="multiple"' : '';

		ob_start();
		?>
		<tr valign="top" class="<?php echo esc_attr( $data['class'] ); ?>">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<select id="<?php echo esc_attr( $field_key ); ?>" class="wc-enhanced-select" <?php echo $is_multiple; ?> name="<?php echo esc_attr( $field_key ); ?>" data-placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php foreach ( (array) $data['options'] as $option_key => $option_value ) { ?>
							<option value="<?php echo esc_attr( $option_key ); ?>" <?php echo ( ( in_array( $option_key, explode( ':', $this->get_option( $key ) ) ) ) ? 'selected="selected"' : '' ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict ?>>
								<?php echo esc_html( $option_value ); ?></option>
						<?php } ?>
					</select>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate No Interest HTML.
	 *
	 * @param string $key Field key.
	 * @param array  $data Field data.
	 * @return string
	 */
	public function generate_nointerest_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'       => '',
			'disabled'    => false,
			'class'       => '',
			'css'         => '',
			'placeholder' => '',
			'type'        => 'text',
			'desc_tip'    => false,
			'description' => '',
			'options'     => array(),
		);

		$data               = wp_parse_args( $data, $defaults );
		$value              = explode( ',', esc_attr( $this->get_option( $key ) ) );
		$noint_installments = array();
		foreach ( $value as $v ) {
			list( $cardcode, $installments ) = explode( '-', $v );
			$noint_installments[ $cardcode ] = $installments;
		}

		ob_start();

		?>
		<tr valign="top" class="<?php echo esc_attr( $data['class'] ); ?>">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			</th>
			<td class="forminp">
				<?php echo $this->get_description_html( $data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<fieldset>
					<table>
						<thead>
							<tr>
								<th style="padding:15px 0px"><?php esc_html_e( 'Card company', 'korea-for-woocommerce' ); ?></th>
								<th style="padding:15px 0px"><?php esc_html_e( 'Interest-free installments', 'korea-for-woocommerce' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data['cards'] as $cardcode => $cardname ) { ?>
								<tr>
									<td width="20%" class="forminp" style="padding:0; margin:0;">
										<i><?php echo wp_kses_post( wptexturize( $cardname ) ); ?></i>
									</td>
									<td width="80%" class="forminp" style="padding:0; margin:0;">
										<select class="noint-installments wc-enhanced-select" id="<?php echo esc_attr( $field_key . '[' . $cardcode . ']' ); ?>" name="<?php echo esc_attr( $field_key . '[' . $cardcode . '][]' ); ?>" multiple="multiple">
											<?php foreach ( (array) $data['installments'] as $installment => $label ) { ?>
												<option value="<?php echo esc_attr( $installment ); ?>" <?php echo isset( $noint_installments[ $cardcode ] ) && in_array( $installment, (array) explode( ':', $noint_installments[ $cardcode ] ) ) ? 'selected="selected"' : ''; // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict ?>>
													<?php echo esc_html( $label ); ?>
												</option>
											<?php } ?>
										</select>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

}
