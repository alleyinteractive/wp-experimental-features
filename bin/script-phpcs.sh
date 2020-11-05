#!/usr/bin/env bash

set -e

# Bail early if we aren't linting.
if [[ -z $WP_PHPCS ]]; then
	exit 0
fi

echo "Running phpcs..."
phpcs
