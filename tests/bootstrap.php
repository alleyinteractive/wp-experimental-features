<?php
/**
 * Initialize tests for this plugin.
 *
 * @package Experimental_Features
 */

\Mantle\Testing\manager()
	->loaded( fn () => require_once dirname( __DIR__ ) . '/wp-experimental-features.php' )
	->install();
