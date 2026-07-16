export type PaginatedMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type PaginatedResponse<T> = {
  status: boolean;
  data: T[];
  meta: PaginatedMeta;
};

export type DetailResponse<T> = {
  status: boolean;
  data: T;
};

export type MutationResponse = {
  status: boolean;
  message: string;
};

export type ListParams = {
  page?: number;
  per_page?: number;
  username?: string;
};
