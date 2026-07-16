<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeAdminPasswordRequest;
use App\Models\Admin;
use App\Services\Admin\AdminMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $admin = Admin::query()
            ->where('username', $request->input('username'))
            ->first();

        if (!$admin || (int) $admin->status !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect username or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $password = (string) $request->input('password');
        $stored = (string) $admin->password;
        $valid = hash_equals($stored, md5($password)) || hash_equals($stored, $password);

        if (!$valid) {
            return response()->json([
                'status' => false,
                'message' => 'Incorrect username or password.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $admin->forceFill([
            'last_login_time' => time(),
            'last_login_ip' => ip2long($request->ip()) ?: 0,
        ])->save();

        $token = auth('admin')->login($admin);

        return response()->json([
            'status' => true,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'user' => $this->adminPayload($admin),
        ]);
    }

    public function me(): JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();

        return response()->json([
            'status' => true,
            'user' => $this->adminPayload($admin),
        ]);
    }

    public function logout(): JsonResponse
    {
        auth('admin')->logout();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Change password for the logged-in admin (ThinkPHP User/setpwd).
     */
    public function changePassword(ChangeAdminPasswordRequest $request): JsonResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin')->user();

        $oldPassword = (string) $request->input('old_password');
        $newPassword = (string) $request->input('new_password');
        $stored = (string) $admin->password;

        $oldValid = hash_equals($stored, md5($oldPassword)) || hash_equals($stored, $oldPassword);

        if (!$oldValid) {
            return response()->json([
                'status' => false,
                'message' => 'Old password is incorrect.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (hash_equals(md5($newPassword), $stored) || hash_equals($newPassword, $stored)) {
            return response()->json([
                'status' => false,
                'message' => 'New password must be different from the old password.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $admin->forceFill(['password' => md5($newPassword)])->save();

        auth('admin')->logout();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully. Please sign in again.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function adminPayload(Admin $admin): array
    {
        return [
            'id' => $admin->id,
            'username' => $admin->username,
            'nickname' => $admin->nickname,
            'email' => $admin->email,
            'level' => (int) $admin->level,
            'status' => (int) $admin->status,
        ];
    }
}
