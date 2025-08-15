<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            // 'company_name' => '',
            // 'position' => '',
            // 'website' => '',
            'profile_pic' => 'file|mimes:png,jpg',
            'cover_pic' => 'file|mimes:png,jpg',
            // 'mobile_number' => '',
            // 'telephone_number' => '',
            // 'twitter_url' => '',
            // 'insta_url' => '',
            // 'snapchat_url' => '',
            // 'fb_url' => '',
            // 'address' => '',
            // 'employee_level' => '',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|',
            'role' => 'required|in:superadmin,subadmin,employee',
        ];
    }
}
