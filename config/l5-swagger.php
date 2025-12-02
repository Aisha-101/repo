<?php

return [

    'default' => 'default',

    'documentations' => [

        'default' => [

            'api' => [
                'title' => 'Knygų rekomendacijos API',
                'url' => env('APP_URL') . '/api',
            ],

            'routes' => [
                'api' => 'api/documentation', // Route for Swagger UI
            ],

            'paths' => [
                'docs' => storage_path('api-docs'),
                'docs_json' => 'openapi.json',
                'docs_yaml' => null,
                'annotations' => [base_path('app/Http/Controllers')],
                'excludes' => [],
            ],
        ],
    ],

    'defaults' => [

        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],

        'paths' => [
            'docs' => storage_path('api-docs'),
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'excludes' => [],
        ],

        'scanOptions' => [
            'default_processors_configuration' => [],
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            'exclude' => [],
            'open_api_spec_version' => '3.1.0', // Explicitly set OpenAPI version
        ],

        'securityDefinitions' => [
             'bearerAuth' => [
                'type' => 'apiKey',
                'description' => 'Enter token in format: Bearer {your_token}',
                'name' => 'Authorization',
                'in' => 'header',
            ],
            'securitySchemes' => [],
            'security' => [
                'bearerAuth' => []],
        ],

        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url' => null,

        'ui' => [
            'display' => [
                'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],

        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
        ],
    ],
];
