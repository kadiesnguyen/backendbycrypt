<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator as ValidationValidator;
use Symfony\Component\HttpFoundation\Response;

class ReplyOnlineSupportRequest extends FormRequest
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
            'content' => ['required', 'string'],
        ];
    }

    public function withValidator(ValidationValidator $validator): void
    {
        $validator->after(function (ValidationValidator $validator): void {
            $content = (string) $this->input('content', '');

            if ($this->containsSuspiciousInput($content)) {
                $validator->errors()->add('content', 'Invalid input information.');
            }
        });
    }

    protected function containsSuspiciousInput(string $value): bool
    {
        $value = str_replace("'", '', trim($value));

        return (bool) preg_match(
            '/select|SELECT|or|OR|and|AND|char|CHAR|create|CREATR|drop|DROP|database|DATABASE|table|TABLE|insert|INSERT|script|SCRIPT|function|FUNCTION|update|UPDATE|delete|DELETE|exec|EXEC|system|SYSTEM|passthru|PASSTHRU|shell_exec|SHELL_EXEC|<|\`|\%|\"|\'|\/\*|\*|\.\.\/|\.\/|union|UNION|into|INTO|load_file|LOAD_FILE|outfile|OUTFILE/i',
            $value
        );
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
