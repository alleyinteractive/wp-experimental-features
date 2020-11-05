# Experimental Features
A WordPress plugin that creates a feature flags system for beta testing 
experimental features in your themes or plugins.

## Usage

### Defining Feature Flags

Feature flags are defined using a filter:

```php
/**
 * Define available feature flags.
 *
 * @param array $flags Feature flags that have been defined for the Experimental Features plugin.
 *
 * @return array The modified list of feature flags.
 */
function filter_experimental_features_flags( array $flags ) : array {
	$flags['my-cool-feature'] = __( 'My Cool Feature', 'my-textdomain' );

	return $flags;
}
add_filter(
	'experimental_features_flags',
	__NAMESPACE__ . '\filter_experimental_features_flags',
	10,
	1
);
``` 
