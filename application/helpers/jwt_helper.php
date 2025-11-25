<?php defined('BASEPATH') or exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Simpan SECRET di ENV/Config aman. Untuk demo, hardcode.
 * @return string
 */
if (!function_exists('jwt_secret')) {
    function jwt_secret()
    {
        return 'super-secret-CHANGE-ME';
    }
}

if (!function_exists('jwt_encode')) {
    function jwt_encode(array $claims, $ttlSeconds = 3600)
    {
        $now = time();
        $payload = array_merge([
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $ttlSeconds,
            'iss' => 'ci3-api',
            'aud' => 'ci3-client'
        ], $claims);

        return JWT::encode($payload, jwt_secret(), 'HS256');
    }
}

if (!function_exists('jwt_decode')) {
    function jwt_decode($token)
    {
        return JWT::decode($token, new Key(jwt_secret(), 'HS256'));
    }
}
