<?php
/**
 * Experimental Features includes: Options class
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

/**
 * Options page for Experimental Features.
 *
 * @package Experimental_Features
 */
class Options {
	/**
	 * Registers settings fields.
	 */
	public static function action_admin_init() {
		// Define fields to register.
		$fields = [
			'experimental_features_flags' => [
				'label'    => esc_html__( 'Feature Flags', 'experimental-features' ),
				'sanitize' => [ self::class, 'sanitize_flags' ],
			],
		];

		// Register the config section.
		add_settings_section(
			'experimental_features_config_section',
			esc_html__( 'Experimental Features Configuration', 'experimental-features' ),
			'__return_null',
			'experimental_features_config_options'
		);

		// Loop over field definitions and register each.
		foreach ( $fields as $field_key => $field_properties ) {

			// Add the definition for the field.
			add_settings_field(
				$field_key,
				$field_properties['label'],
				[ self::class, 'render_field' ],
				'experimental_features_config_options',
				'experimental_features_config_section',
				[
					'field_name' => $field_key,
					'label_for'  => str_replace( '_', '-', $field_key ),
				]
			);

			// Register the fields.
			register_setting(
				'experimental_features_config_section',
				$field_key,
				$field_properties['sanitize']
			);
		}
	}

	/**
	 * Adds the options page to the Settings menu.
	 */
	public static function action_admin_menu() {
		add_options_page(
			esc_html__( 'Experimental Features', 'experimental-features' ),
			esc_html__( 'Experimental Features', 'experimental-features' ),
			Filter::capability(),
			'experimental-features',
			[ self::class, 'render_options_page' ]
		);
	}

	/**
	 * Initializes functionality by setting up action and filter hooks.
	 */
	public static function init() {
		add_action(
			'admin_init',
			[ self::class, 'action_admin_init' ]
		);
		add_action(
			'admin_menu',
			[ self::class, 'action_admin_menu' ]
		);

		add_action(
			'admin_init',
			[ self::class, 'register_admin_bar_menu' ]
		);
	}

	/**
	 * Renders a field for an option.
	 *
	 * @param array $args Field arguments.
	 */
	public static function render_field( array $args ) {

		// Get the un-namespaced field name.
		$field_name = str_replace( 'experimental_features_', '', $args['field_name'] );

		// Swap underscores for dashes in the remainder of the field name.
		$field_name = str_replace( '_', '-', $field_name );

		// Get the current value for the option.
		$args['value'] = get_option( $args['field_name'] );

		// Prepare data for partials based on field name.
		switch ( $field_name ) {
			case 'flags':
				$args['options'] = Filter::flags();
				$args['value']   = empty( $args['value'] ) ? [] : $args['value'];
				break;
		}

		// Load the field partial.
		Partials::load(
			'field-' . $field_name,
			$args
		);
	}

	/**
	 * Renders the options page.
	 */
	public static function render_options_page() {
		Partials::load( 'options-page' );
	}

	/**
	 * Sanitizes an array of feature flags.
	 *
	 * @param ?array $option_value The array of feature flag slugs to sanitize.
	 * @return array The sanitized array.
	 */
	public static function sanitize_flags( ?array $option_value ): array {
		return array_map( 'sanitize_text_field', null === $option_value ? [] : $option_value );
	}

	/**
	 * Register the admin bar menu.
	 */
	public static function register_admin_bar_menu() {
		if ( ! static::should_include_admin_bar() ) {
			return;
		}

		self::handle_toggle_request();

		add_action(
			'admin_bar_menu',
			[ self::class, 'action_admin_bar_menu' ],
			200
		);
	}

	/**
	 * Determine if the admin bar should be included.
	 *
	 * @return bool
	 */
	public static function should_include_admin_bar(): bool {
		return (bool) apply_filters( 'experimental_features_admin_bar', current_user_can( Filter::capability() ) );
	}

	/**
	 * Handle the request to toggle a feature via the admin bar.
	 */
	public static function handle_toggle_request() {
		if ( empty( $_GET['experimental-feature-toggle'] ) || empty( $_GET['experimental-feature-nonce'] ) ) {
			return;
		}

		check_admin_referer( 'experimental-features', 'experimental-feature-nonce', 'experimental-features' );

		Filter::toggle_flag( sanitize_text_field( wp_unslash( $_GET['experimental-feature-toggle'] ) ) );
	}

	/**
	 * Register the admin bar toggle switches.
	 */
	public static function action_admin_bar_menu() {
		global $wp_admin_bar;

		$features = Filter::flags();

		if ( empty( $features ) ) {
			return;
		}

    $wp_admin_bar->add_menu(
			[
				'href'  => admin_url( 'options-general.php?page=experimental-features' ),
				'id'    => 'experimental-features',
				'title' => __( '🧪 Experimental Features', 'experimental-features' )
			]
		);

		$nonce = wp_create_nonce( 'experimental-features' );

		foreach ( $features as $feature => $label ) {
			$enabled = Filter::filter_experimental_features_flag( false, $feature );

			$wp_admin_bar->add_menu(
				[
					'href'   => add_query_arg(
						[
							'experimental-feature-nonce'  => $nonce,
							'experimental-feature-toggle' => $feature,
						]
					),
					'id'     => "experimental-features-{$feature}",
					'parent' => 'experimental-features',
					'title'  => sprintf(
						$enabled
							/* translators: feature name */
							? __( '✅ Disable "%s"', 'experimental-features' )
							/* translators: feature name */
							: __( '❌ Enable "%s"', 'experimental-features' ),
						$label
					),
				]
			);
		}
	}
}
