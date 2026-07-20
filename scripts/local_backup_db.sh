#!/usr/bin/env bash
set -euo pipefail

BACKUP_DIR="/var/www/gpbm/sql_manual"
LOG_FILE="${BACKUP_DIR}/backup_${HOSTNAME}_$(date +'%Y-%m-%d').log"
DATE="$(date +'%Y-%m-%d_%H-%M-%S')"
MYSQLDUMP_BIN="${MYSQLDUMP_BIN:-mysqldump}"

mkdir -p "$BACKUP_DIR"
touch "$LOG_FILE"

log() {
  echo "[$(date +'%Y-%m-%d %H:%M:%S')] $*" | tee -a "$LOG_FILE"
}

DATABASES=(
  'name=gplan_pbm|host=localhost|user=gplan_pbm|pass=G#a1m9ep8la@0n'
)

COMMON_OPTS=(
  --single-transaction
  --quick
  --skip-lock-tables
  --default-character-set=utf8mb4
)

FAILED_DBS=()

for DB in "${DATABASES[@]}"; do
  IFS='|' read -r NAME HOST USER PASS <<< "$DB"

  NAME="${NAME#name=}"
  HOST="${HOST#host=}"
  USER="${USER#user=}"
  PASS="${PASS#pass=}"

  OUT="${BACKUP_DIR}/${NAME}_${DATE}.sql.gz"
  ERR_TMP="$(mktemp)"

  log "Backing up ${NAME} (host=${HOST})..."

  set +e
  "$MYSQLDUMP_BIN" \
    --host="$HOST" \
    --user="$USER" \
    --password="$PASS" \
    "${COMMON_OPTS[@]}" \
    --routines --triggers --events \
    "$NAME" 2>"$ERR_TMP" | gzip -c > "$OUT"
  RC=$?
  set -e

  if [[ $RC -eq 0 ]]; then
    rm -f "$ERR_TMP"
    log "  ✅ OK (with routines/triggers/events): $OUT"
    continue
  fi

  if grep -qiE 'Access denied|show events|show routine|TRIGGER command denied|EVENT command denied' "$ERR_TMP"; then
    log "  ⚠️  Privilege limitation detected. Retrying without routines/triggers/events..."
    rm -f "$OUT"

    set +e
    "$MYSQLDUMP_BIN" \
      --host="$HOST" \
      --user="$USER" \
      --password="$PASS" \
      "${COMMON_OPTS[@]}" \
      "$NAME" 2>>"$ERR_TMP" | gzip -c > "$OUT"
    RC2=$?
    set -e

    if [[ $RC2 -eq 0 ]]; then
      log "  ✅ OK (tables+data only): $OUT"
      log "  ↳ Details: $(tr '\n' ' ' < "$ERR_TMP" | sed 's/  */ /g' | cut -c1-300)..."
      rm -f "$ERR_TMP"
      continue
    fi
  fi

  log "  ❌ FAILED for ${NAME}. Error (first ~300 chars):"
  log "     $(tr '\n' ' ' < "$ERR_TMP" | sed 's/  */ /g' | cut -c1-300)..."
  rm -f "$ERR_TMP"
  FAILED_DBS+=("$NAME")
done

RETENTION_DAYS=30
log "Applying retention: deleting backups/logs older than ${RETENTION_DAYS} days from ${BACKUP_DIR}..."

find "$BACKUP_DIR" -maxdepth 1 -type f -name "*.sql.gz" -mtime +"$RETENTION_DAYS" -print -delete \
  | while read -r f; do log "  🗑️  Deleted: $f"; done

find "$BACKUP_DIR" -maxdepth 1 -type f -name "backup_*.log" -mtime +"$RETENTION_DAYS" -print -delete \
  | while read -r f; do log "  🗑️  Deleted: $f"; done

log "Retention complete."

if [[ ${#FAILED_DBS[@]} -gt 0 ]]; then
  log "❌ Backup completed with failures: ${FAILED_DBS[*]}"
  log "Log: $LOG_FILE"
  exit 1
fi

log "✅ All backups completed successfully."
log "Log: $LOG_FILE"
