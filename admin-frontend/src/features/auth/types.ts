export type AdminUser = {
  id: number | string;
  username: string;
  name?: string;
  email?: string;
  role?: string;
};

export type ApiMenuItem = {
  id: number | string;
  title: string;
  url: string;
  slug: string;
  ico_name?: string;
  pid?: number;
  group?: string;
  level?: number;
};

export type AdminMenuTree = {
  main: ApiMenuItem[];
  child: Record<string, ApiMenuItem[]>;
};

export type LoginCredentials = {
  username: string;
  password: string;
};

export type LoginResponse = {
  token: string;
  user?: AdminUser;
};

export type MeResponse = AdminUser | { user: AdminUser };

export type MenusResponse = {
  status?: boolean;
  menus: ApiMenuItem[];
  tree?: AdminMenuTree;
};
