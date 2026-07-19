<?php

namespace App\Http\Requests\Abstracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * Class BasicModelRequest
 * @package App\Http\Requests
 */
abstract class BasicModelRequest extends FormRequest
{
    /**
     * @return string
     */
    abstract protected function getLangFile() : string;

    /**
     * @return array
     */
    public function attributes()
    {
        $lang_prefix = $this->getLangFile();
        return collect($this->rules())->keys()->mapWithKeys(function($field) use($lang_prefix) {
            return [$field => __("{$lang_prefix}.{$field}")];
        })->toArray();
    }
}