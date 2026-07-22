<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListLoansRequest;
use App\Http\Requests\Admin\ReviewLoanRequest;
use App\Http\Resources\Admin\LoanResource;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    public function __construct(private readonly LoanService $loans)
    {
    }

    public function index(ListLoansRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $username = $request->input('username', $request->input('name'));
        $status = $request->input('status');

        $query = Loan::query()->orderByDesc('id');

        if ($username !== null && $username !== '') {
            $query->where('username', $username);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => LoanResource::collection(collect($paginator->items())),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function approve(ReviewLoanRequest $request, int $id): JsonResponse
    {
        return $this->review($id, 'approve', $request->input('note'));
    }

    public function reject(ReviewLoanRequest $request, int $id): JsonResponse
    {
        return $this->review($id, 'reject', $request->input('note'));
    }

    private function review(int $id, string $action, ?string $note): JsonResponse
    {
        if ($id <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Missing params.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $loan = $action === 'approve'
                ? $this->loans->approve($id, $note)
                : $this->loans->reject($id, $note);
        } catch (RuntimeException $e) {
            return match ($e->getMessage()) {
                'not_found' => response()->json([
                    'status' => false,
                    'message' => 'Loan does not exist.',
                ], Response::HTTP_NOT_FOUND),
                'processed' => response()->json([
                    'status' => false,
                    'message' => 'The loan has been processed.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY),
                'no_wallet' => response()->json([
                    'status' => false,
                    'message' => 'User wallet not found.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR),
                default => response()->json([
                    'status' => false,
                    'message' => 'System error.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR),
            };
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully.',
            'data' => new LoanResource($loan),
        ]);
    }
}
