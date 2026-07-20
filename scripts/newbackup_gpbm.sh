#!/usr/bin/env bash
# backup_gameplan.sh — rsync.net flat backup with DB dumps
# Versioning is handled entirely by rsync.net's server-side snapshot schedule.
# This script simply keeps the remote in sync with the current local state.

###############################################################################
#                                CONFIG                                       #
###############################################################################

# Mail
EMAIL="gp@milnesconsultancy.co.uk"
SEND_SUCCESS_MAIL=true
CLIENT="GameplanPBM"
EMAIL_SUBJECT_PREFIX="[${CLIENT} backup]"

# Base paths (local)
BASE="/home/sites/7a/9/917dc0194c"
SQL_DIR="${BASE}/sql"
WEB_DIR="${BASE}/public_html"

# Databases (each with own creds). Add more entries as needed.
# Format per item: 'name=<db>|host=<host>|user=<user>|pass=<password>'
DBS=(
  'name=gpbmuk-313333c5cd|host=shareddb-u.hosting.stackcp.net|user=gpbmuk-313333c5cd|pass=1I2m$JYh3_)Q'
)

# Remote rsync.net target (SSH keys already set up)
REMOTE_USER="zh2342"
REMOTE_HOST="zh2342.rsync.net"
REMOTE_BASE="live/gpbm"           # remote base folder (relative to remote $HOME)
REMOTE_WEB="${REMOTE_BASE}/web"  # synced from WEB_DIR
REMOTE_SQL="${REMOTE_BASE}/sql"  # synced from SQL_DIR

# Logs & lock (local)
LOG_DIR="${BASE}/logs"
LOG_FILE="${LOG_DIR}/backup.log"
LOCK_DIR="${BASE}/.cache/locks"
LOCK_FILE="${LOCK_DIR}/backup.lock"

# Optional: rsync transfer tuning
RSYNC_BW_LIMIT=""      # e.g. "40960" for 40 MB/s; leave blank for unlimited

###############################################################################
#                       EARLY SETUP  (runs before set -e)                     #
###############################################################################

mkdir -p "${LOG_DIR}" "${LOCK_DIR}" "${SQL_DIR}"

log() {
  printf '%s [%s] %s\n' "$(date '+%F %T')" "$1" "$2" | tee -a "${LOG_FILE}"
}

ensure_abs() { local n="$1" p="$2"; [[ "$p" = /* ]] || { log "ERROR" "$n must be absolute: $p"; exit 2; }; }
ensure_dir() { local p="$1"; [[ -d "$p" ]] || { log "ERROR" "Missing directory: $p"; exit 2; }; }

BASE="${BASE%/}"; SQL_DIR="${SQL_DIR%/}"; WEB_DIR="${WEB_DIR%/}"

ensure_abs "BASE"    "$BASE"
ensure_abs "SQL_DIR" "$SQL_DIR"
ensure_abs "WEB_DIR" "$WEB_DIR"
ensure_dir "$SQL_DIR"
ensure_dir "$WEB_DIR"

case "$SQL_DIR" in "$BASE"/*) ;; *) log "ERROR" "SQL_DIR must be under BASE"; exit 2;; esac
case "$WEB_DIR" in "$BASE"/*) ;; *) log "ERROR" "WEB_DIR must be under BASE"; exit 2;; esac

log "INFO" "Resolved paths: BASE=$BASE  SQL_DIR=$SQL_DIR  WEB_DIR=$WEB_DIR"
log "INFO" "Remote target: ${REMOTE_USER}@${REMOTE_HOST}  web=${REMOTE_WEB}  sql=${REMOTE_SQL}"

###############################################################################
#                          INTERNALS (no need to edit)                        #
###############################################################################

set -Eeuo pipefail
set +H        # disable history expansion so '!' in passwords won't bite
umask 077

PATH="/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:$PATH"
SSH_TARGET="${REMOTE_USER}@${REMOTE_HOST}"
RSYNC_SSH=(ssh -o BatchMode=yes -o StrictHostKeyChecking=accept-new -o ConnectTimeout=20)
RSYNC_COMMON=(-aH --delete --numeric-ids --delete-excluded -z --partial --human-readable -e "${RSYNC_SSH[*]}")
[[ -n "${RSYNC_BW_LIMIT}" ]] && RSYNC_COMMON+=(--bwlimit="${RSYNC_BW_LIMIT}")

RUN_TS="$(date +%F_%H-%M-%S)"
CURRENT_STAGE="(not started)"

have_cmd() { command -v "$1" >/dev/null 2>&1; }

send_email() {
  local subject="$1" body="$2"
  if have_cmd mail; then
    printf '%s\n' "$body" | mail -s "$subject" "$EMAIL"
  elif have_cmd mailx; then
    printf '%s\n' "$body" | mailx -s "$subject" "$EMAIL"
  elif have_cmd sendmail; then
    { printf 'To: %s\nSubject: %s\n\n%s\n' "$EMAIL" "$subject" "$body"; } | sendmail -t
  else
    log "WARN" "No mailer available; could not send email: ${subject}"
  fi
}

on_error() {
  local ec=$?
  local tail_snippet
  tail_snippet="$(tail -n 200 -- "${LOG_FILE}" || true)"
  send_email "${EMAIL_SUBJECT_PREFIX} FAILED at stage: ${CURRENT_STAGE}" \
             "Stage: ${CURRENT_STAGE}\nExit code: $ec\n\nLast 200 log lines:\n\n${tail_snippet}"
  log "ERROR" "Failed at stage: ${CURRENT_STAGE} (exit $ec). Email sent."
  rm -f -- "${LOCK_FILE}" || true
  exit $ec
}
trap on_error ERR

# PID-file lock
if [[ -f "${LOCK_FILE}" ]]; then
  old_pid="$(cat "${LOCK_FILE}" || true)"
  if [[ -n "${old_pid}" ]] && kill -0 "${old_pid}" 2>/dev/null; then
    log "ERROR" "Another run is active (pid ${old_pid}). Exiting."
    exit 1
  else
    log "WARN" "Stale lock (pid ${old_pid}) found; removing."
    rm -f -- "${LOCK_FILE}"
  fi
fi
echo "$$" > "${LOCK_FILE}"
trap 'rm -f -- "${LOCK_FILE}"' EXIT

require_cmds=(ssh rsync mysqldump date find)
for c in "${require_cmds[@]}"; do
  if ! have_cmd "$c"; then
    log "ERROR" "Required command not found: ${c}"
    exit 2
  fi
done

# ── helpers ──────────────────────────────────────────────────────────────────

get_field() {
  local key="$1" data="$2" kv
  local IFS='|'
  for kv in $data; do
    case "$kv" in
      ${key}=*) printf '%s' "${kv#*=}"; return 0 ;;
    esac
  done
  return 1
}

make_remote_dirs() {
  "${RSYNC_SSH[@]}" "${SSH_TARGET}" "mkdir -p '${REMOTE_WEB}' '${REMOTE_SQL}'"
}

do_rsync() {
  local src="$1" remote_dir="$2" label="$3"
  rsync "${RSYNC_COMMON[@]}" -- \
    "${src%/}/" "${SSH_TARGET}:${remote_dir}/" >>"${LOG_FILE}" 2>&1
  log "INFO" "Rsync ${label} → ${remote_dir}"
}

###############################################################################
#                                WORKFLOW                                     #
###############################################################################

CURRENT_STAGE="Start job"
log "INFO" "===== Backup job start for ${CLIENT} at ${RUN_TS} ====="

CURRENT_STAGE="Remote setup (mkdir)"
make_remote_dirs
log "INFO" "Remote directories confirmed: ${REMOTE_WEB}  ${REMOTE_SQL}"

CURRENT_STAGE="Dump databases"
i=1
for dbdef in "${DBS[@]}"; do
  DB_NAME="$(get_field name "$dbdef")"
  DB_HOST="$(get_field host "$dbdef")"
  DB_USER="$(get_field user "$dbdef")"
  DB_PASS="$(get_field pass "$dbdef")"

  [[ -z "${DB_NAME}" || -z "${DB_HOST}" || -z "${DB_USER}" || -z "${DB_PASS}" ]] && {
    log "ERROR" "DB definition #${i} is incomplete."
    exit 3
  }

  safe_db="${DB_NAME//[^A-Za-z0-9_.-]/_}"
  dump_file="${SQL_DIR}/${CLIENT}-${i}-${safe_db}.sql"

  log "INFO" "Dumping DB #${i} (${DB_NAME}) from ${DB_HOST} → ${dump_file}"
  mysqldump \
    -h "${DB_HOST}" -u "${DB_USER}" -p"${DB_PASS}" \
    --single-transaction --quick --skip-lock-tables \
    --default-character-set=utf8mb4 \
    --skip-triggers \
    "${DB_NAME}" > "${dump_file}"

  if [[ ! -s "${dump_file}" ]]; then
    log "ERROR" "Dump for DB #${i} (${DB_NAME}) created but empty."
    exit 4
  fi
  log "INFO" "Dumped ${DB_NAME} (DB #${i}); size=$(stat -c%s "${dump_file}" 2>/dev/null || wc -c <"${dump_file}") bytes"
  (( i++ ))
done

CURRENT_STAGE="Rsync SQL dumps"
do_rsync "${SQL_DIR}" "${REMOTE_SQL}" "sql"

CURRENT_STAGE="Rsync WEB_DIR"
do_rsync "${WEB_DIR}" "${REMOTE_WEB}" "web"

CURRENT_STAGE="Clear local SQL dumps"
find "${SQL_DIR}" -maxdepth 1 -type f -name "${CLIENT}-*.sql" -print -delete >>"${LOG_FILE}" 2>&1 || true
log "INFO" "Cleared local SQL dumps for ${CLIENT}"

CURRENT_STAGE="End job"
log "INFO" "===== Backup job end for ${CLIENT} at $(date '+%F %T') ====="

if [[ "${SEND_SUCCESS_MAIL}" == true ]]; then
  tail_snippet="$(tail -n 200 -- "${LOG_FILE}" || true)"
  send_email "${EMAIL_SUBJECT_PREFIX} SUCCESS" \
             "Backup completed successfully at $(date '+%F %T').\nRemote: ${REMOTE_BASE}\n\nLast 200 log lines:\n\n${tail_snippet}"
  log "INFO" "Success email sent."
fi

exit 0
