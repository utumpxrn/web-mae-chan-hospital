<?php

/**
 * Generate a JSON Web Token (JWT).
 *
 * @param array $payload The data to be encoded in the token.
 * @param string $secret The secret key used to sign the token.
 * @return string The generated JWT.
 */
function generate_jwt(array $payload, string $secret): string
{
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $base64UrlHeader = base64UrlEncode($header);
    $base64UrlPayload = base64UrlEncode(json_encode($payload));

    // Create signature
    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $secret, true);
    $base64UrlSignature = base64UrlEncode($signature);

    // Concatenate header, payload, and signature to form the JWT
    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}

/**
 * Verify and decode a JSON Web Token (JWT).
 *
 * @param string $jwt The JWT to verify.
 * @param string $secret The secret key used to verify the token.
 * @return array|null The decoded payload if valid, or null if invalid.
 */
function verify_jwt(string $jwt, string $secret): ?array
{
    // Split the JWT into header, payload, and signature
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return null;
    }
    [$header, $payload, $signature] = $parts;

    // Verify the signature
    $validSignature = hash_hmac('sha256', "$header.$payload", $secret, true);
    $base64UrlValidSignature = base64UrlEncode($validSignature);

    if ($base64UrlValidSignature !== $signature) {
        return null;
    }

    // Decode and return the payload
    $decodedPayload = json_decode(base64UrlDecode($payload), true);
    
    // Check expiration if present
    if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) {
        return null; // Token is expired
    }

    return $decodedPayload;
}

/**
 * Encode data to Base64 URL format.
 *
 * @param string $data The data to encode.
 * @return string The Base64 URL encoded data.
 */
function base64UrlEncode(string $data): string
{
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

/**
 * Decode Base64 URL format to original data.
 *
 * @param string $data The Base64 URL encoded data.
 * @return string The decoded data.
 */
function base64UrlDecode(string $data): string
{
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
}