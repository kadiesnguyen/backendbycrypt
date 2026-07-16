#!/usr/bin/env python3
"""Migration loop state manager — auto-advance phases when all tasks complete."""

from __future__ import annotations

import argparse
import json
import sys
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

ROOT = Path(__file__).resolve().parent.parent
STATE_FILE = ROOT / ".migration-loop-state.json"
PLAYBOOK_FILE = ROOT / ".migration-playbook.json"


def load_json(path: Path) -> dict[str, Any]:
    with path.open(encoding="utf-8") as f:
        return json.load(f)


def save_state(state: dict[str, Any]) -> None:
    state["lastTick"] = datetime.now(timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")
    with STATE_FILE.open("w", encoding="utf-8") as f:
        json.dump(state, f, indent=2, ensure_ascii=False)
        f.write("\n")


def phase_key(phase: int | str) -> str:
    return str(phase)


def get_playbook_tasks(playbook: dict[str, Any], phase: int) -> dict[str, Any]:
    return playbook["phases"][phase_key(phase)]["tasks"]


def ensure_phase_in_state(state: dict[str, Any], playbook: dict[str, Any], phase: int) -> None:
    key = phase_key(phase)
    if key not in state["phases"]:
        state["phases"][key] = {
            "name": playbook["phases"][key]["name"],
            "status": "pending",
            "tasks": {},
        }

    phase_state = state["phases"][key]
    playbook_tasks = get_playbook_tasks(playbook, phase)

    if "tasks" not in phase_state or not isinstance(phase_state["tasks"], dict):
        phase_state["tasks"] = {}

    for task_id in playbook_tasks:
        if task_id not in phase_state["tasks"]:
            phase_state["tasks"][task_id] = "pending"


def all_tasks_done(phase_state: dict[str, Any]) -> bool:
    tasks = phase_state.get("tasks", {})
    if not tasks:
        return False
    return all(status == "done" for status in tasks.values())


def first_pending_task(phase_state: dict[str, Any]) -> str | None:
    for task_id, status in phase_state.get("tasks", {}).items():
        if status != "done":
            return task_id
    return None


def advance_phase(state: dict[str, Any], playbook: dict[str, Any]) -> bool:
    """Mark current phase done and move to next. Returns True if advanced."""
    current = int(state["currentPhase"])
    key = phase_key(current)
    phase_state = state["phases"][key]

    if not all_tasks_done(phase_state):
        return False

    phase_state["status"] = "done"

    next_phase = current + 1
    if next_phase > 6:
        state["notes"] = "Migration hoàn tất — tất cả phase 0-6 done."
        save_state(state)
        return False

    state["currentPhase"] = next_phase
    ensure_phase_in_state(state, playbook, next_phase)
    state["phases"][phase_key(next_phase)]["status"] = "in_progress"
    state["notes"] = f"Auto-advanced: Phase {current} → Phase {next_phase}"
    save_state(state)
    return True


def cmd_status() -> int:
    state = load_json(STATE_FILE)
    playbook = load_json(PLAYBOOK_FILE)
    current = int(state["currentPhase"])
    ensure_phase_in_state(state, playbook, current)

    phase_state = state["phases"][phase_key(current)]
    pending = first_pending_task(phase_state)
    done_count = sum(1 for s in phase_state["tasks"].values() if s == "done")
    total = len(phase_state["tasks"])

    print(f"Phase {current}: {phase_state['name']} ({phase_state.get('status', 'unknown')})")
    print(f"Tasks: {done_count}/{total} done")
    for task_id, status in phase_state["tasks"].items():
        mark = "✓" if status == "done" else "○"
        title = playbook["phases"][phase_key(current)]["tasks"][task_id]["title"]
        print(f"  {mark} {task_id}: {title} [{status}]")
    if pending:
        print(f"Next task: {pending}")
    elif all_tasks_done(phase_state):
        print("Phase complete — ready to advance")
    return 0


def cmd_complete(task_id: str) -> int:
    state = load_json(STATE_FILE)
    playbook = load_json(PLAYBOOK_FILE)
    current = int(state["currentPhase"])
    ensure_phase_in_state(state, playbook, current)

    phase_state = state["phases"][phase_key(current)]
    if task_id not in phase_state["tasks"]:
        print(f"Unknown task: {task_id}", file=sys.stderr)
        return 1

    phase_state["tasks"][task_id] = "done"
    if phase_state.get("status") == "pending":
        phase_state["status"] = "in_progress"

    advanced = False
    while all_tasks_done(phase_state):
        advanced = advance_phase(state, playbook)
        if not advanced:
            break
        current = int(state["currentPhase"])
        phase_state = state["phases"][phase_key(current)]

    if not advanced:
        save_state(state)

    print(f"Task '{task_id}' marked done.")
    if advanced:
        print(f"Advanced to Phase {state['currentPhase']}: {state['phases'][phase_key(state['currentPhase'])]['name']}")
    cmd_status()
    return 0


def cmd_try_advance() -> int:
    state = load_json(STATE_FILE)
    playbook = load_json(PLAYBOOK_FILE)
    current = int(state["currentPhase"])
    ensure_phase_in_state(state, playbook, current)

    if advance_phase(state, playbook):
        print(f"Advanced to Phase {state['currentPhase']}")
    else:
        print(f"Phase {current} chưa xong — không advance")
    cmd_status()
    return 0


def cmd_next_prompt() -> int:
    """Output JSON prompt payload for the migration loop agent."""
    state = load_json(STATE_FILE)
    playbook = load_json(PLAYBOOK_FILE)
    current = int(state["currentPhase"])
    ensure_phase_in_state(state, playbook, current)

    phase_state = state["phases"][phase_key(current)]

    # Auto-advance if current phase fully done
    while all_tasks_done(phase_state) and current < 6:
        advance_phase(state, playbook)
        current = int(state["currentPhase"])
        phase_state = state["phases"][phase_key(current)]

    if current >= 6 and all_tasks_done(phase_state):
        payload = {
            "action": "complete",
            "phase": current,
            "message": "Tất cả phase 0-6 hoàn tất. Báo cáo tổng kết migration.",
        }
        print(json.dumps(payload, ensure_ascii=False))
        return 0

    pending = first_pending_task(phase_state)
    if not pending:
        payload = {
            "action": "wait",
            "phase": current,
            "message": f"Phase {current} không có task pending.",
        }
        print(json.dumps(payload, ensure_ascii=False))
        return 0

    task_meta = playbook["phases"][phase_key(current)]["tasks"][pending]
    done = sum(1 for s in phase_state["tasks"].values() if s == "done")
    total = len(phase_state["tasks"])

    payload = {
        "action": "work",
        "phase": current,
        "phase_name": phase_state["name"],
        "task_id": pending,
        "task_title": task_meta["title"],
        "progress": f"{done}/{total}",
        "prompt": (
            f"[Migration Loop] Phase {current} ({phase_state['name']}) — "
            f"task '{pending}': {task_meta['title']}. "
            f"Tiến độ phase: {done}/{total}. "
            f"{task_meta['prompt']} "
            f"KHÔNG hỏi user — tự thực hiện ngay khi nhận tick này. "
            f"Sau khi xong: `python3 Backend/scripts/migration-state.py complete {pending}`. "
            f"Khi tất cả task phase done, hệ thống tự chuyển phase tiếp theo. "
            f"Tuân thủ .cursor/rules/frontend-ui-engineering.md. "
            f"Đọc ThinkPHP tại Backend/thinkphp-admin/, implement tại Backend/laravel/ và Backend/admin-frontend/."
        ),
    }
    print(json.dumps(payload, ensure_ascii=False))
    return 0


def cmd_init() -> int:
    """Sync state tasks from playbook (non-destructive)."""
    state = load_json(STATE_FILE)
    playbook = load_json(PLAYBOOK_FILE)

    for phase_num in range(0, 7):
        ensure_phase_in_state(state, playbook, phase_num)

    save_state(state)
    print("State synced with playbook.")
    cmd_status()
    return 0


def main() -> int:
    parser = argparse.ArgumentParser(description="Admin migration loop state manager")
    sub = parser.add_subparsers(dest="command", required=True)

    sub.add_parser("status", help="Show current phase and tasks")
    sub.add_parser("init", help="Sync state from playbook")
    sub.add_parser("try-advance", help="Advance phase if all tasks done")
    sub.add_parser("next-prompt", help="JSON prompt for loop agent (auto-advance)")

    complete_p = sub.add_parser("complete", help="Mark task done and auto-advance")
    complete_p.add_argument("task_id", help="Task id to mark done")

    args = parser.parse_args()

    handlers = {
        "status": cmd_status,
        "init": cmd_init,
        "try-advance": cmd_try_advance,
        "next-prompt": cmd_next_prompt,
    }

    if args.command == "complete":
        return cmd_complete(args.task_id)

    return handlers[args.command]()


if __name__ == "__main__":
    raise SystemExit(main())
