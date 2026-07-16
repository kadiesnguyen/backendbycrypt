<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertUserRequest extends FormRequest
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
        $isCreate = $this->isMethod('post');

        return [
            'username' => [$isCreate ? 'required' : 'sometimes', 'string', 'max:200'],
            'password' => [$isCreate ? 'required' : 'sometimes', 'nullable', 'string', 'max:200'],
            'paypassword' => ['sometimes', 'nullable', 'string', 'max:200'],
            'fullname' => ['sometimes', 'nullable', 'string', 'max:200'],
            'phonenumber' => ['sometimes', 'nullable', 'string', 'max:50'],
            'cccd' => ['sometimes', 'nullable', 'string', 'max:50'],
            'status' => ['sometimes', 'integer', 'in:1,2'],
            'txstate' => ['sometimes', 'integer', 'in:1,2'],
            'bank_name' => ['sometimes', 'nullable', 'string', 'max:200'],
            'bank_acc_no' => ['sometimes', 'nullable', 'string', 'max:200'],
            'bank_acc_name' => ['sometimes', 'nullable', 'string', 'max:200'],
            'wallet' => ['sometimes', 'nullable', 'string', 'max:500'],
            'invit' => ['sometimes', 'nullable', 'string', 'max:50'],
            'invit_1' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'invit_2' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'invit_3' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'hy_result_mode' => ['sometimes', 'integer', 'in:0,1,2'],
            'kefu' => ['sometimes', 'nullable', 'integer', 'min:0'],
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
