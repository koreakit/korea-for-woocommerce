<?php
/**
 * WooCommerce Korea - Search Engine Page
 */

namespace Greys\WooCommerce\Korea\SEP;

defined( 'ABSPATH' ) || exit;

/**
 * Init class.
 */
class Init {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_filter( 'query_vars', array( __CLASS__, 'wc_sep_query_var' ) );
	}

	/**
	 * Add SEP query vars
	 *
	 * @param array $query_vars The array of available query variables.
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
	 *
	 * @return array
	 */
	public static function wc_sep_query_var( $query_vars ) {
		$query_vars[] = 'wc-sep';
		return $query_vars;
	}

}

return new Init();
