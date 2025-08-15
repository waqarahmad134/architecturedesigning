<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->input('id');
        return [
            'id' => 'required|exists:users,id',
            'first_name' => 'string',
            'last_name' => 'string',
            // 'company_name' => 'string',
            // 'position' => 'string',
            // 'website' => 'string',
            'profile_pic' => 'file|mimes:png,jpg',
            'cover_pic' => 'file|mimes:png,jpg',
            // 'mobile_number' => 'string',
            // 'telephone_number' => 'string',
            // 'twitter_url' => 'string',
            // 'insta_url' => 'string',
            // 'snapchat_url' => 'string',
            // 'fb_url' => 'string',
            // 'address' => 'string',
            // 'employee_level' => 'string',
            // 'password' => 'string',
            'role' => 'string|in:superadmin,subadmin,employee',
            'status' => 'string|in:active,block',
            'email' => [
                'email',
                Rule::unique('users')->ignore($userId, 'id')
            ]
        ];
    }
}
