<?php

return [
    'api_url' => env('TABADUL_API_URL', 'https://epg.tabadul.iq/epg/rest/register.do'),


    'user_name' => env('TABADUL_USER_NAME', 'your_api_username'),


    'password' => env('TABADUL_PASSWORD', 'your_api_password'),


    'order_number' => env('TABADUL_ORDER_NUMBER', 'your_order_number'),


    'amount' => env('TABADUL_AMOUNT', 'your_order_amount'),


    'currency' => env('TABADUL_CURRENCY', 'your_currency_code'),


    'return_url' => env('TABADUL_RETURN_URL', 'your_return_url'),


    'client_id' => env('TABADUL_CLIENT_ID', 'your_client_id'),


    'description' => env('TABADUL_DESCRIPTION', 'your_order_description'),


    'language' => env('TABADUL_LANGUAGE', 'your_language_code'),
];