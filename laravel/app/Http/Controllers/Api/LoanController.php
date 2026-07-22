<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoanController extends Controller
{
    public function __construct(private readonly LoanService $loans)
    {
    }

    public function config(): JsonResponse
    {
        $user = JWTAuth::user();

        return response()->json([
            'status' => true,
            'data' => $this->loans->configForUser($user),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'gt:0'],
            'img_front' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:16384'],
            'img_back' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:16384'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $loan = $this->loans->apply(
                JWTAuth::user(),
                (float) $request->input('amount'),
                $request->file('img_front'),
                $request->file('img_back'),
            );
        } catch (RuntimeException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'status' => true,
            'message' => 'Loan application submitted.',
            'data' => new LoanResource($loan),
        ], Response::HTTP_CREATED);
    }

    public function history(Request $request): JsonResponse
    {
        $user = JWTAuth::user();
        $status = $request->query('status', 'all');

        $query = Loan::query()
            ->where('user_id', $user->id)
            ->orderByDesc('id');

        if ($status && $status !== 'all') {
            if (!in_array($status, [
                Loan::STATUS_PENDING,
                Loan::STATUS_REJECTED,
                Loan::STATUS_ACTIVE,
                Loan::STATUS_REPAID,
                Loan::STATUS_OVERDUE,
            ], true)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid status filter.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $query->where('status', $status);
        }

        $items = $query->limit(100)->get();

        return response()->json([
            'status' => true,
            'data' => LoanResource::collection($items),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = JWTAuth::user();
        $loan = Loan::query()
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$loan) {
            return response()->json([
                'status' => false,
                'message' => 'Loan not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => new LoanResource($loan),
        ]);
    }
}
