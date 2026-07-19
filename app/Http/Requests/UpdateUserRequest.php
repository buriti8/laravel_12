<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends CreateUserRequest
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
            'name' => 'sometimes|required',
            'username' => [
                'sometimes',
                'required',
                Rule::unique('users', 'username')->whereNull('deleted_at')->ignore($this->user),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('status', 1);
                    })->orWhereNull('status');
                })->whereNull('deleted_at')->ignore($this->user),
            ],
            'password' => 'sometimes|required|confirmed|min:6',
            'is_admin'=> 'sometimes|required',
            'role'=> 'required_if:is_admin,0|array',
            'status' => 'sometimes|bool',
            'type_transport' => 'sometimes|nullable',
        ];
    }

    /**
     * @return string
     */
    protected function getLangFile(): string
    {
        return 'users';
    }
}
