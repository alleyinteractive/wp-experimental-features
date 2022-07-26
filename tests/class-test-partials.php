<?php
/**
 * Experimental Features: Partials Test
 *
 * @package Experimental_Features
 * @subpackage Tests
 */

namespace Experimental_Features;

use Mantle\Testkit\Test_Case;

require_once ABSPATH . 'wp-admin/includes/admin.php';

/**
 * A class to test the functionality of the Partials class.
 *
 * @package Experimental_Features
 * @subpackage Tests
 */
class Test_Partials extends Test_Case {

	/**
	 * Tests loading a partial.
	 */
	public function test_load() {
		ob_start();
		Partials::load( 'options-page' );
		$result = ob_get_clean();
		$this->assertNotFalse( strpos( $result, '<form id="experimental-features-config"' ) );
	}
}
