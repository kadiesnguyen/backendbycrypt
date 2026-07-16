<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertIssueRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'min' => ['required', 'numeric', 'min:0'],
            'max' => ['required', 'numeric', 'min:0'],
            'open' => ['required', 'integer', 'min:1'],
            'percent' => ['required', 'numeric', 'min:0'],
            'imgs' => ['sometimes', 'nullable', 'string', 'max:225'],
            'content' => ['sometimes', 'nullable', 'string'],
            'status' => ['required', 'in:1,2'],
            'state' => ['required', 'in:1,2'],
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
