import { apiClient } from "@/lib/api-client";
import type {
  LoginCredentials,
  LoginResponse,
  MeResponse,
  MenusResponse,
  AdminMenuTree,
  AdminUser,
  ApiMenuItem,
} from "./types";

export async function loginRequest(
  credentials: LoginCredentials,
): Promise<LoginResponse> {
  return apiClient<LoginResponse>("/login", {
    method: "POST",
    body: credentials,
    auth: false,
  });
}

export async function fetchCurrentUser(): Promise<AdminUser> {
  const response = await apiClient<MeResponse>("/me");
  if ("user" in response && response.user) {
    return response.user;
  }
  return response as AdminUser;
}

export function normalizeMenusResponse(response: MenusResponse): {
  menus: ApiMenuItem[];
  tree: AdminMenuTree;
} {
  const menus = Array.isArray(response.menus) ? response.menus : [];
  const rawTree = response.tree;
  const child =
    rawTree?.child && !Array.isArray(rawTree.child)
      ? rawTree.child
      : {};
  const tree: AdminMenuTree = {
    main: rawTree?.main ?? [],
    child,
  };
  return { menus, tree };
}

export async function fetchMenus(): Promise<{
  menus: ApiMenuItem[];
  tree: AdminMenuTree;
}> {
  const response = await apiClient<MenusResponse>("/menus");
  return normalizeMenusResponse(response);
}

export type ChangePasswordPayload = {
  old_password: string;
  new_password: string;
  confirm_password: string;
};

export async function changePassword(
  payload: ChangePasswordPayload,
): Promise<{ status: boolean; message: string }> {
  return apiClient("/auth/password", { method: "PUT", body: payload });
}
