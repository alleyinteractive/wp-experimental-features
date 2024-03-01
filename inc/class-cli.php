<?php
/**
 * CLI class file
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

use WP_CLI;

/**
 * CLI class
 */
class CLI {
	/**
	 * List all experimental features
	 */
	public function list(): int {
		$flags = Filter::flags();

		if ( empty( $flags ) ) {
			WP_CLI::line( 'No experimental features found.' );

			return 0;
		}

		foreach ( $flags as $flag => $label ) {
			$flag = WP_CLI::colorize( '%B' . $flag . '%n' );

			/**
			 * Filters the status of an experimental feature.
			 */
			$status = (bool) apply_filters( 'experimental_features_flag', false, $flag );

			$status = $status
				? WP_CLI::colorize( '%Genabled%n' )
				: WP_CLI::colorize( '%Rdisabled%n' );

			WP_CLI::line( "{$label} ({$flag}): {$status}" );
		}

		return 0;
	}

	/**
	 * Enable an experimental feature by slug.
	 *
	 * @synopsis <feature>
	 *
	 * @param array $args The feature slug.
	 */
	public function enable( array $args ): int {
		[ $feature ] = $args;

		$flags = Filter::flags();

		if ( ! isset( $flags[ $feature ] ) ) {
			WP_CLI::error( "Unknown feature: {$feature}. Use `wp experimental-features list` to see available features. Ensure you are passing the feature slug." );
		}

		if ( Filter::enable_flag( $feature ) ) {
			WP_CLI::success( "Enabled feature: {$feature}" );
		} else {
			WP_CLI::error( "Failed to enable feature: {$feature}" );
		}

		return 0;
	}

	/**
	 * Disable an experimental feature by slug.
	 *
	 * @synopsis <feature>
	 *
	 * @param array $args The feature to disable.
	 */
	public function disable( array $args ): int {
		[ $feature ] = $args;

		$flags = Filter::flags();

		if ( ! isset( $flags[ $feature ] ) ) {
			WP_CLI::error( "Unknown feature: {$feature}. Use `wp experimental-features list` to see available features. Ensure you are passing the feature slug." );
		}

		if ( Filter::disable_flag( $feature ) ) {
			WP_CLI::success( "Disabled feature: {$feature}" );
		} else {
			WP_CLI::error( "Failed to disable feature: {$feature}" );
		}

		return 0;
	}

	/**
	 * Toggle an experimental feature by slug.
	 *
	 * @synopsis <feature>
	 *
	 * @param array $args The feature to toggle.
	 */
	public function toggle( array $args ): int {
		[ $feature ] = $args;

		$flags = Filter::flags();

		if ( ! isset( $flags[ $feature ] ) ) {
			WP_CLI::error( "Unknown feature: {$feature}. Use `wp experimental-features list` to see available features. Ensure you are passing the feature slug." );
		}

		if ( ! Filter::toggle_flag( $feature ) ) {
			WP_CLI::error( "Failed to toggle feature: {$feature}" );
		}

		$status = (bool) apply_filters( 'experimental_features_flag', false, $feature );

		$status = $status
			? WP_CLI::colorize( '%Genabled%n' )
			: WP_CLI::colorize( '%Rdisabled%n' );

		WP_CLI::success( "Toggled feature: {$feature} ({$status})" );

		return 0;
	}
}

WP_CLI::add_command( 'experimental-features', CLI::class );
