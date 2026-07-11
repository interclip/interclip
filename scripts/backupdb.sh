#!/usr/bin/env bash

set -euo pipefail
umask 077

DATE=$(date +\%F_%H-%M)
BACKUP_DIR="${BACKUP_DIR:-/var/backups/mysql}"
MYSQL_CONTAINER="${MYSQL_CONTAINER:-iclip_mysql}"
DATABASE_LABEL="${DATABASE_LABEL:-iclip}"

if [[ ! "$DATABASE_LABEL" =~ ^[A-Za-z0-9_-]+$ ]]; then
    echo "DATABASE_LABEL contains unsupported characters" >&2
    exit 1
fi

# Create backup directory if not exists
install -d -m 700 "$BACKUP_DIR"

BACKUP_PATH="$BACKUP_DIR/$DATABASE_LABEL-$DATE.sql"
PARTIAL_PATH=$(mktemp "$BACKUP_DIR/.$DATABASE_LABEL-$DATE.XXXXXX.partial")
trap 'rm -f "$PARTIAL_PATH"' EXIT

docker exec "$MYSQL_CONTAINER" sh -c \
    'MYSQL_PWD="$MYSQL_PASSWORD" exec /usr/bin/mysqldump --single-transaction --no-tablespaces --routines --events --triggers --user="$MYSQL_USER" "$MYSQL_DATABASE"' \
    > "$PARTIAL_PATH"
chmod 600 "$PARTIAL_PATH"
mv "$PARTIAL_PATH" "$BACKUP_PATH"
trap - EXIT

# Remove backups older than 14 days
find "$BACKUP_DIR" -type f -name "*.sql" -mtime +14 -delete
