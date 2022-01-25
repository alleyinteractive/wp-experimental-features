<?php
/**
 * Initialize tests for this plugin.
 *
 * @package Experimental_Features
 */

use function Mantle\Testing\tests_add_filter;

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

\Mantle\Testing\install(
	function() {
		tests_add_filter( 'muplugins_loaded', 'experimental_features_manually_load_environment' );
	} 
);
