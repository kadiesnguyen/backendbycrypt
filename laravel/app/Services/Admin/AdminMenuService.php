<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Menu;
use Illuminate\Support\Collection;

class AdminMenuService
{
    public function __construct(
        private readonly AdminPermissionService $permissions
    ) {
    }

    /**
     * @return array{main: array<int, array<string, mixed>>, child: array<string, array<int, array<string, mixed>>>}
     */
    public function getMenus(Admin $admin): array
    {
        $developMode = (bool) config('admin.develop_mode', false);

        $mainMenus = $this->visibleMenusQuery($admin, $developMode)
            ->where('pid', 0)
            ->orderBy('sort')
            ->get();

        $main = [];
        foreach ($mainMenus as $item) {
            $url = $this->fullMenuUrl($item->url);
            if (!$this->permissions->isSuperAdmin($admin)
                && !$this->permissions->checkPermission($admin, $url, 2)) {
                continue;
            }

            $main[] = [
                'id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'slug' => strtolower(str_replace('/', '.', $url)),
                'ico_name' => $item->ico_name,
            ];
        }

        $child = [];
        foreach ($main as $mainItem) {
            $groups = $this->visibleMenusQuery($admin, $developMode)
                ->where('pid', $mainItem['id'])
                ->whereNotNull('group')
                ->where('group', '!=', '')
                ->distinct()
                ->pluck('group');

            foreach ($groups as $groupName) {
                $items = $this->visibleMenusQuery($admin, $developMode)
                    ->where('pid', $mainItem['id'])
                    ->where('group', $groupName)
                    ->orderBy('sort')
                    ->get()
                    ->filter(function (Menu $menu) use ($admin) {
                        $url = $this->fullMenuUrl($menu->url);
                        return $this->permissions->isSuperAdmin($admin)
                            || $this->permissions->checkPermission($admin, $url, 1);
                    })
                    ->map(fn (Menu $menu) => [
                        'id' => $menu->id,
                        'title' => $menu->title,
                        'url' => $menu->url,
                        'slug' => strtolower(str_replace('/', '.', $this->fullMenuUrl($menu->url))),
                        'pid' => $menu->pid,
                        'group' => $menu->group,
                    ])
                    ->values()
                    ->all();

                if ($items !== []) {
                    $child[$groupName] = $items;
                }
            }
        }

        return [
            'main' => $main,
            'child' => (object) $child,
        ];
    }

    /**
     * Flat list for sidebar navigation.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getFlatMenus(Admin $admin): array
    {
        $tree = $this->getMenus($admin);
        $flat = [];

        foreach ($tree['main'] as $main) {
            $flat[] = [
                'id' => $main['id'],
                'title' => $main['title'],
                'url' => $main['url'],
                'slug' => $main['slug'],
                'level' => 0,
            ];
        }

        foreach ($tree['child'] as $groupItems) {
            foreach ($groupItems as $item) {
                $flat[] = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'slug' => $item['slug'],
                    'level' => 1,
                    'group' => $item['group'] ?? null,
                ];
            }
        }

        return $flat;
    }

    private function visibleMenusQuery(Admin $admin, bool $developMode)
    {
        $query = Menu::query()->where('hide', 0);

        if (!$developMode) {
            $query->where('is_dev', 0);
        }

        if ((int) $admin->level === 1) {
            $query->where('is_manager', 1);
        }

        return $query;
    }

    private function fullMenuUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return 'admin/index/index';
        }

        if (stripos($url, 'admin/') === 0 || stripos($url, 'Admin/') === 0) {
            return strtolower($url);
        }

        return 'admin/' . strtolower($url);
    }
}
