<?php
/**
 * The entry point for the WP Experimental Features plugin.
 *
 * Plugin Name: Experimental Features
 * Plugin URI:  https://github.com/alleyinteractive/wp-experimental-features
 * Description: Turn experimental features on and off via a checkbox in the admin.
 * Version:     1.2.0
 * Author:      Alley
 * Author URI:  https://alley.co
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

// Load files (todo: convert to alleyinteractive/composer-wordpress-autoloader).
require_once __DIR__ . '/autoloader.php';

// Initialize functionality.
Filter::init();
Options::init();
REST_API::init();

/**
 * Retrieve all experimental features.
 *
 * @return array<string, string>
 */
function get_features(): array {
	return Filter::flags();
}

/**
 * Retrieve all experimental features and their status.
 *
 * @return array<string, boolean>
 */
function get_features_with_status(): array {
	$flags = array_keys( Filter::flags() );

	return array_combine(
		$flags,
		array_map(
			fn ( $flag ) => get_feature_status( $flag ),
			$flags,
		),
	);
}

/**
 * Retrieve the status of a specific experimental feature.
 *
 * @param string $flag The feature flag to retrieve.
 * @param bool   $default The default status of the feature.
 * @return boolean
 */
function get_feature_status( string $flag, bool $default = false ): bool {
	/**
	 * Filter the status of an experimental feature.
	 *
	 * @param boolean $default The default status of the feature.
	 * @param string  $flag    The feature flag to retrieve.
	 */
	return (bool) apply_filters( 'experimental_features_flag', $default, $flag );
}
