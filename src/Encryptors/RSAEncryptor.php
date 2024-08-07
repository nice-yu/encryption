<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Encryptors;

use NiceYu\Encryption\Exceptions\EncryptionException;

/**
 * RSAEncryptor class for handling RSA encryption and decryption.
 */
class RSAEncryptor extends AbstractEncryptor
{
    private string $publicKey;
    private string $privateKey;

    /**
     * Constructor for RSAEncryptor.
     *
     * @param string $publicKey The public key for encryption.
     * @param string $privateKey The private key for decryption.
     * @param string $output The output format for encryption (default: 'base64').
     */
    public function __construct(string $publicKey, string $privateKey, string $output = 'base64')
    {
        parent::__construct($output);
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
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
        $keyResource = openssl_pkey_get_public($this->publicKey);
        if ($keyResource === false) {
            throw new EncryptionException("Invalid public key provided for encryption.");
        }

        $cipherText = '';
        openssl_public_encrypt($plaintext, $cipherText, $keyResource);
        openssl_free_key($keyResource);

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
        $keyResource = openssl_pkey_get_private($this->privateKey);
        if ($keyResource === false) {
            throw new EncryptionException("Invalid private key provided for decryption.");
        }

        $cipherText = $this->decodeInput($cipherText);
        $plainText = '';
        openssl_private_decrypt($cipherText, $plainText, $keyResource);
        openssl_free_key($keyResource);

        return $plainText;
    }
}
