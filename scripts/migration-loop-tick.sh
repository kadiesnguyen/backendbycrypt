#!/usr/bin/env bash
# Emits loop wake with auto phase-advance prompt from migration-state.py
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
export MIGRATION_ROOT="$ROOT_DIR"

python3 - <<'PY'
import json
import os
import subprocess
from pathlib import Path

root = Path(os.environ["MIGRATION_ROOT"])
script = root / "scripts" / "migration-state.py"
payload_raw = subprocess.check_output(["python3", str(script), "next-prompt"], text=True)
payload = json.loads(payload_raw)
line = {
    "action": payload.get("action"),
    "payload": payload,
    "prompt": payload.get("prompt", payload.get("message", "")),
}
print("AGENT_LOOP_WAKE_admin_migration " + json.dumps(line, ensure_ascii=False))
PY
