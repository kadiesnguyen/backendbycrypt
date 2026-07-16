<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UpsertPlatformMarketRequest extends FormRequest
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
            'buyname' => [$creating ? 'required' : 'sometimes', 'nullable', 'string', 'max:50'],
            'sellname' => [$creating ? 'required' : 'sometimes', 'nullable', 'string', 'max:50'],
            'jiaoyiqu' => [$creating ? 'required' : 'sometimes', 'nullable'],
            'round' => ['required', 'in:0,1,2,3,4,5,6'],
            'round_mum' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:6'],
            'fee_buy' => ['sometimes', 'nullable', 'string', 'max:20'],
            'fee_sell' => ['sometimes', 'nullable', 'string', 'max:20'],
            'buy_min' => ['sometimes', 'nullable', 'string', 'max:20'],
            'buy_max' => ['sometimes', 'nullable', 'string', 'max:20'],
            'sell_min' => ['sometimes', 'nullable', 'string', 'max:20'],
            'sell_max' => ['sometimes', 'nullable', 'string', 'max:20'],
            'trade_min' => ['sometimes', 'nullable', 'string', 'max:50'],
            'trade_max' => ['sometimes', 'nullable', 'string', 'max:50'],
            'trade_buy_num_min' => ['sometimes', 'nullable', 'string', 'max:200'],
            'trade_buy_num_max' => ['sometimes', 'nullable', 'string', 'max:200'],
            'trade_sell_num_min' => ['sometimes', 'nullable', 'string', 'max:200'],
            'trade_sell_num_max' => ['sometimes', 'nullable', 'string', 'max:200'],
            'invit_1' => ['sometimes', 'nullable', 'string', 'max:20'],
            'invit_2' => ['sometimes', 'nullable', 'string', 'max:20'],
            'invit_3' => ['sometimes', 'nullable', 'string', 'max:20'],
            'invit_buy' => ['sometimes', 'nullable', 'in:0,1'],
            'invit_sell' => ['sometimes', 'nullable', 'in:0,1'],
            'zhang' => ['sometimes', 'nullable', 'string', 'max:10'],
            'die' => ['sometimes', 'nullable', 'string', 'max:10'],
            'hou_price' => ['sometimes', 'nullable', 'string', 'max:50'],
            'trade' => ['sometimes', 'nullable', 'in:0,1'],
            'status' => ['sometimes', 'nullable', 'in:0,1'],
            'sort' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'start_time' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:23'],
            'start_minute' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:59'],
            'stop_time' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:23'],
            'stop_minute' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:59'],
            'agree6' => ['sometimes', 'nullable', 'in:0,1'],
            'agree7' => ['sometimes', 'nullable', 'in:0,1'],
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
