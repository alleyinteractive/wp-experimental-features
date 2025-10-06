<?php
/**
 * Experimental Features: Partials Test
 *
 * @package Experimental_Features
 * @subpackage Tests
 */

namespace Experimental_Features;

use Mantle\Testkit\TestCase;

use function Mantle\Support\Helpers\capture;

require_once ABSPATH . 'wp-admin/includes/admin.php';

/**
 * A class to test the functionality of the Partials class.
 *
 * @package Experimental_Features
 * @subpackage Tests
 */
class PartialsTest extends TestCase {

	/**
	 * Tests loading a partial.
	 */
	public function test_load() {
		$result = capture( fn () => Partials::load( 'options-page' ) );
		$this->assertNotFalse( strpos( $result, '<form id="experimental-features-config"' ) );
	}
}
