#!/usr/bin/env bash

set -e

# Bail early if we aren't running tests.
if [[ -z "${WP_VERSION}" ]]; then
	exit 0
fi

echo "Running phpunit..."
phpunit
