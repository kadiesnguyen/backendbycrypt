import type { TranslationParams } from "./types";

export type TranslateFn = (key: string, params?: TranslationParams) => string;

function label(t: TranslateFn, prefix: string, code: number): string {
  const key = `${prefix}.${code}`;
  const value = t(key);
  return value === key ? t("common.unknown") : value;
}

export function contractHyzdLabel(t: TranslateFn, hyzd: number): string {
  return label(t, "label.contract.hyzd", hyzd);
}

export function contractStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.contract.status", status);
}

export function contractIsWinLabel(t: TranslateFn, isWin: number): string {
  return label(t, "label.contract.is_win", isWin);
}

export function contractKongykLabel(t: TranslateFn, kongyk: number): string {
  return label(t, "label.contract.kongyk", kongyk);
}

export function billStLabel(t: TranslateFn, st: number): string {
  return label(t, "label.bill.st", st);
}

export function loginLogStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.loginLog.status", status);
}

export function depositStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.deposit.status", status);
}

export function depositMethodLabel(t: TranslateFn, method: number): string {
  return label(t, "label.deposit.method", method);
}

export function withdrawalStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.withdrawal.status", status);
}

export function minerStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.miner.status", status);
}

export function minerTypeLabel(t: TranslateFn, type: number): string {
  return label(t, "label.miner.type", type);
}

export function stakeDisplayLabel(t: TranslateFn, status: number): string {
  return label(t, "label.stake.display", status);
}

export function stakeStateLabel(t: TranslateFn, state: number): string {
  return label(t, "label.stake.state", state);
}

export function spotOrderStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.spot.status", status);
}

export function transferStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.transfer.status", status);
}

export function walletStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.wallet.status", status);
}

export function contentStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.content.status", status);
}

export function issueLogStatusLabel(t: TranslateFn, status: number): string {
  return label(t, "label.issueLog.status", status);
}

export function activeInactiveLabel(t: TranslateFn, active: boolean): string {
  return active ? t("common.active") : t("common.inactive");
}

export function onOffLabel(t: TranslateFn, on: boolean): string {
  return on ? t("common.on") : t("common.off");
}

export function queueResultLabel(t: TranslateFn, result: string): string {
  const normalized = result.trim().toUpperCase();
  if (normalized === "WIN") return t("action.win");
  if (normalized === "LOSS") return t("action.loss");
  return result;
}
