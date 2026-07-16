export type SiteConfig = {
  id: number;
  webname: string | null;
  webtitle: string | null;
  bank_name: string | null;
  bank_acc_no: string | null;
  bank_acc_name: string | null;
  weblogo: string | null;
  waplogo: string | null;
  websildea: string | null;
  websildeb: string | null;
  websildec: string | null;
  wapsilded: string | null;
  webissue: string | null;
  webkj: string | null;
  wapsildea: string | null;
  wapsildeb: string | null;
  wapsildec: string | null;
  wapissue: string | null;
  wapkj: string | null;
  webtjimgs: string | null;
  waptjimgs: string | null;
  webswitch: number;
};

export type SiteConfigResponse = {
  status: boolean;
  data: SiteConfig | null;
};

export type SiteConfigUpdatePayload = Partial<
  Omit<SiteConfig, "id">
>;

export type SiteConfigMutationResponse = {
  status: boolean;
  message: string;
  data?: SiteConfig;
};
