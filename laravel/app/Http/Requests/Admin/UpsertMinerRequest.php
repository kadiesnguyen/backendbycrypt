<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertMinerRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:225'],
            'rtype' => ['required', 'in:1,2'],
            'type' => ['required', 'in:1,2'],
            'sharebl' => ['sometimes', 'nullable', 'string', 'max:30'],
            'content' => ['sometimes', 'nullable', 'string'],
            'imgs' => ['sometimes', 'nullable', 'string', 'max:225'],
            'dayoutnum' => ['required'],
            'outtype' => ['required', 'in:1,2'],
            'outcoin' => ['required', 'string', 'max:30'],
            'pricenum' => ['required'],
            'pricecoin' => ['required', 'string', 'max:30'],
            'buymax' => ['required', 'integer', 'min:0'],
            'cycle' => ['required', 'integer', 'min:1'],
            'suanl' => ['required'],
            'allnum' => ['required', 'integer', 'min:0'],
            'ycnum' => ['required', 'integer', 'min:0'],
            'jlnum' => ['required'],
            'jlcoin' => ['required', 'string', 'max:30'],
            'buyask' => ['required', 'in:1,2'],
            'asknum' => ['required', 'integer', 'min:0'],
            'djout' => ['required', 'in:1,2'],
            'djday' => ['sometimes', 'nullable', 'integer', 'min:0'],
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
