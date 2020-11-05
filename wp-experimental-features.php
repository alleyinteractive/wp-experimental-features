<?php
/**
 * The entry point for the WP Experimental Features plugin.
 *
 * Plugin Name: Experimental Features
 * Plugin URI:  https://github.com/alleyinteractive/wp-experimental-features
 * Description: Turn experimental features on and off via a checkbox in the admin.
 * Version:     1.0.0
 * Author:      Alley
 * Author URI:  https://alley.co
 *
 * @package Experimental_Features
 */

namespace Experimental_Features;

// Load files.
require_once __DIR__ . '/autoloader.php';

// Initialize functionality.
Options::init();
