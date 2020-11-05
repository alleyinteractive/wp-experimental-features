<?php
/**
 * Experimental Features includes: Filter class
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

/**
 * Filter class. Centralizes definitions and documentation of filters.
 *
 * @package Experimental_Features
 */
class Filter {
	/**
	 * Defines a filter function for the capability required to turn experimental features on and off.
	 *
	 * @return string The capability required to turn experimental features on and off. Defaults to `manage_options`.
	 */
	public static function capability() : string {
		/**
		 * Filters the capability required to turn experimental features on and off.
		 *
		 * @param string $capability The capability required to turn experimental features on and off. Defaults to `manage_options`.
		 */
		return apply_filters( 'experimental_features_capability', 'manage_options' );
	}

	/**
	 * Filters the value of an individual feature flag.
	 *
	 * This filter should be used in plugins and themes to check if a
	 * particular feature flag is enabled or not.
	 *
	 * @param bool   $default The default value for the feature flag. Typically, this is false.
	 * @param string $slug    The feature flag slug to look up.
	 *
	 * @return bool Whether the given feature flag is active or not.
	 */
	public static function filter_experimental_features_flag( bool $default, string $slug ) : bool {
		$option_value = get_option( 'experimental_features_flags', [] );

		return ! empty( $option_value )
			&& is_array( $option_value )
			&& in_array( $slug, $option_value, true )
				? true
				: $default;
	}

	/**
	 * Defines a filter function for the available feature flags.
	 *
	 * @return array The available feature flags.
	 */
	public static function flags() : array {
		/**
		 * Filters the available feature flags.
		 *
		 * Use this filter to define feature flags for your site. This filter
		 * should return an associative array where the feature slug is the key
		 * and the feature label is the value.
		 *
		 * @param array $flags Available feature flags for this site. Keys are feature slugs, values are feature labels.
		 */
		return apply_filters( 'experimental_features_flags', [] );
	}

	/**
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init() {
		add_filter(
			'experimental_features_flag',
			[ self::class, 'filter_experimental_features_flag' ],
			10,
			2
		);
	}
}
