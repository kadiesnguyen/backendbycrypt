<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdatePlatformMarketRobotRequest extends FormRequest
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
            'shuadan' => ['sometimes', 'nullable', 'in:0,1'],
            'sdhigh' => ['sometimes', 'nullable', 'string', 'max:50'],
            'sdlow' => ['sometimes', 'nullable', 'string', 'max:50'],
            'sdhigh_num' => ['sometimes', 'nullable', 'string', 'max:50'],
            'sdlow_num' => ['sometimes', 'nullable', 'string', 'max:50'],
            'round' => ['sometimes', 'nullable', 'in:0,1,2,3,4,5,6'],
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
