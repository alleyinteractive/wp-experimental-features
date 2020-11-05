<?php
/**
 * Experimental Features Partials: Options Page
 *
 * Renders the options page for the plugin.
 *
 * @global array $args Arguments passed to the template part.
 *
 * @package Experimental_Features
 */

?>

<div class="wrap">
	<h1><?php esc_html_e( 'Experimental Features', 'experimental-features' ); ?></h1>
	<form id="experimental-features-config" method="post" action="options.php">
		<?php settings_fields( 'experimental_features_config_section' ); ?>
		<?php do_settings_sections( 'experimental_features_config_options' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
