<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdatePlatformMarketQuotesRequest extends FormRequest
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
            'faxingjia' => ['sometimes', 'nullable', 'string', 'max:50'],
            'new_price' => ['sometimes', 'nullable', 'string', 'max:50'],
            'buy_price' => ['sometimes', 'nullable', 'string', 'max:50'],
            'sell_price' => ['sometimes', 'nullable', 'string', 'max:50'],
            'min_price' => ['sometimes', 'nullable', 'string', 'max:50'],
            'max_price' => ['sometimes', 'nullable', 'string', 'max:50'],
            'volume' => ['sometimes', 'nullable', 'string', 'max:50'],
            'change' => ['sometimes', 'nullable', 'string', 'max:50'],
            'hou_price' => ['sometimes', 'nullable', 'string', 'max:50'],
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
