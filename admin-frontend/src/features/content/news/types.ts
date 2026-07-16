export type AdminNews = {
  id: number;
  title: string;
  coverImage: string | null;
  content: string | null;
  status: number;
  status_label: string;
  created_at: string | null;
  updated_at: string | null;
};

export type NewsListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type NewsListResponse = {
  status: boolean;
  data: AdminNews[];
  meta: NewsListMeta;
};

export type NewsListParams = {
  page?: number;
  per_page?: number;
};

export type NewsDetailResponse = {
  status: boolean;
  data: AdminNews;
};

export type NewsUpsertPayload = {
  title: string;
  coverImage?: string;
  content?: string;
  status?: number;
};

export type NewsMutationResponse = {
  status: boolean;
  message: string;
  data?: AdminNews;
};

export type NewsDeleteResponse = {
  status: boolean;
  message: string;
};
