<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertCoinRequest extends FormRequest
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
        $creating = !$this->route('id');

        return [
            'name' => $creating
                ? ['required', 'string', 'max:50', 'regex:/^[a-z]+$/']
                : ['sometimes', 'string', 'max:50'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'integer', 'in:1,2,3'],
            'czline' => ['sometimes', 'nullable', 'string', 'max:255'],
            'czaddress' => ['sometimes', 'nullable', 'string', 'max:255'],
            'czstatus' => ['sometimes', 'integer', 'in:1,2'],
            'czminnum' => ['sometimes', 'nullable', 'numeric'],
            'txstatus' => ['sometimes', 'integer', 'in:1,2'],
            'txminnum' => ['sometimes', 'nullable', 'numeric'],
            'txmaxnum' => ['sometimes', 'nullable', 'numeric'],
            'sxftype' => ['sometimes', 'integer', 'in:1,2'],
            'txsxf' => ['sometimes', 'nullable', 'numeric'],
            'txsxf_n' => ['sometimes', 'nullable', 'numeric'],
            'bbsxf' => ['sometimes', 'nullable', 'numeric'],
            'hysxf' => ['sometimes', 'nullable', 'numeric'],
            'bank' => ['sometimes', 'nullable', 'numeric'],
            'sort' => ['sometimes', 'integer'],
            'status' => ['sometimes', 'integer', 'in:1,2'],
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
