#!/usr/bin/env bash

set -euo pipefail

sudo chown -R "$(id -u):$(id -g)" node_modules
composer install --no-interaction --prefer-dist
yarn install --frozen-lockfile
yarn build

php -r '
$required = ["curl", "mysqli", "redis"];
$missing = array_values(array_filter($required, static fn (string $extension): bool => !extension_loaded($extension)));
if ($missing !== []) {
    fwrite(STDERR, "Missing PHP extensions: " . implode(", ", $missing) . PHP_EOL);
    exit(1);
}
'

bash .devcontainer/post-start.sh
bash scripts/dev-smoke.sh
