#!/usr/bin/env bash

set -e

# Bail early if we aren't linting.
if [[ -z $PHP_LINT ]]; then
	exit 0
fi

count=$(find . -type "f" -iname "*.php" | wc -l | xargs)
echo "Running the PHP linter on $count file(s)..."
# Via https://github.com/Yoast/wordpress-seo/pull/8897/files.
if find . -type "f" -iname "*.php" -exec php -l {} \; | grep "^[Parse error|Fatal error]"; then exit 1; fi
