<?php

namespace App\Http\Requests;

use App\Http\Requests\Abstracts\BasicModelRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends BasicModelRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('status', 1);
                    })->orWhereNull('status');
                })->whereNull('deleted_at'),
            ],
            'password' => 'required|confirmed|min:6',
            'is_admin' => 'required',
            'role' => 'required_if:is_admin,0|array',
            'type_transport' => 'nullable'
        ];
    }

    /**
     * @return string
     */
    protected function getLangFile(): string
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'role.required_if' => __('validation.required', ['attribute', $this->attributes()['role'] ?? 'role']),
        ];
    }
}
