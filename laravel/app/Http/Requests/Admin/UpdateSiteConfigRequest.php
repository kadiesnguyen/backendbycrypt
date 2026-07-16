<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpdateSiteConfigRequest extends FormRequest
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
            'webname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'webtitle' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_acc_no' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_acc_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'weblogo' => ['sometimes', 'nullable', 'string'],
            'waplogo' => ['sometimes', 'nullable', 'string'],
            'websildea' => ['sometimes', 'nullable', 'string'],
            'websildeb' => ['sometimes', 'nullable', 'string'],
            'websildec' => ['sometimes', 'nullable', 'string'],
            'wapsilded' => ['sometimes', 'nullable', 'string'],
            'webissue' => ['sometimes', 'nullable', 'string'],
            'webkj' => ['sometimes', 'nullable', 'string'],
            'wapsildea' => ['sometimes', 'nullable', 'string'],
            'wapsildeb' => ['sometimes', 'nullable', 'string'],
            'wapsildec' => ['sometimes', 'nullable', 'string'],
            'wapissue' => ['sometimes', 'nullable', 'string'],
            'wapkj' => ['sometimes', 'nullable', 'string'],
            'webtjimgs' => ['sometimes', 'nullable', 'string'],
            'waptjimgs' => ['sometimes', 'nullable', 'string'],
            'webswitch' => ['sometimes', 'integer', 'in:1,2'],
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
