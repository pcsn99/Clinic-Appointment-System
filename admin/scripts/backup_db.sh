#!/bin/bash

DB_NAME="clinic_db"
DB_USER="clinic_user"
DB_PASS="password"

BACKUP_DIR="$HOME/clinic_db_backups"
DATE=$(date +%Y=%m-%d_%H-%M-%S)


mkdir -p "$BACKUP_DIR"

mysqldump --no-tablespaces -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/backup_$DATE.sql"

echo "[$DATE] Database Backup created: backup_$DATE.sql" >> "$BACKUP_DIR/backup.log"
