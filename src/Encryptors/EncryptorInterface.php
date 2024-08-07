<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Encryptors;

/**
 * Interface for encryptor classes.
 */
interface EncryptorInterface
{
    const OUTPUT_BASE64 = 'base64';
    const OUTPUT_HEX = 'hex';
    public function encrypt(string $plaintext): ?string;
    public function decrypt(string $cipherText): ?string;
}