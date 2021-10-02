<?php
/**
 * These teste make assertions against class WC_Korea_Test.
 *
 * @package WC_Korea_Test/Tests/Checkout
 */

use Greys\WooCommerce\Korea\Checkout\Helper;
use Greys\WooCommerce\Korea\Checkout\Phone;

/**
 * WC_Korea_Checkout_Test class.
 */
class Checkout extends WP_UnitTestCase {

	public function test_format_phone() {
		$this->assertEquals( '1588-0000', Helper::format_phone( '1588-0000' ) );
		$this->assertEquals( '02-345-6789', Helper::format_phone( '+82 23456789' ) );
		$this->assertEquals( '02-3456-7891', Helper::format_phone( '+82 23 456 7891' ) );
		$this->assertEquals( '02-2345-6789', Helper::format_phone( '822 2345 6789' ) );
		$this->assertEquals( '02-3000-5000', Helper::format_phone( '0230005000' ) );
		$this->assertEquals( '031-222-3333', Helper::format_phone( '82-0312-2233-33' ) );
		$this->assertEquals( '031-2222-3333', Helper::format_phone( '03122223333' ) );
		$this->assertEquals( '011-444-5555', Helper::format_phone( '011 444 5555' ) );
		$this->assertEquals( '010-6666-7777', Helper::format_phone( '82+1066667777' ) );
		$this->assertEquals( '0303-456-7890', Helper::format_phone( '03034567890' ) );
		$this->assertEquals( '0505-987-6543', Helper::format_phone( '050-5987-6543' ) );
		$this->assertEquals( '0303-4567-8900', Helper::format_phone( '030345678900' ) );
		$this->assertEquals( '0505-9876-5432', Helper::format_phone( '050-5987-65432' ) );
		$this->assertEquals( '070-7432-1000', Helper::format_phone( '0707-432-1000' ) );
	}

	public function test_is_valid_phone() {
		$this->assertTrue( Helper::is_valid_phone( '1588-0000' ) );
		$this->assertTrue( Helper::is_valid_phone( '02-345-6789' ) );
		$this->assertTrue( Helper::is_valid_phone( '+82-2-345-6789' ) );
		$this->assertTrue( Helper::is_valid_phone( '+82-02-2345-6789' ) );
		$this->assertTrue( Helper::is_valid_phone( '053-444-5555' ) );
		$this->assertTrue( Helper::is_valid_phone( '053-4444-5555' ) );
		$this->assertTrue( Helper::is_valid_phone( '011-444-5555' ) );
		$this->assertTrue( Helper::is_valid_phone( '010-4444-5555' ) );
		$this->assertTrue( Helper::is_valid_phone( '0303-4444-5555' ) );
		$this->assertTrue( Helper::is_valid_phone( '0505-4444-5555' ) );

		$this->assertFalse( Helper::is_valid_phone( '010-4444-55555' ) );
		$this->assertFalse( Helper::is_valid_phone( '010-1234-5678' ) );
		$this->assertFalse( Helper::is_valid_phone( '02-123-4567' ) );
		$this->assertFalse( Helper::is_valid_phone( '02-123456' ) );
		$this->assertFalse( Helper::is_valid_phone( '03-456-7890' ) );
		$this->assertFalse( Helper::is_valid_phone( '090-9876-5432' ) );
		$this->assertFalse( Helper::is_valid_phone( '0303-1111-5432' ) );
		$this->assertFalse( Helper::is_valid_phone( '0505-9876-543210' ) );
	}

	/**
	 * Test woocommerce_korea_checkout_phone_validation filter.
	 */
	public function test_phone_validation_filter() {
		$this->assertEquals( 10, has_action( 'woocommerce_after_checkout_validation', array( 'Phone', 'validate_phone' ) ) );

		add_filter( 'woocommerce_korea_checkout_phone_validation', '__return_false' );
		$this->assertFalse( has_action( 'woocommerce_after_checkout_validation', array( 'Phone', 'validate_phone' ) ) );
	}

	/**
	 * Test woocommerce_korea_checkout_phone_format filter.
	 */
	public function test_phone_format_filter() {
		$this->assertEquals( 10, has_action( 'woocommerce_checkout_create_order', array( 'Phone', 'format_phone' ) ) );
   		$this->assertEquals( 10, has_action( 'woocommerce_checkout_update_customer', array( 'Phone', 'format_phone' ) ) );

		add_filter( 'woocommerce_korea_checkout_phone_format', '__return_false' );
		$this->assertFalse( has_action( 'woocommerce_checkout_create_order', array( 'Phone', 'format_phone' ) ) );
		$this->assertFalse( has_action( 'woocommerce_checkout_update_customer', array( 'Phone', 'format_phone' ) ) );
	}

	/**
	 * Test get_country_locale.
	 */
	public function test_get_country_locale() {
		get_option( 'woocommerce_default_country', 'KR' );

		$countries = new WC_Countries();
		$locales = $countries->get_country_locale();

		$this->assertTrue( $locales['KR']['postcode']['required'] );
		$this->assertSame( 40, $locales['KR']['postcode']['priority'] );
		$this->assertSame( 30, $locales['KR']['country']['priority'] );
	}

}
