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
}
