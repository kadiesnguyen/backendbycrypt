<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\AuthGroup;
use App\Models\AuthRule;
use App\Support\RbacRuleMapper;

class AdminPermissionService
{
    /** @var array<string, array<int, string>> */
    private static array $cache = [];

    public function isSuperAdmin(Admin $admin): bool
    {
        if (in_array((int) $admin->id, config('admin.superadmin_ids', [1]), true)) {
            return true;
        }

        return (int) $admin->level === 0;
    }

    /**
     * @param int|array<int> $types
     */
    public function checkPermission(
        Admin $admin,
        string $rule,
        int|array $types = [1, 2],
        string $relation = 'or'
    ): bool {
        if ($this->isSuperAdmin($admin)) {
            return true;
        }

        $types = is_array($types) ? $types : [$types];
        $normalizedRule = RbacRuleMapper::normalizeRule($rule);
        $rules = $this->getRulesForAdmin($admin, $types);

        if ($relation === 'and') {
            return in_array($normalizedRule, $rules, true);
        }

        return in_array($normalizedRule, $rules, true);
    }

    /**
     * @param int|array<int> $types
     * @return array<int, string>
     */
    public function getRulesForAdmin(Admin $admin, int|array $types = [1, 2]): array
    {
        if ($this->isSuperAdmin($admin)) {
            return AuthRule::query()
                ->where('status', 1)
                ->whereIn('type', (array) $types)
                ->pluck('name')
                ->map(fn (string $name) => strtolower($name))
                ->unique()
                ->values()
                ->all();
        }

        $typesKey = implode(',', array_map('strval', (array) $types));
        $cacheKey = "{$admin->id}:{$typesKey}";

        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $groupIds = $admin->authGroups()
            ->where('tw_auth_group.status', 1)
            ->where('tw_auth_group.module', 'admin')
            ->where('tw_auth_group.type', 1)
            ->pluck('tw_auth_group.id');

        if ($groupIds->isEmpty()) {
            return self::$cache[$cacheKey] = [];
        }

        $groups = AuthGroup::query()
            ->whereIn('id', $groupIds)
            ->where('status', 1)
            ->get(['rules']);

        $ruleIds = [];
        foreach ($groups as $group) {
            if (empty($group->rules)) {
                continue;
            }
            foreach (explode(',', $group->rules) as $id) {
                $id = (int) trim($id);
                if ($id > 0) {
                    $ruleIds[$id] = true;
                }
            }
        }

        if ($ruleIds === []) {
            return self::$cache[$cacheKey] = [];
        }

        $rules = AuthRule::query()
            ->whereIn('id', array_keys($ruleIds))
            ->where('status', 1)
            ->whereIn('type', (array) $types)
            ->pluck('name')
            ->map(fn (string $name) => strtolower($name))
            ->unique()
            ->values()
            ->all();

        return self::$cache[$cacheKey] = $rules;
    }

    public function flushCache(int $adminId): void
    {
        foreach (array_keys(self::$cache) as $key) {
            if (str_starts_with($key, "{$adminId}:")) {
                unset(self::$cache[$key]);
            }
        }
    }
}
