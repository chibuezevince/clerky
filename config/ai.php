<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider Names
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the AI providers below should be the
    | default for AI operations when no explicit provider is provided
    | for the operation. This should be any provider defined below.
    |
    */

    'default' => 'qwen',
    'default_for_images' => 'qwen',
    'default_for_audio' => 'qwen',
    'default_for_transcription' => 'qwen',
    'default_for_embeddings' => 'qwen',
    'default_for_reranking' => 'qwen',

    'providers' => [
        'qwen' => [
            'driver' => 'openai',
            'key' => env('QWEN_API_KEY'),
            'url' => env('QWEN_URL', 'https://dashscope-intl.aliyuncs.com/compatible-mode/v1'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Below you may configure caching strategies for AI related operations
    | such as embedding generation. You are free to adjust these values
    | based on your application's available caching stores and needs.
    |
    */

    'caching' => [
        'embeddings' => [
            'cache' => false,
            'store' => env('CACHE_STORE', 'database'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Providers
    |--------------------------------------------------------------------------
    |
    | Below are each of your AI providers defined for this application. Each
    | represents an AI provider and API key combination which can be used
    | to perform tasks like text, image, and audio creation via agents.
    |
    */

];
