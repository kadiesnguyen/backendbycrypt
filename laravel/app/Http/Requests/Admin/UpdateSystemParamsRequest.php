<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateSystemParamsRequest extends FormRequest
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
            'kefu' => ['sometimes', 'nullable', 'string', 'max:255'],
            'appeal' => ['sometimes', 'nullable', 'string', 'max:255'],
            'smsemail' => ['sometimes', 'nullable', 'string', 'max:255'],
            'emailcode' => ['sometimes', 'nullable', 'string', 'max:255'],
            'smstemple' => ['sometimes', 'nullable', 'string', 'max:255'],
            'tgtext' => ['sometimes', 'nullable', 'string', 'max:255'],
            'gfemail' => ['sometimes', 'nullable', 'string', 'max:255'],
            'footertext' => ['sometimes', 'nullable', 'string', 'max:255'],
            'telegram' => ['sometimes', 'nullable', 'string', 'max:255'],
            'tymoney' => ['sometimes', 'nullable', 'numeric'],
            'regswitch' => ['sometimes', 'integer', 'in:1,2'],
            'tbswitch' => ['sometimes', 'integer', 'in:1,2'],
            'regjl' => ['sometimes', 'integer', 'in:1,2'],
            'checkin_rewards' => ['sometimes', 'nullable', 'string'],
            'checkin_notify_status' => ['sometimes', 'integer', 'in:1,2'],
            'checkin_notify' => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
