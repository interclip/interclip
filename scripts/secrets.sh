#!/usr/bin/env bash

set -euo pipefail

if [[ -z "${GITPOD_WORKSPACE_ID:-}" ]]; then
    echo "Refusing to install Gitpod development credentials outside Gitpod." >&2
    exit 1
fi

install -m 600 scripts/.gitpod.env .env
