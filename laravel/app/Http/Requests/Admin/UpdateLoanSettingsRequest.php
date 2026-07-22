<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoanSettingsRequest extends FormRequest
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
            'enabled' => ['required', 'boolean'],
            'min_amount' => ['required', 'numeric', 'gt:0'],
            'max_amount' => ['required', 'numeric', 'gte:min_amount'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'daily_interest_rate' => ['required', 'numeric', 'gte:0', 'lt:1'],
            'lender_name' => ['required', 'string', 'max:120'],
        ];
    }
}
