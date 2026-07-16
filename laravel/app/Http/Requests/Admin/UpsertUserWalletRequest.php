<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertUserWalletRequest extends FormRequest
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
            'userid' => [$isCreate ? 'required' : 'sometimes', 'integer'],
            'username' => [$isCreate ? 'required_without:userid' : 'sometimes', 'nullable', 'string'],
            'name' => ['required', 'string', 'max:50'],
            'addr' => ['required', 'string', 'max:500'],
            'czline' => ['sometimes', 'nullable', 'string', 'max:200'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
            'addtime' => ['sometimes', 'nullable'],
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
