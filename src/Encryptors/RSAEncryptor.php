<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Encryptors;

use NiceYu\Encryption\Exceptions\EncryptionException;

/**
 * RSAEncryptor class for handling RSA encryption and decryption.
 */
class RSAEncryptor extends AbstractEncryptor
{
    private $publicKeyResource;
    private $privateKeyResource;

    /**
     * Constructor for RSAEncryptor.
     *
     * @param string $publicKey The public key for encryption.
     * @param string $privateKey The private key for decryption.
     * @param string $output The output format for encryption (default: 'base64').
     * @throws EncryptionException
     */
    public function __construct(string $publicKey, string $privateKey, string $output = 'base64')
    {
        parent::__construct($output);

        // Initialize key resources and validate them
        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            throw new EncryptionException("Invalid public key provided for encryption.");
        }

        $privateKeyResource = openssl_pkey_get_private($privateKey);
        if ($privateKeyResource === false) {
            throw new EncryptionException("Invalid private key provided for decryption.");
        }

        $this->publicKeyResource = $publicKeyResource;
        $this->privateKeyResource = $privateKeyResource;
    }

    /**
     * Encrypts the given plaintext using the public key.
     *
     * @param string $plaintext The plaintext to encrypt.
     * @return string|null The encrypted text, or null on failure.
     * @throws EncryptionException
     */
    public function encrypt(string $plaintext): ?string
    {
        $cipherText = '';
        if (!openssl_public_encrypt($plaintext, $cipherText, $this->publicKeyResource)) {
            throw new EncryptionException("Encryption failed.");
        }

        return $this->encodeOutput($cipherText);
    }

    /**
     * Decrypts the given ciphertext using the private key.
     *
     * @param string $cipherText The ciphertext to decrypt.
     * @return string|null The decrypted text, or null on failure.
     * @throws EncryptionException
     */
    public function decrypt(string $cipherText): ?string
    {
        $cipherText = $this->decodeInput($cipherText);
        $plainText = '';
        if (!openssl_private_decrypt($cipherText, $plainText, $this->privateKeyResource)) {
            throw new EncryptionException("Decryption failed.");
        }

        return $plainText;
    }

    /**
     * Destructor for RSAEncryptor.
     * Ensures key resources are properly released.
     */
    public function __destruct()
    {
        // No need to explicitly free the keys as PHP will handle it
    }
}
