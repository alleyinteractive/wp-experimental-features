<?php
/**
 * Experimental Features Partials: Flags Field
 *
 * Renders the feature flags field for the options page.
 *
 * @global array $args Arguments passed to the template part.
 *
 * @package Experimental_Features
 */

?>

<fieldset>
	<legend class="screen-reader-text">
		<?php esc_html_e( 'Feature Flags', 'experimental-features' ); ?>
	</legend>
	<?php foreach ( $args['options'] as $slug => $label ) : ?>
		<div>
			<label for="experimental-features-post-types-<?php echo esc_attr( $slug ); ?>">
				<input id="experimental-features-post-types-<?php echo esc_attr( $slug ); ?>"
					name="experimental_features_flags[]"
					type="checkbox"
					value="<?php echo esc_attr( $slug ); ?>"
					<?php checked( in_array( $slug, $args['value'], true ) ); ?>
				/>
				<?php echo esc_html( $label ); ?>
			</label>
		</div>
	<?php endforeach; ?>
</fieldset>
