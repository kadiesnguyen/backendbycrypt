export type ContractSetting = {
  id: number;
  hy_sxf: string | number;
  hy_time: string;
  hy_ykbl: string;
  hy_tzed: string;
  hy_min: string;
  hy_min_per_frame: string | null;
  hy_max_per_frame: string | null;
  hy_kstime: string;
  hy_ksid: string | null;
  hy_ylid: string | null;
  hy_fkgl: string | null;
  checkin_rewards: string | null;
};

export type ContractSettingResponse = {
  status: boolean;
  data: ContractSetting | null;
};

export type UpdateContractSettingPayload = {
  hy_id?: number;
  id?: number;
  hy_sxf: string | number;
  hy_time: string;
  hy_ykbl: string;
  hy_tzed: string;
  hy_min: string;
  hy_min_per_frame?: string;
  hy_max_per_frame?: string;
  hy_kstime: string;
  hy_ksid?: string;
  hy_ylid?: string;
  hy_fkgl?: string;
};

export type ContractSettingUpdateResponse = {
  status: boolean;
  message: string;
  data: ContractSetting;
};

export type TradingMarket = {
  id: number;
  name: string;
  round: number | string;
  fee_buy: string | null;
  fee_sell: string | null;
  shuadan: number | string | null;
  trade: number | string | null;
  status: number | string | null;
  jiaoyiqu: number | string | null;
  buy_min: string | null;
  buy_max: string | null;
  sell_min: string | null;
  sell_max: string | null;
  trade_min: string | null;
  trade_max: string | null;
  new_price: string | null;
  addtime: number | null;
};

export type MarketsListMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type MarketsListResponse = {
  status: boolean;
  data: TradingMarket[];
  meta: MarketsListMeta;
};

export type MarketsListParams = {
  page?: number;
  per_page?: number;
  field?: string;
  name?: string;
};

export type MarketFormMeta = {
  trading_areas: { value: number; label: string }[];
  coins: { name: string; title: string }[];
  hours: string[];
  minutes: string[];
};

export type MarketFormMetaResponse = {
  status: boolean;
  data: MarketFormMeta;
};

export type MarketDetailResponse = {
  status: boolean;
  data: TradingMarket;
  meta: MarketFormMeta;
};

export type UpsertMarketPayload = {
  buyname?: string;
  sellname?: string;
  jiaoyiqu?: number | string;
  round: number | string;
  fee_buy?: string;
  fee_sell?: string;
  buy_min?: string;
  buy_max?: string;
  sell_min?: string;
  sell_max?: string;
  trade_min?: string;
  trade_max?: string;
  trade?: number | string;
  status?: number | string;
  shuadan?: number | string;
  zhang?: string;
  die?: string;
};

export type MarketMutationResponse = {
  status: boolean;
  message: string;
  data: TradingMarket;
};

export type ActionResponse = {
  status: boolean;
  message: string;
};
