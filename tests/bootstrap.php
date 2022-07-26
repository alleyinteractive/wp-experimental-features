<?php
/**
 * Initialize tests for this plugin.
 *
 * @package Experimental_Features
 */

use function Mantle\Testing\tests_add_filter;

require_once __DIR__ . '/../vendor/wordpress-autoload.php';

\Mantle\Testing\install(
	fn () => tests_add_filter(
		'muplugins_loaded',
		function () {
			require_once dirname( __DIR__ ) . '/wp-experimental-features.php';
		},
	),
);
