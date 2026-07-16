<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertFrontendNavigationRequest extends FormRequest
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
            'lang' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:30'],
            'title' => ['required', 'string', 'max:30'],
            'url' => ['required', 'string', 'max:100'],
            'sort' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
            'get_login' => ['sometimes', 'boolean'],
            'access' => ['sometimes', 'boolean'],
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
