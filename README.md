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

### Checking the Value of Feature Flags

The value of a feature flag can be checked by running the feature flag slug
through the `experimental_features_flag` filter. This allows for plugins and
themes to not break if the Experimental Features plugin is deactivated,
because the filter will simply return the default value.

```php
$is_enabled = apply_filters( 
	'experimental_features_flag',
	false,
	'my-cool-feature'
);
```

If the flag is not enabled, or if the Experimental Features plugin is not
active, then the default value (first parameter, `false` in the example above)
will be returned.
