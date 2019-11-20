<?php
/**
 * Abstract class that will be inherited by all korean payment methods.
 *
 * @extends WC_Payment_Gateway
 */

defined( 'ABSPATH' ) || exit;

abstract class WC_Korea_Payment_Gateway extends WC_Payment_Gateway {

	/**
	 * Check if the gateway is available for use.
	 *
	 * @return bool
	 */
	public function is_available() {
		if ( ! in_array(get_woocommerce_currency(), ['KRW']) ) {
			return FALSE;
		}

		if ( $this->testmode && get_current_user_id() != $this->testaccount && !current_user_can('manage_woocommerce') ) {
			return FALSE;
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
		$defaults = [
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'multiple'			=> false,
			'options'           => []
		];

		$data       = wp_parse_args( $data, $defaults );
		$field_key  = $this->get_field_key( $key );
		$field_key .= $data['multiple'] == true ? '[]' : '';
		
		ob_start();
		?>
		<tr valign="top" class="<?php echo esc_attr( $data['class'] ); ?>">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<select id="<?php echo esc_attr( $field_key ); ?>" class="wc-enhanced-select" <?php if ( $data['multiple'] == true ) { echo 'multiple="multiple"'; } ?> name="<?php echo esc_attr( $field_key ); ?>" data-placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php disabled( $data['disabled'], true ); // WPCS: XSS ok. ?>>
						<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
							<option value="<?php echo esc_attr( $option_key ); ?>" <?php if ( in_array( $option_key, explode(':', $this->get_option( $key ))) ) { echo 'selected="selected"'; } ?>><?php echo esc_attr( $option_value ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
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
		$defaults  = [
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'options'           => [],
		];

		$data  = wp_parse_args( $data, $defaults );
		$cards = explode(',', esc_attr( $this->get_option( $key ) ) );

		ob_start();
		?>
		<tr valign="top" class="<?php echo esc_attr( $data['class'] ); ?>">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?> <?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok. ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<ul id="<?php echo esc_attr( $field_key ); ?>">
						<?php $i = 1; foreach ( $cards as $card ) { list($cardcode, $quotabase) = explode('-', $card); ?>
							<li>
								<select class="wc-inicis-nointerest-card" name="<?php echo esc_attr( $field_key ); ?>[card][]">
									<?php foreach ( (array) $data['cards'] as $option_key => $option_value ) : ?>
										<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( (string) $option_key, esc_attr( $cardcode ) ); ?>><?php echo esc_attr( $option_value ); ?></option>
									<?php endforeach; ?>
								</select>

								<select class="wc-inicis-nointerest-quotabase wc-enhanced-select" name="<?php echo esc_attr( $field_key ); ?>[quotabase][<?php echo esc_attr($i); ?>][]" multiple="multiple">
									<?php foreach ( (array) $data['quotabase'] as $option_key => $option_value ) : ?>
										<option value="<?php echo esc_attr( $option_key ); ?>" <?php if ( in_array($option_key, (array) explode(':', $quotabase)) ) { echo 'selected="selected"'; } ?>><?php echo esc_attr( $option_value ); ?></option>
									<?php endforeach; ?>
								</select>
								
								<?php if ( $i == 1 ) { ?>
									<a class="button-secondary button-nointerest-card-add" href="javascript:;" style="font-weight:bold;">+</a>
								<?php } else { ?>
									<a class="button-secondary button-nointerest-card-remove" href="javascript:;" style="color:red; font-weight:bold;">-</a>
								<?php } ?>
							</li>
						<?php ++$i; } ?>
					</ul>
				</fieldset>
				<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

}