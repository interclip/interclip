#!/bin/bash

SCRIPT_DIR=$(dirname "$0")

# Load environment variables from the .env file
export $(grep -v '^#' "$SCRIPT_DIR/../.env" | xargs)

# Set variables
DATE=$(date +\%F_%H-%M)
BACKUP_DIR="/var/backups/mysql"
MYSQL_CONTAINER="iclip_mysql"
MYSQL_USER="${USERNAME}"
MYSQL_PASSWORD="${PASSWORD}"
DATABASE="${DB_NAME}"

# Create backup directory if not exists
mkdir -p ${BACKUP_DIR}

# Dump the database into a file
docker exec ${MYSQL_CONTAINER} /usr/bin/mysqldump -u ${MYSQL_USER} --password=${MYSQL_PASSWORD} ${DATABASE} > ${BACKUP_DIR}/${DATABASE}-${DATE}.sql

# Remove backups older than 14 days
find ${BACKUP_DIR} -type f -name "*.sql" -mtime +14 -exec rm {} \;
