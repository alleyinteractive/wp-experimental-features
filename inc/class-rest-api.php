<?php
/**
 * REST_API class file
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

/**
 * REST API Integration
 *
 * @package Experimental_Features
 */
class REST_API {
	/**
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init(): void {
		add_action(
			'rest_api_init',
			[ self::class, 'action__rest_api_init' ]
		);
	}

	/**
	 * Registers the REST API route.
	 */
	public static function action__rest_api_init() {
		/**
		 * Filter if the REST API route should be registered. Default is false.
		 *
		 * @param bool $register_rest_route Whether to register the REST API route.
		 */
		if ( ! apply_filters( 'experimental_features_rest_api_enabled', false ) ) {
			return;
		}

		register_rest_route(
			'experimental-features/v1',
			'/features',
			[
				'methods'             => 'GET',
				'callback'            => [ self::class, 'handle_request' ],
				/**
				 * Filter the permission callback for the REST API route.
				 *
				 * @param callable $permission_callback The callback function to check if the request has permission to read the features.
				 */
				'permission_callback' => apply_filters( 'experimental_features_rest_permission_callback', '__return_true' ),
				'schema'              => [
					'description' => __( 'Retrieve the status of all experimental features.', 'experimental-features' ),
					'type'        => 'array',
					'items'       => [
						'type'       => 'object',
						'properties' => [
							'label'  => [
								'type'        => 'string',
								'description' => __( 'The label of the feature.', 'experimental-features' ),
							],
							'status' => [
								'type'        => 'boolean',
								'description' => __( 'The status of the feature.', 'experimental-features' ),
							],
						],
					],
				],
			],
		);
	}

	/**
	 * Handles the REST API request.
	 */
	public static function handle_request(): array {
		/**
		 * Filter the experimental features flags that are available in the REST API.
		 *
		 * @param array $flags The experimental features flags.
		 */
		$features = (array) apply_filters( 'experimental_features_rest_api_flags', Filter::flags() );

		return array_combine(
			array_keys( $features ),
			array_map(
				fn ( $flag, $label ) => [
					'label'  => $label,
					/**
					 * Filter the status of an experimental feature.
					 *
					 * @param boolean $default The default status of the feature.
					 * @param string  $flag    The feature flag to retrieve.
					 */
					'status' => (bool) apply_filters( 'experimental_features_flag', false, $flag ),
				],
				array_keys( $features ),
				$features,
			),
		);
	}
}
