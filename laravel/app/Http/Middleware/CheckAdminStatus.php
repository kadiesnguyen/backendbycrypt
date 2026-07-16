<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if (!$admin instanceof Admin) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated admin.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ((int) $admin->status !== 1) {
            auth('admin')->logout();

            return response()->json([
                'status' => false,
                'message' => 'Admin account is disabled.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
