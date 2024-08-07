<?php
declare(strict_types=1);

if (!function_exists('is_encrypt')) {
    /**
     * Checks if the given encryption method is valid.
     *
     * @param string $encryptMethod
     * @return bool
     */
    function is_encrypt(string $encryptMethod): bool
    {
        if (in_array(strtolower($encryptMethod), openssl_get_cipher_methods())) {
            return !is_null(encrypt_flex_key($encryptMethod, '123'));
        }
        return false;
    }
}

if (!function_exists('encrypt_flex_key')) {
    /**
     * Generates a flexible encryption key based on the given method and key.
     *
     * @param string $encryptMethod Encryption method
     * @param string $key Encryption key information
     * @return string|null
     */
    function encrypt_flex_key(string $encryptMethod, string $key): ?string
    {
        $keyLength = @openssl_cipher_key_length($encryptMethod);
        if ($keyLength === false) {
            return null;
        }
        return substr($key, 0, $keyLength);
    }
}

if (!function_exists('encrypt_flex_iv')) {
    /**
     * Generates a flexible encryption IV based on the given method and IV.
     *
     * @param string $encryptMethod Encryption method
     * @param string $iv Encryption IV information
     * @return string|null
     */
    function encrypt_flex_iv(string $encryptMethod, string $iv): ?string
    {
        $ivLength = @openssl_cipher_iv_length($encryptMethod);
        if ($ivLength === false) {
            return null;
        }
        return substr($iv, 0, $ivLength);
    }
}
