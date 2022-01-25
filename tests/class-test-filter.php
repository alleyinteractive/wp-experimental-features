<?php
/**
 * Experimental Features: Filter Test
 *
 * @package Experimental_Features
 * @subpackage Tests
 */

namespace Experimental_Features;

use Mantle\Testing\Framework_Test_Case;

/**
 * A class to test the functionality of the Filter class.
 *
 * @package Experimental_Features
 * @subpackage Tests
 */
class Test_Filter extends Framework_Test_Case {
	/**
	 * Test the process of defining a filter flag and retrieving its value.
	 */
	public function test_feature_flag() {
		// Define the feature flags via filter.
		add_filter(
			'experimental_features_flags',
			function ( array $flags ) : array {
				$flags['my-cool-feature'] = 'My Cool Feature';
				return $flags;
			}
		);

		// Ensure the value of the feature flag is `false` before it is activated.
		$this->assertFalse( apply_filters( 'experimental_features_flag', false, 'my-cool-feature' ) );

		// Turn on the feature flag.
		update_option( 'experimental_features_flags', [ 'my-cool-feature' ] );

		// Ensure the value of the feature flag is `true` after it is activated.
		$this->assertTrue( apply_filters( 'experimental_features_flag', false, 'my-cool-feature' ) );
	}
}
