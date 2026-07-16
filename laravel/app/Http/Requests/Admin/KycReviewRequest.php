<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class KycReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rzstatus' => ['required', 'integer', 'in:2,3'],
            'username' => ['required', 'string'],
            'kjid' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $message = $validator->errors()->first();
        $message = match (true) {
            str_contains($message, 'kjid') => 'Máy đào thưởng KYC không hợp lệ.',
            str_contains($message, 'username') => 'Thiếu tên đăng nhập.',
            str_contains($message, 'rzstatus') => 'Trạng thái xác minh không hợp lệ.',
            default => $message,
        };

        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $message,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
