<?php

namespace App\Http\Requests;

use App\Http\Requests\Abstracts\BasicModelRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdatePasswordRequest extends BasicModelRequest
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
            'current_password' => !$this->user ? 'required' : '',
            'password' => 'required|confirmed',
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
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if (!$this->checkCurrentPassword()) {
                $validator->errors()->add('current_password', __('users.invalid_current_password'));
            } else if (!$this->checkNewPassword()) {
                $validator->errors()->add('password', __('users.invalid_new_password'));
            }
        });
    }

    /**
     * Valida si el campo contraseña actual es correcto
     *
     * @return bool
     */
    private function checkCurrentPassword(): bool
    {
        if ($this->has('current_password')) {
            return ($this->user ?? Auth::user())->checkPassword($this->get('current_password'));
        }

        return true;
    }

    /**
     * @return bool
     */
    private function checkNewPassword(): bool
    {
        return !($this->user ?? Auth::user())->checkPassword($this->get('password'));
    }
}
