<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfilePicConfirm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "code" => "required|exists:secret_codes,code",
            "email" => [
                "required",
                "email",
                Rule::exists('secret_codes')->where(function ($query) {
                    $query->where('email', request()->input('email'))->where('code', request()->input('code'));
                })
            ],
        ];
    }
}
