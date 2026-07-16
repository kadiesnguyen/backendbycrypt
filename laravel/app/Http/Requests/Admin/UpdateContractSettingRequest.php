<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateContractSettingRequest extends FormRequest
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
            'hy_id' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'id' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'hy_sxf' => ['required', 'numeric'],
            'hy_time' => ['required', 'string', 'max:225'],
            'hy_ykbl' => ['required', 'string', 'max:225'],
            'hy_tzed' => ['required', 'string', 'max:225'],
            'hy_min' => ['required', 'string', 'max:255'],
            'hy_min_per_frame' => ['sometimes', 'nullable', 'string', 'max:255'],
            'hy_max_per_frame' => ['sometimes', 'nullable', 'string', 'max:255'],
            'hy_kstime' => ['required', 'string', 'max:225'],
            'hy_ksid' => ['sometimes', 'nullable', 'string', 'max:225'],
            'hy_ylid' => ['sometimes', 'nullable', 'string', 'max:225'],
            'hy_fkgl' => ['sometimes', 'nullable', 'string', 'max:225'],
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
