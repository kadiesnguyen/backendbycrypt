"use client";

import { useEffect, useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useRouter } from "next/navigation";
import {
  clearAdminToken,
  getAdminToken,
  setAdminToken,
} from "@/lib/api-client";
import {
  fetchCurrentUser,
  fetchMenus,
  loginRequest,
} from "./api";
import type { AdminMenuTree, AdminUser, LoginCredentials, ApiMenuItem } from "./types";

export const authQueryKeys = {
  me: ["admin", "me"] as const,
  menus: ["admin", "menus"] as const,
};

const EMPTY_TREE: AdminMenuTree = { main: [], child: {} };

export function useAuthSession() {
  const [hasToken, setHasToken] = useState(false);

  useEffect(() => {
    setHasToken(Boolean(getAdminToken()));
  }, []);

  const meQuery = useQuery({
    queryKey: authQueryKeys.me,
    queryFn: fetchCurrentUser,
    enabled: hasToken,
    retry: false,
  });

  const menusQuery = useQuery({
    queryKey: authQueryKeys.menus,
    queryFn: fetchMenus,
    enabled: hasToken && meQuery.isSuccess,
    retry: false,
  });

  const menuData = menusQuery.data;
  const menus = (menuData?.menus ?? []) as ApiMenuItem[];
  const menuTree = menuData?.tree ?? EMPTY_TREE;

  return {
    user: meQuery.data as AdminUser | undefined,
    menus,
    menuTree,
    isLoading: !hasToken || meQuery.isLoading || menusQuery.isLoading,
    isError: meQuery.isError || menusQuery.isError,
    error: meQuery.error ?? menusQuery.error,
    refetch: async () => {
      await Promise.all([meQuery.refetch(), menusQuery.refetch()]);
    },
    isAuthenticated: Boolean(meQuery.data),
  };
}

export function useLogin() {
  const router = useRouter();
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (credentials: LoginCredentials) => loginRequest(credentials),
    onSuccess: (data) => {
      setAdminToken(data.token);
      if (data.user) {
        queryClient.setQueryData(authQueryKeys.me, data.user);
      }
      router.replace("/dashboard");
    },
  });
}

export function useLogout() {
  const router = useRouter();
  const queryClient = useQueryClient();

  return () => {
    clearAdminToken();
    queryClient.clear();
    router.replace("/login");
  };
}
