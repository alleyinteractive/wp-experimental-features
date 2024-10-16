<?php
/**
 * The entry point for the WP Experimental Features plugin.
 *
 * Plugin Name: Experimental Features
 * Plugin URI:  https://github.com/alleyinteractive/wp-experimental-features
 * Description: Turn experimental features on and off via a checkbox in the admin.
 * Version:     1.3.2
 * Author:      Alley
 * Author URI:  https://alley.com
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

// Load files (todo: convert to alleyinteractive/composer-wordpress-autoloader).
require_once __DIR__ . '/autoloader.php';

// Initialize functionality.
Filter::init();
Options::init();
REST_API::init();

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/inc/class-cli.php';
}
