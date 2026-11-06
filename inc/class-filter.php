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
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init(): void {
		add_filter(
			'experimental_features_flag',
			[ self::class, 'filter_experimental_features_flag' ],
			10,
			2
		);

		add_action(
			'update_option_experimental_features_flags',
			[ static::class, 'filter_option_updated' ],
			10,
			2,
		);
	}

	/**
	 * Defines a filter function for the capability required to turn experimental features on and off.
	 *
	 * @return string The capability required to turn experimental features on and off. Defaults to `manage_options`.
	 */
	public static function capability(): string {
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
	 * @param mixed $default The default value for the feature flag. Typically, this is false.
	 * @param mixed $slug    The feature flag slug to look up.
	 * @return bool Whether the given feature flag is active or not.
	 */
	public static function filter_experimental_features_flag( $default, $slug ): bool {
		if ( is_string( $slug ) && in_array( $slug, self::enabled_flags(), true ) ) {
			return true;
		}

		return (bool) $default;
	}

	/**
	 * Fire helpful actions when an experimental feature is enabled or disabled.
	 *
	 * @param mixed $old_value Old value.
	 * @param mixed $value New value.
	 */
	public static function filter_option_updated( $old_value, $value ): void {
		$old_value = self::to_slugs( $old_value );
		$value     = self::to_slugs( $value );

		$enabled  = array_diff( $value, $old_value );
		$disabled = array_diff( $old_value, $value );

		if ( ! empty( $enabled ) || ! empty( $disabled ) ) {
			/**
			 * Fired whenever any feature flag is enabled or disabled.
			 *
			 * @param string[] $enabled Feature flags that were enabled.
			 * @param string[] $disabled Feature flags that were disabled.
			 */
			do_action( 'experimental_features_flags_updated', $enabled, $disabled );
		}

		if ( ! empty( $enabled ) ) {
			foreach ( $enabled as $flag ) {
				/**
				 * Fired whenever a specific feature flag is enabled.
				 */
				do_action( "experimental_features_flag_enabled_{$flag}" );
			}
		}

		if ( ! empty( $disabled ) ) {
			foreach ( $disabled as $flag ) {
				/**
				 * Fired whenever a specific feature flag is disabled.
				 */
				do_action( "experimental_features_flag_disabled_{$flag}" );
			}
		}
	}

	/**
	 * Defines a filter function for the available feature flags.
	 *
	 * @return array<string, string> The available feature flags. Keys are feature slugs, values are feature labels.
	 */
	public static function flags(): array {
		/**
		 * Filters the available feature flags.
		 *
		 * Use this filter to define feature flags for your site. This filter
		 * should return an associative array where the feature slug is the key
		 * and the feature label is the value.
		 *
		 * @param array<string, string> $flags Available feature flags for this site. Keys are feature slugs, values are feature labels.
		 */
		return apply_filters( 'experimental_features_flags', [] );
	}

	/**
	 * Enable the status of a feature flag.
	 *
	 * @param string $flag Flag to toggle.
	 * @return bool|null Boolean if the flag was updated (or failed to update), null otherwise.
	 */
	public static function enable_flag( string $flag ): ?bool {
		$flags = static::flags();

		if ( ! isset( $flags[ $flag ] ) ) {
			return false;
		}

		$setting = self::enabled_flags();

		if ( ! in_array( $flag, $setting, true ) ) {
			$setting[] = $flag;

			return (bool) update_option( 'experimental_features_flags', $setting );
		}

		return null;
	}

	/**
	 * Disable the status of a feature flag.
	 *
	 * @param string $flag Flag to toggle.
	 * @return bool|null Boolean if the flag was updated (or failed to update), null otherwise.
	 */
	public static function disable_flag( string $flag ): ?bool {
		$flags = static::flags();

		if ( ! isset( $flags[ $flag ] ) ) {
			return false;
		}

		$setting = self::enabled_flags();

		if ( in_array( $flag, $setting, true ) ) {
			$setting = array_values( array_diff( $setting, [ $flag ] ) );

			return (bool) update_option( 'experimental_features_flags', $setting );
		}

		return null;
	}

	/**
	 * Toggle the status of a feature flag.
	 *
	 * @param string $flag Flag to toggle.
	 * @return bool
	 */
	public static function toggle_flag( string $flag ): bool {
		$flags = static::flags();

		if ( ! isset( $flags[ $flag ] ) ) {
			return false;
		}

		$setting = self::enabled_flags();

		if ( in_array( $flag, $setting, true ) ) {
			$setting = array_values( array_diff( $setting, [ $flag ] ) );
		} else {
			$setting[] = $flag;
		}

		return (bool) update_option( 'experimental_features_flags', $setting );
	}

	/**
	 * Get the slugs of the feature flags that are currently enabled.
	 *
	 * @return array<int, string>
	 */
	private static function enabled_flags(): array {
		return self::to_slugs( get_option( 'experimental_features_flags', [] ) );
	}

	/**
	 * Normalize a stored option value to a list of flag slugs.
	 *
	 * @param mixed $value Option value.
	 * @return array<int, string>
	 */
	private static function to_slugs( $value ): array {
		return array_values( array_filter( (array) $value, 'is_string' ) );
	}
}
