<?php

  namespace App\Http\Middleware;

  use Closure;
  use Illuminate\Http\Request;
  use Symfony\Component\HttpFoundation\Response;

  class CheckUserStatus
  {
      public function handle(Request $request, Closure $next): Response
      {
          $user = $request->user('api');

          if ($user && $user->status != 1) {
              return response()->json([
                  'status' => false,
                  'message' => 'Tài khoản bị khóa hoặc không hoạt động',
              ], 403);
          }

          return $next($request);
      }
  }