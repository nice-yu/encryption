<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Encryptors;

use NiceYu\Encryption\Exceptions\EncryptionException;

/**
 * Encryptor class for handling encryption and decryption using OpenSSL.
 */
class Encryptor extends AbstractEncryptor
{
    private string $cipher;
    private string $key;
    private string $iv;

    /**
     * Constructor for Encryptor.
     *
     * @param string $cipher The encryption cipher method.
     * @param string $key The encryption key.
     * @param string $iv The encryption IV (optional).
     * @param string $output The output format for encryption (default: 'base64').
     */
    public function __construct(string $cipher, string $key, string $iv = '', string $output = 'base64')
    {
        parent::__construct($output);
        $this->cipher = $cipher;
        $this->key = $key;
        $this->iv = $iv;
    }

    /**
     * Encrypts the given plaintext.
     *
     * @param string $plaintext The plaintext to encrypt.
     * @return string|null The encrypted text, or null on failure.
     * @throws EncryptionException
     */
    public function encrypt(string $plaintext): ?string
    {
        if (!is_encrypt($this->cipher)) {
            throw new EncryptionException("Encryption algorithm is not supported: $this->cipher");
        }
        $cipherText = @openssl_encrypt($plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
        return $this->encodeOutput($cipherText);
    }

    /**
     * Decrypts the given ciphertext.
     *
     * @param string $cipherText The ciphertext to decrypt.
     * @return string|null The decrypted text, or null on failure.
     * @throws EncryptionException
     */
    public function decrypt(string $cipherText): ?string
    {
        if (!is_encrypt($this->cipher)) {
            throw new EncryptionException("Decryption algorithm is not supported: $this->cipher");
        }
        $cipherText = $this->decodeInput($cipherText);
        $plaintext = openssl_decrypt($cipherText, $this->cipher, $this->key, OPENSSL_RAW_DATA, $this->iv);
        if ($plaintext === false) {
            throw new EncryptionException("Decryption failed");
        }
        return $plaintext;
    }
}
