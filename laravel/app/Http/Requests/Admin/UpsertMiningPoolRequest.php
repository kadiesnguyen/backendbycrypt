<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertMiningPoolRequest extends FormRequest
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
            'oretitle' => ['required', 'string', 'max:225'],
            'idimg1' => ['required', 'string', 'max:225'],
            'oreimg' => ['sometimes', 'nullable', 'string', 'max:225'],
            'coinname' => ['required', 'string', 'max:225'],
            'cc_coin' => ['required', 'string', 'max:30'],
            'summoney' => ['required'],
            'fmoney' => ['required'],
            'minmoney' => ['required'],
            'maxmoney' => ['required'],
            'rtype' => ['required', 'in:1,2,3'],
            'sfbl' => ['required'],
            'gdnum' => ['required'],
            'gdbl' => ['required', 'string', 'max:50'],
            'rway' => ['required', 'in:1,2'],
            'buytype' => ['required', 'in:1,2'],
            'arrmoney' => ['sometimes', 'nullable', 'string', 'max:225'],
            'buynum' => ['required'],
            'sort' => ['required', 'integer'],
            'status' => ['required', 'in:1,2'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('idimg1') && !$this->filled('oreimg')) {
            $this->merge(['oreimg' => $this->input('idimg1')]);
        }

        if (!$this->filled('arrmoney')) {
            $this->merge(['arrmoney' => 0]);
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
