<?php
/**
 * WooCommerce Korea - Search Engine Page
 */

namespace Greys\WooCommerce\Korea\ShoppingEnginePage;

defined( 'ABSPATH' ) || exit;

use Greys\WooCommerce\Korea\ShoppingEnginePage\Daum;
use Greys\WooCommerce\Korea\ShoppingEnginePage\Naver;

/**
 * Init class.
 */
class Controller {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_filter( 'query_vars', array( __CLASS__, 'add_query_var' ) );
	}

}
