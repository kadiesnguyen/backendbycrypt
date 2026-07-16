#!/usr/bin/env bash
# Start migration loop with auto phase progression.
# Usage: ./migration-loop-start.sh [interval_seconds]
# Default: 900s (15 min) between ticks.
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
INTERVAL="${1:-900}"
PID_FILE="$ROOT_DIR/.migration-loop.pid"
SENTINEL="AGENT_LOOP_WAKE_admin_migration"

if [[ -f "$PID_FILE" ]] && kill -0 "$(cat "$PID_FILE")" 2>/dev/null; then
  echo "Loop already running (PID $(cat "$PID_FILE")). Stop with: ./migration-loop-stop.sh"
  exit 1
fi

chmod +x "$ROOT_DIR/scripts/migration-loop-tick.sh"
python3 "$ROOT_DIR/scripts/migration-state.py" init

echo "=== Migration status ==="
python3 "$ROOT_DIR/scripts/migration-state.py" status
echo ""

# Run first tick immediately (work now)
PAYLOAD=$(python3 "$ROOT_DIR/scripts/migration-state.py" next-prompt)
echo "=== First tick payload ==="
echo "$PAYLOAD" | python3 -m json.tool
echo ""
echo "Agent MUST execute the prompt above NOW — do not ask user for confirmation."
echo ""

# Background loop — sentinel only after sleep (no double-run on startup)
(
  while true; do
    sleep "$INTERVAL"
    bash "$ROOT_DIR/scripts/migration-loop-tick.sh"
  done
) &

echo $! > "$PID_FILE"
echo "Loop started: PID $(cat "$PID_FILE"), interval ${INTERVAL}s"
echo "Stop: bash $ROOT_DIR/scripts/migration-loop-stop.sh"
