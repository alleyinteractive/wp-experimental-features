<?php
/**
 * Experimental Features: REST API Test
 *
 * @package Experimental_Features
 * @subpackage Tests
 */

namespace Experimental_Features;

use Mantle\Testkit\Test_Case;

/**
 * A class to test the functionality of the REST_API class.
 *
 * @package Experimental_Features
 * @subpackage Tests
 */
class Test_REST_API extends Test_Case {
	/**
	 * Test the REST API endpoint is disabled by default.
	 */
	public function test_rest_api_disabled_by_default() {
		$this->expectApplied( 'experimental_features_rest_api_enabled' )->once()->andReturnFalse();

		$this->get( '/wp-json/experimental-features/v1/features' )->assertNotFound();
	}

	/**
	 * Test the REST API endpoint is enabled when the filter is applied.
	 */
	public function test_rest_api_enabled() {
		add_filter( 'experimental_features_rest_api_enabled', '__return_true' );

		$this->expectApplied( 'experimental_features_rest_api_enabled' )->once()->andReturnTrue();
		$this->expectApplied( 'experimental_features_rest_api_flags' )->twice()->andReturnArray();

		// Register a feature.
		add_filter(
			'experimental_features_flags',
			fn () => [
				'feature' => 'Feature',
			]
		);

		$this->get( '/wp-json/experimental-features/v1/features' )
			->assertOk()
			->assertJsonStructure(
				[
					'feature' => [
						'label',
						'status',
					],
				]
			)
			->assertJsonPath( 'feature.status', false );

		// Turn on the feature flag.
		update_option( 'experimental_features_flags', [ 'feature' ] );

		$this->get( '/wp-json/experimental-features/v1/features' )->assertJsonPath( 'feature.status', true );
	}

	/**
	 * Test the REST API endpoint permission callback.
	 */
	public function test_rest_api_permission_callback() {
		add_filter( 'experimental_features_rest_api_enabled', '__return_true' );
		add_filter( 'experimental_features_rest_permission_callback', fn () => '__return_false' );

		$this->expectApplied( 'experimental_features_rest_permission_callback' )->once()->andReturn( '__return_false' );

		$this->get( '/wp-json/experimental-features/v1/features' )->assertUnauthorized();
	}
}
