<?php
/**
 * Experimental Features includes: Partials class
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

/**
 * Partials helper class.
 *
 * @package Experimental_Features
 */
class Partials {

	/**
	 * A helper function for loading partials.
	 *
	 * @param string $slug The partial filepath to the partial template.
	 * @param array  $args Optional. Arguments to pass to the template part.
	 */
	public static function load( string $slug, array $args = [] ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed

		// Ensure requested partial exists.
		$filepath = dirname( __DIR__ ) . '/partials/' . $slug . '.php';
		if ( ! file_exists( $filepath ) ) {
			return;
		}

		require $filepath; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
	}
}
