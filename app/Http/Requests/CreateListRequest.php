<?php

namespace App\Http\Requests;

use App\Http\Requests\Abstracts\BasicModelRequest;
use Illuminate\Validation\Rule;

class CreateListRequest extends BasicModelRequest
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
            'list' => 'required',
            'option' => 'required',
            'option_key' => 'nullable',
        ];
    }

    /**
     * @return string
     */
    protected function getLangFile(): string
    {
        return 'lists';
    }
}
