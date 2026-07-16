import type { AdminMenuTree, ApiMenuItem } from "@/features/auth/types";
import type { PendingCounts } from "@/lib/pending-counts/api";

export type MenuRouteEntry = {
  path: string;
  labelKey: string;
};

function normalizeMenuUrl(url: string): string {
  return url
    .trim()
    .replace(/^admin\//i, "")
    .replace(/^Admin\//, "");
}

export const MENU_ROUTE_MAP: Record<string, MenuRouteEntry> = {
  "Index/index": { path: "/dashboard", labelKey: "nav.dashboard" },
  "User/index": { path: "/users", labelKey: "nav.users" },
  "User/agent": { path: "/users/agents", labelKey: "nav.agents" },
  "User/admin": { path: "/users/admins", labelKey: "nav.admins" },
  "User/log": { path: "/users/login-logs", labelKey: "nav.loginLogs" },
  "User/qianbao": { path: "/users/wallets", labelKey: "nav.wallets" },
  "User/coin": { path: "/users/assets", labelKey: "nav.assets" },
  "User/amountlog": { path: "/users/fund-history", labelKey: "nav.fundHistory" },
  "User/online": { path: "/users/online-support", labelKey: "nav.onlineSupport" },
  "User/noticelist": { path: "/users/notices", labelKey: "nav.notices" },
  "Finance/index": { path: "/finance/bills", labelKey: "nav.bills" },
  "Finance/myzr": { path: "/finance/deposits", labelKey: "nav.deposits" },
  "Finance/myzc": { path: "/finance/withdrawals", labelKey: "nav.withdrawals" },
  "Finance/fund": { path: "/finance/transfers", labelKey: "nav.transfers" },
  "Trade/index": { path: "/trading/orders", labelKey: "nav.orders" },
  "Trade/sethy": { path: "/trading/settings", labelKey: "nav.contractSettings" },
  "Trade/resultqueue": { path: "/trading/queue", labelKey: "nav.queue" },
  "Trade/tyorder": { path: "/trading/trial-orders", labelKey: "nav.trialOrders" },
  "Trade/hylog": { path: "/trading/close-history", labelKey: "nav.closeHistory" },
  "Trade/bbsetting": { path: "/trading/spot-settings", labelKey: "nav.spotSettings" },
  "Trade/bbsjlist": { path: "/trading/spot-market", labelKey: "nav.spotMarket" },
  "Trade/bbxjlist": { path: "/trading/spot-limit", labelKey: "nav.spotLimit" },
  "Trade/market": { path: "/trading/platform-markets", labelKey: "nav.platformMarkets" },
  "Article/index": { path: "/content/articles", labelKey: "nav.articles" },
  "News/index": { path: "/content/news", labelKey: "nav.news" },
  "Issue/index": { path: "/staking", labelKey: "nav.staking" },
  "Issue/log": { path: "/staking", labelKey: "nav.staking" },
  "Kuangm/index": { path: "/miners", labelKey: "nav.miners" },
  "Kuangm/kjlist": { path: "/miners/active", labelKey: "nav.minerActive" },
  "Kuangm/djprofit": { path: "/miners/frozen-profits", labelKey: "nav.frozenProfits" },
  "Kuangm/kjsylist": { path: "/miners/profits", labelKey: "nav.minerProfits" },
  "Kuangm/overlist": { path: "/miners/expired", labelKey: "nav.minerExpired" },
  "Config/index": { path: "/settings/site", labelKey: "nav.site" },
  "Config/qita": { path: "/settings/system", labelKey: "nav.system" },
  "Config/coin": { path: "/settings/coins", labelKey: "nav.coins" },
  "Config/depositport": { path: "/settings/deposit-ports", labelKey: "nav.depositPorts" },
  "Config/ctmarket": { path: "/settings/ct-markets", labelKey: "nav.ctMarkets" },
  "Config/marketo": { path: "/settings/platform-markets", labelKey: "nav.platformMarkets" },
  "Config/daohang": { path: "/settings/navigation", labelKey: "nav.navigation" },
  "Config/dhfooter": { path: "/settings/footer", labelKey: "nav.footer" },
};

export const GROUP_LABEL_KEYS: Record<string, string> = {
  "Quick Actions": "group.quickActions",
  Content: "group.content",
  "User Management": "group.userManagement",
  "Financial Management": "group.financialManagement",
  "Quick Contract": "group.quickContract",
  "Quick Trading": "group.quickTrading",
  "Pair Trading": "group.pairTrading",
  "Robot Configuration": "group.robotConfiguration",
  "New Purchase Management": "group.newPurchaseManagement",
  "Miner Management": "group.minerManagement",
  System: "group.system",
  Navigation: "group.navigation",
  "Website Configuration": "group.websiteConfiguration",
  "Development Group": "group.developmentGroup",
};

/** Sidebar section order (merged groups are omitted). */
export const SIDEBAR_GROUP_ORDER = [
  "Quick Actions",
  "User Management",
  "Quick Contract",
  "Financial Management",
  "Miner Management",
  "New Purchase Management",
  "System",
] as const;

/** Legacy ThinkPHP groups folded into other sections — no standalone header. */
export const MERGED_AWAY_GROUPS = new Set([
  "Pair Trading",
  "Robot Configuration",
  "Quick Trading",
  "Content",
  "Navigation",
  "Website Configuration",
  "Development Group",
]);

/** Move menu entries into Hợp đồng nhanh (Quick Contract). */
export const MENU_URL_TARGET_GROUP: Record<string, string> = {
  "Trade/bbsetting": "Quick Contract",
  "Trade/bbsjlist": "Quick Contract",
  "Trade/bbxjlist": "Quick Contract",
  "Trade/hylog": "Quick Contract",
  "Trade/market": "Quick Contract",
};

/** Item order inside Hợp đồng nhanh. */
export const QUICK_CONTRACT_ITEM_ORDER = [
  "Trade/index",
  "Trade/sethy",
  "Trade/bbsetting",
  "Trade/bbsjlist",
  "Trade/bbxjlist",
  "Trade/hylog",
  "Trade/market",
];

/** ThinkPHP menu URLs hidden from the Next.js sidebar. */
export const HIDDEN_MENU_URLS = new Set([
  "User/online",
  "User/noticelist",
  "Trade/tyorder",
  "Trade/resultqueue",
  "News/index",
  "Article/index",
  "Config/daohang",
  "Config/dhfooter",
  "Issue/log",
  "Index/index",
]);

/** Sidebar paths that show a pending-count badge. */
export const MENU_BADGE_BY_PATH: Record<string, keyof PendingCounts> = {
  "/finance/deposits": "deposits",
  "/finance/withdrawals": "withdrawals",
  "/trading/orders": "contract_orders",
};

export const DASHBOARD_PATH = "/dashboard";

export function resolveMenuPath(url: string): string | undefined {
  const normalized = normalizeMenuUrl(url);
  return MENU_ROUTE_MAP[normalized]?.path;
}

export function resolveMenuLabelKey(url: string): string | undefined {
  const normalized = normalizeMenuUrl(url);
  return MENU_ROUTE_MAP[normalized]?.labelKey;
}

export function resolveGroupLabelKey(groupName: string): string {
  return GROUP_LABEL_KEYS[groupName] ?? groupName;
}

type IndexedMenuItem = {
  item: ApiMenuItem;
  sourceGroup: string;
  order: number;
};

function isVisibleMenuItem(item: ApiMenuItem): boolean {
  const url = normalizeMenuUrl(item.url);
  if (HIDDEN_MENU_URLS.has(url)) {
    return false;
  }
  const path = resolveMenuPath(item.url);
  if (!path) {
    return false;
  }
  return true;
}

function targetGroupForItem(url: string, sourceGroup: string): string | null {
  const normalized = normalizeMenuUrl(url);
  if (MENU_URL_TARGET_GROUP[normalized]) {
    return MENU_URL_TARGET_GROUP[normalized];
  }
  if (MERGED_AWAY_GROUPS.has(sourceGroup)) {
    return null;
  }
  if (!SIDEBAR_GROUP_ORDER.includes(sourceGroup as (typeof SIDEBAR_GROUP_ORDER)[number])) {
    return null;
  }
  return sourceGroup;
}

function sortQuickContractItems(items: ApiMenuItem[]): ApiMenuItem[] {
  const orderIndex = (url: string) => {
    const index = QUICK_CONTRACT_ITEM_ORDER.indexOf(normalizeMenuUrl(url));
    return index === -1 ? QUICK_CONTRACT_ITEM_ORDER.length : index;
  };

  return [...items].sort((a, b) => orderIndex(a.url) - orderIndex(b.url));
}

function sortGroupItems(groupName: string, items: ApiMenuItem[]): ApiMenuItem[] {
  if (groupName === "Quick Contract") {
    return sortQuickContractItems(items);
  }
  return items;
}

function restructureMenuTree(source: AdminMenuTree): AdminMenuTree["child"] {
  const grouped = new Map<string, Map<string, IndexedMenuItem>>();
  let order = 0;

  for (const [sourceGroup, items] of Object.entries(source.child)) {
    for (const item of items) {
      if (!isVisibleMenuItem(item)) {
        continue;
      }

      const targetGroup = targetGroupForItem(item.url, sourceGroup);
      if (!targetGroup) {
        continue;
      }

      const urlKey = normalizeMenuUrl(item.url);
      const bucket = grouped.get(targetGroup) ?? new Map<string, IndexedMenuItem>();
      if (!bucket.has(urlKey)) {
        bucket.set(urlKey, { item, sourceGroup, order: order++ });
      }
      grouped.set(targetGroup, bucket);
    }
  }

  const child: AdminMenuTree["child"] = {};

  for (const groupName of SIDEBAR_GROUP_ORDER) {
    const bucket = grouped.get(groupName);
    if (!bucket || bucket.size === 0) {
      continue;
    }

    const items = Array.from(bucket.values())
      .sort((a, b) => a.order - b.order)
      .map(({ item }) => item);

    child[groupName] = sortGroupItems(groupName, items);
  }

  return child;
}

/** Fallback sidebar when API returns no menus (e.g. admin without RBAC group). */
export const FALLBACK_MENU_TREE: AdminMenuTree = {
  main: [],
  child: {
    "User Management": [
      { id: "fb-user", title: "Users", url: "User/index", slug: "admin.user.index" },
      { id: "fb-agent", title: "Agents", url: "User/agent", slug: "admin.user.agent" },
      { id: "fb-admin", title: "Admins", url: "User/admin", slug: "admin.user.admin" },
      { id: "fb-log", title: "Login logs", url: "User/log", slug: "admin.user.log" },
      { id: "fb-wallet", title: "Wallets", url: "User/qianbao", slug: "admin.user.qianbao" },
      { id: "fb-assets", title: "Assets", url: "User/coin", slug: "admin.user.coin" },
      { id: "fb-fund", title: "Fund history", url: "User/amountlog", slug: "admin.user.amountlog" },
    ],
    "Financial Management": [
      { id: "fb-bills", title: "Bills", url: "Finance/index", slug: "admin.finance.index" },
      { id: "fb-dep", title: "Deposits", url: "Finance/myzr", slug: "admin.finance.myzr" },
      { id: "fb-wd", title: "Withdrawals", url: "Finance/myzc", slug: "admin.finance.myzc" },
      { id: "fb-tr", title: "Transfers", url: "Finance/fund", slug: "admin.finance.fund" },
    ],
    "Quick Contract": [
      { id: "fb-ord", title: "Orders", url: "Trade/index", slug: "admin.trade.index" },
      { id: "fb-set", title: "Settings", url: "Trade/sethy", slug: "admin.trade.sethy" },
      { id: "fb-spot-set", title: "Spot settings", url: "Trade/bbsetting", slug: "admin.trade.bbsetting" },
      { id: "fb-spot-m", title: "Spot market", url: "Trade/bbsjlist", slug: "admin.trade.bbsjlist" },
      { id: "fb-spot-l", title: "Spot limit", url: "Trade/bbxjlist", slug: "admin.trade.bbxjlist" },
      { id: "fb-close", title: "Close history", url: "Trade/hylog", slug: "admin.trade.hylog" },
      { id: "fb-platform", title: "Platform markets", url: "Trade/market", slug: "admin.trade.market" },
    ],
    "New Purchase Management": [
      { id: "fb-stake", title: "Staking", url: "Issue/index", slug: "admin.issue.index" },
    ],
    "Miner Management": [
      { id: "fb-miner", title: "Miners", url: "Kuangm/index", slug: "admin.kuangm.index" },
      { id: "fb-active", title: "Active", url: "Kuangm/kjlist", slug: "admin.kuangm.kjlist" },
      { id: "fb-frozen", title: "Frozen", url: "Kuangm/djprofit", slug: "admin.kuangm.djprofit" },
      { id: "fb-profit", title: "Profits", url: "Kuangm/kjsylist", slug: "admin.kuangm.kjsylist" },
      { id: "fb-exp", title: "Expired", url: "Kuangm/overlist", slug: "admin.kuangm.overlist" },
    ],
    System: [
      { id: "fb-site", title: "Site", url: "Config/index", slug: "admin.config.index" },
      { id: "fb-sys", title: "System", url: "Config/qita", slug: "admin.config.qita" },
      { id: "fb-coin", title: "Coins", url: "Config/coin", slug: "admin.config.coin" },
      { id: "fb-port", title: "Deposit ports", url: "Config/depositport", slug: "admin.config.depositport" },
      { id: "fb-ct", title: "CT markets", url: "Config/ctmarket", slug: "admin.config.ctmarket" },
      { id: "fb-pm", title: "Platform markets", url: "Config/marketo", slug: "admin.config.marketo" },
    ],
  },
};

export function resolveMenuTree(menuTree: AdminMenuTree): AdminMenuTree {
  const source = Object.values(menuTree.child).some((items) => items.length > 0)
    ? menuTree
    : FALLBACK_MENU_TREE;

  return {
    ...source,
    child: restructureMenuTree(source),
  };
}
