<?php
/**
 * Experimental Features: Autoloader
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

spl_autoload_register(
	function ( $class ) {
		// Split class into namespace and class name, and ensure the request was for this namespace.
		$parts = explode( '\\', $class );
		if ( 2 !== count( $parts ) || __NAMESPACE__ !== $parts[0] ) {
			return;
		}

		// Transform the class name into a class slug and load the file, if it exists.
		$class_slug = str_replace( '_', '-', strtolower( $parts[1] ) );
		$class_file = __DIR__ . '/inc/class-' . $class_slug . '.php';
		if ( file_exists( $class_file ) ) {
			require_once $class_file; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		}
	}
);
