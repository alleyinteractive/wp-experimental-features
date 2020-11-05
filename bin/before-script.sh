#!/usr/bin/env bash

set -e

# Turn off Xdebug. See https://core.trac.wordpress.org/changeset/40138.
phpenv config-rm xdebug.ini || echo "Xdebug not available"

# Install PHPUnit.
composer global require "phpunit/phpunit=6.1.*"

# Set up the WordPress installation.
export WP_CORE_DIR=/tmp/wordpress/
bash bin/install-wp-tests.sh wordpress_test root '' localhost "${WP_VERSION}"

# Maybe install memcached.
if [[ -n "${WP_TRAVIS_OBJECT_CACHE}" ]]; then
  printf "\n" | pecl install --force memcache 1> /dev/null
  curl -s https://raw.githubusercontent.com/Automattic/wp-memcached/master/object-cache.php > "${WP_CORE_DIR}/wp-content/object-cache.php"
fi

# Maybe set up phpcs.
if [[ -n "${WP_PHPCS}" ]]; then
  composer g require --dev automattic/vipwpcs dealerdirect/phpcodesniffer-composer-installer
fi
