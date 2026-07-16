<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\Admin\AdminMenuService;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function __construct(
        private readonly AdminMenuService $menuService
    ) {
    }

    public function index(): JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();
        $menus = $this->menuService->getFlatMenus($admin);
        $tree = $this->menuService->getMenus($admin);

        return response()->json([
            'status' => true,
            'menus' => $menus,
            'tree' => $tree,
        ]);
    }
}
