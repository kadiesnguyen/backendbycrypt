<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class SendNoticeRequest extends FormRequest
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
            'type' => ['required', 'integer', 'in:1,2'],
            'user_id' => ['required_if:type,1', 'nullable', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:225'],
            'content' => ['required', 'string'],
            'imgs' => ['sometimes', 'nullable', 'string', 'max:225'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $message = $validator->errors()->first();
        $message = match (true) {
            str_contains($message, 'user id') => 'Thiếu ID thành viên khi gửi thông báo cá nhân.',
            str_contains($message, 'title') => 'Tiêu đề không được để trống.',
            str_contains($message, 'content') => 'Nội dung không được để trống.',
            default => $message,
        };

        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $message,
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
