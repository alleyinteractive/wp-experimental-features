<?php
/**
 * Initialize tests for this plugin.
 *
 * @package Experimental_Features
 */

// Map path to phpunit-polyfills for WordPress >= 5.9.
const WP_TESTS_PHPUNIT_POLYFILLS_PATH = __DIR__ . '/../vendor/yoast/phpunit-polyfills'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound

// Load Core's test suite.
$experimental_features_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $experimental_features_tests_dir ) {
	$experimental_features_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once $experimental_features_tests_dir . '/includes/functions.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

/**
 * Setup our environment.
 */
function experimental_features_manually_load_environment() {
	/*
	 * Tests won't start until the uploads directory is scanned, so use the
	 * lightweight directory from the test install.
	 *
	 * @see https://core.trac.wordpress.org/changeset/29120.
	 */
	add_filter(
		'pre_option_upload_path',
		function () {
			return ABSPATH . 'wp-content/uploads';
		}
	);

	// Load this plugin.
	require_once dirname( __DIR__ ) . '/wp-experimental-features.php';
}
tests_add_filter( 'muplugins_loaded', 'experimental_features_manually_load_environment' );

// Include core's bootstrap.
require $experimental_features_tests_dir . '/includes/bootstrap.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
