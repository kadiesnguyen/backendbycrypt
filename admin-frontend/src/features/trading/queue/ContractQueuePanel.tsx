"use client";

import { TableShell, tableClassName, theadClassName, thClassName } from "@/components/list/TableShell";
import { queueResultLabel } from "@/lib/i18n/entity-labels";
import { useI18n } from "@/lib/i18n/useI18n";
import { useState } from "react";
import { queueResultClass } from "../lib/format";
import type { QueueEntry } from "./types";

type ContractQueuePanelProps = {
  entries: QueueEntry[];
  pendingId: number | null;
  onUpdate: (id: number, result: "WIN" | "LOSS") => void;
  onDelete: (entry: QueueEntry) => void;
};

export function ContractQueuePanel({
  entries,
  pendingId,
  onUpdate,
  onDelete,
}: ContractQueuePanelProps) {
  const { t } = useI18n();
  const [editingId, setEditingId] = useState<number | null>(null);
  const [editResult, setEditResult] = useState<"WIN" | "LOSS">("WIN");

  const startEdit = (entry: QueueEntry) => {
    setEditingId(entry.id);
    setEditResult(entry.result.toUpperCase() === "LOSS" ? "LOSS" : "WIN");
  };

  const cancelEdit = () => {
    setEditingId(null);
  };

  const saveEdit = (id: number) => {
    onUpdate(id, editResult);
    setEditingId(null);
  };

  return (
    <TableShell>
      <table className={tableClassName}>
        <thead className={theadClassName}>
          <tr>
            <th scope="col" className={thClassName}>
              {t("common.round")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.result")}
            </th>
            <th scope="col" className={thClassName}>
              {t("common.added")}
            </th>
            <th scope="col" className={`${thClassName} text-right`}>
              {t("common.actions")}
            </th>
          </tr>
        </thead>
        <tbody className="divide-y divide-border">
          {entries.map((entry) => {
            const isEditing = editingId === entry.id;
            const isBusy = pendingId === entry.id;

            return (
              <tr key={entry.id} className="bg-surface transition hover:bg-surface-elevated">
                <td className="px-4 py-3 text-foreground">{entry.round_no}</td>
                <td className="px-4 py-3">
                  {isEditing ? (
                    <select
                      value={editResult}
                      onChange={(e) => setEditResult(e.target.value as "WIN" | "LOSS")}
                      className="rounded border border-border bg-surface-elevated px-2 py-1 text-sm text-foreground"
                    >
                      <option value="WIN">{t("action.win")}</option>
                      <option value="LOSS">{t("action.loss")}</option>
                    </select>
                  ) : (
                    <span
                      className={`inline-flex rounded px-2 py-0.5 text-xs font-medium ${queueResultClass(entry.result)}`}
                    >
                      {queueResultLabel(t, entry.result)}
                    </span>
                  )}
                </td>
                <td className="px-4 py-3 text-muted">{entry.addtime_text}</td>
                <td className="px-4 py-3">
                  <div className="flex justify-end gap-2">
                    {isEditing ? (
                      <>
                        <button
                          type="button"
                          disabled={isBusy}
                          onClick={() => saveEdit(entry.id)}
                          className="rounded bg-primary px-2.5 py-1 text-xs font-medium text-background transition hover:opacity-90 disabled:opacity-40"
                        >
                          {t("common.save")}
                        </button>
                        <button
                          type="button"
                          disabled={isBusy}
                          onClick={cancelEdit}
                          className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                        >
                          {t("common.cancel")}
                        </button>
                      </>
                    ) : (
                      <>
                        <button
                          type="button"
                          disabled={isBusy || editingId !== null}
                          onClick={() => startEdit(entry)}
                          className="rounded border border-border px-2.5 py-1 text-xs font-medium text-foreground transition hover:bg-surface-elevated disabled:opacity-40"
                        >
                          {t("common.edit")}
                        </button>
                        <button
                          type="button"
                          disabled={isBusy || editingId !== null}
                          onClick={() => onDelete(entry)}
                          className="rounded border border-danger px-2.5 py-1 text-xs font-medium text-danger transition hover:bg-danger/10 disabled:opacity-40"
                        >
                          {t("common.delete")}
                        </button>
                      </>
                    )}
                  </div>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </TableShell>
  );
}
