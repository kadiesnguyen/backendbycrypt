export type QueueEntry = {
  id: number;
  round_no: number;
  result: string;
  addtime: number;
  addtime_text: string;
};

export type QueueListResponse = {
  status: boolean;
  code?: number;
  message?: string;
  data: QueueEntry[];
};

export type QueueAction = "next_win" | "next_loss";

export type QueueActionResponse = {
  status: boolean;
  code?: number;
  message: string;
};

export type UpdateQueueEntryPayload = {
  result: "WIN" | "LOSS";
};

export type UpdateQueueEntryResponse = {
  status: boolean;
  message: string;
  data?: QueueEntry;
};
