<?php

return [
    'lists' => [
        'document_types' => 'Tipos de documento',
    ],
    'default' => [
        'document_types' => [
            'CC' => 'CÉDULA DE CIUDADANÍA',
            'NIT' => 'NIT',
        ],
    ],
    'protected' => [
        'document_types' => [
            'CC' => true,
            'NIT' => true,
        ],
    ],
];
