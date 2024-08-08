<?php
declare(strict_types=1);
namespace NiceYu\Encryption\Product;

use InvalidArgumentException;
use NiceYu\Encryption\Encryptors\EncryptorInterface;

/**
 * Class PasswordEncryptor
 *
 * This class handles password encryption and decryption using the provided encryptor.
 */
class PasswordEncryptor
{
    private EncryptorInterface $encryptor;

    /**
     * PasswordEncryptor constructor.
     *
     * @param EncryptorInterface $encryptor An instance of a class that implements EncryptorInterface.
     * @throws InvalidArgumentException if the provided encryptor does not implement EncryptorInterface.
     */
    public function __construct(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * Encrypt a password.
     *
     * @param string $password The password to encrypt.
     * @return string The encrypted password.
     */
    public function encryptPassword(string $password): string
    {
        return $this->encryptor->encrypt($password);
    }

    /**
     * Decrypt a password.
     *
     * @param string $encryptedPassword The encrypted password to decrypt.
     * @return string The decrypted password.
     */
    public function decryptPassword(string $encryptedPassword): string
    {
        return $this->encryptor->decrypt($encryptedPassword);
    }
}
