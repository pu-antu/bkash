<?php
/**
 * Created by PhpStorm.
 * User: sagar
 * Date: 2/25/2019
 * Time: 11:24 AM
 */


return [
    'mode'  => env('BKASH_API_MODE',''),
    'sandbox' => [
        'username'    => env( 'BKASH_API_USERNAME', ''),
        'password'    => env( 'BKASH_API_PASSWORD', ''),
        'app_key'      => env('BKASH_API_KEY', ''),
        'app_secret' => env(  'BKASH_API_SECRET', ''),
    ],
    'live' => [
        'username'    => env( 'BKASH_API_USERNAME', ''),
        'password'    => env( 'BKASH_API_PASSWORD', ''),
        'app_key'      => env('BKASH_API_KEY', ''),
        'app_secret' => env(  'BKASH_API_SECRET', ''),
    ],
];
