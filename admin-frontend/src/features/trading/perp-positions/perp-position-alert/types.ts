import type { PerpPosition } from "../types";

export type PerpPositionAlertData = {
  count: number;
  has_new: boolean;
  positions: PerpPosition[];
};

export type PerpPositionAlertResponse = {
  status: boolean;
  code?: number;
  data: PerpPositionAlertData;
};

export type MarkPerpNotifiedResponse = {
  status: boolean;
  message: string;
  code?: number;
  data?: { updated: number };
};
