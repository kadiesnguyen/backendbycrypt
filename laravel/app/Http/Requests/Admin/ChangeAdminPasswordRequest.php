<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAdminPasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string', 'min:6', 'max:32'],
            'new_password' => ['required', 'string', 'min:6', 'max:32'],
            'confirm_password' => ['required', 'same:new_password'],
        ];
    }
}
