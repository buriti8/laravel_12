<?php

use Illuminate\Support\Facades\Lang;

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'driver_programming' =>'El conductor tiene una programación para las horas establecidas.',
    'accepted'             => ':attribute debe ser aceptado.',
    'active_url'           => ':attribute no es una URL válida.',
    'after'                => ':attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => ':attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => ':attribute solo puede contener letras.',
    'alpha_dash'           => ':attribute solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => ':attribute solo puede contener letras y números.',
    'array'                => ':attribute debe ser un array.',
    'before'               => ':attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => ':attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => ' :attribute debe ser un valor entre :min y :max.',
        'file'    => 'El archivo :attribute debe pesar entre :min y :max kilobytes.',
        'string'  => ':attribute debe contener entre :min y :max caracteres.',
        'array'   => ':attribute debe contener entre :min y :max elementos.',
    ],
    'boolean'              => ':attribute debe ser verdadero o falso.',
    'confirmed'            => 'Confirmación de :attribute no coincide.',
    'date'                 => ':attribute no corresponde con una fecha válida.',
    'date_format'          => ':attribute no corresponde con el formato de fecha :format.',
    'different'            => ':attribute y :other deben ser diferentes.',
    'digits'               => ':attribute debe ser un número de :digits dígitos.',
    'digits_between'       => ':attribute debe contener entre :min y :max dígitos.',
    'dimensions'           => ':attribute tiene dimensiones de imagen inválidas.',
    'distinct'             => ':attribute tiene un valor duplicado.',
    'email'                => ':attribute debe ser una dirección de correo válida.',
    'exists'               => ':attribute seleccionado no existe.',
    'file'                 => ':attribute debe ser un archivo.',
    'filled'               => ':attribute debe tener un valor.',
    'gt'                   => [
        'numeric' => ':attribute debe ser mayor a :value.',
        'file'    => 'El archivo :attribute debe pesar más de :value kilobytes.',
        'string'  => ':attribute debe contener más de :value caracteres.',
        'array'   => ':attribute debe contener más de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => ':attribute debe ser mayor o igual a :value.',
        'file'    => 'El archivo :attribute debe pesar :value o más kilobytes.',
        'string'  => ':attribute debe contener :value o más caracteres.',
        'array'   => ':attribute debe contener :value o más elementos.',
    ],
    'image'                => ':attribute debe ser una imagen.',
    'in'                   => ':attribute es inválido.',
    'in_array'             => ':attribute no existe en :other.',
    'integer'              => ':attribute debe ser un número entero.',
    'ip'                   => ':attribute debe ser una dirección IP válida.',
    'ipv4'                 => ':attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => ':attribute debe ser una dirección IPv6 válida.',
    'json'                 => ':attribute debe ser una cadena de texto JSON válida.',
    'lt'                   => [
        'numeric' => ':attribute debe ser menor a :value.',
        'file'    => 'El archivo :attribute debe pesar menos de :value kilobytes.',
        'string'  => ':attribute debe contener menos de :value caracteres.',
        'array'   => ':attribute debe contener menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => ':attribute debe ser menor o igual a :value.',
        'file'    => 'El archivo :attribute debe pesar :value o menos kilobytes.',
        'string'  => ':attribute debe contener :value o menos caracteres.',
        'array'   => ':attribute debe contener :value o menos elementos.',
    ],
    'max'                  => [
        'numeric' => ':attribute debe ser un número de :max dígitos.',
        'file'    => 'El archivo :attribute no debe pesar más de :max kilobytes.',
        'string'  => ':attribute no debe contener más de :max caracteres.',
        'array'   => ':attribute no debe contener más de :max elementos.',
    ],
    'mimes'                => ':attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => ':attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'numeric' => ':attribute debe ser al menos :min.',
        'file'    => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'string'  => ':attribute debe contener al menos :min caracteres.',
        'array'   => ':attribute debe contener al menos :min elementos.',
    ],
    'not_in'               => ':attribute seleccionado es inválido.',
    'not_regex'            => ':attribute es inválido.',
    'numeric'              => ':attribute debe ser numérico.',
    'present'              => ':attribute debe estar presente.',
    'regex'                => ':attribute es inválido.',
    'required'             => ':attribute es requerido.',
    'required_if'          => ':attribute es obligatorio cuando  :other es :value.',
    'required_unless'      => ':attribute es requerido a menos que :other se encuentre en :values.',
    'required_with'        => ':attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => ':attribute es obligatorio cuando :values están presentes.',
    'required_without'     => ':attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => ':attribute es obligatorio cuando ninguno de los campos :values están presentes.',
    'same'                 => ':attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => ':attribute debe ser :size.',
        'file'    => 'El archivo :attribute debe pesar :size kilobytes.',
        'string'  => ':attribute debe contener :size caracteres.',
        'array'   => ':attribute debe contener :size elementos.',
    ],
    'string'               => ':attribute debe ser una cadena de caracteres.',
    'timezone'             => ':attribute debe ser una zona horaria válida.',
    'unique'               => ':attribute ya está en uso.',
    'uploaded'             => ':attribute no se pudo subir.',
    'url'                  => ':attribute es inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'username.required' => Lang::get('users.username') . " es requerido.",
        'password.required' => Lang::get('users.password') . " es requerido.",
        'password.confirmed' => "Confirmación de contraseña no coincide.",
        'password.min' => "Contraseña debe contener al menos 6 caracteres.",
        'email.required' => Lang::get('users.email') . " es requerido.",
        'email.email' => Lang::get('users.email') . " debe ser una dirección de correo válida.",
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],
];
