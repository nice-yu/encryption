<?php

declare(strict_types=1);

namespace NiceYu\Encryption\Tests;

use NiceYu\Encryption\Encryptors\Encryptor;
use NiceYu\Encryption\Exceptions\EncryptionException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \is_encrypt
 * @covers \encrypt_flex_key
 * @covers \NiceYu\Encryption\Encryptors\Encryptor
 * @covers \NiceYu\Encryption\Encryptors\AbstractEncryptor
 * @covers \NiceYu\Encryption\Exceptions\EncryptionException
 */
class EncryptorTest extends TestCase
{
    private string $cipher;
    private string $key;
    private string $iv;

    protected function setUp(): void
    {
        $this->cipher = 'AES-128-CBC';
        $this->key = 'thisisaverysecurekey1234';
        $this->iv = '1234567890123456';
    }

    public function testEncryptAndDecryptBase64()
    {
        $encryptor = new Encryptor($this->cipher, $this->key, $this->iv, Encryptor::OUTPUT_BASE64);

        $plaintext = 'Hello, OpenSSL!';
        $cipherText = $encryptor->encrypt($plaintext);

        $this->assertNotNull($cipherText);

        $decryptedText = $encryptor->decrypt($cipherText);

        $this->assertEquals($plaintext, $decryptedText);
    }

    public function testEncryptAndDecryptHex()
    {
        $encryptor = new Encryptor($this->cipher, $this->key, $this->iv, Encryptor::OUTPUT_HEX);

        $plaintext = 'Hello, OpenSSL!';
        $cipherText = $encryptor->encrypt($plaintext);

        $this->assertNotNull($cipherText);

        $decryptedText = $encryptor->decrypt($cipherText);

        $this->assertEquals($plaintext, $decryptedText);
    }

    public function testInvalidCipher()
    {
        $this->expectException(EncryptionException::class);

        $encryptor = new Encryptor('INVALID-CIPHER', $this->key, $this->iv);
        $encryptor->encrypt('test');
    }

    public function testEncryptNotBase64Hex()
    {
        $encryptor = new Encryptor($this->cipher, $this->key, $this->iv, 'ssl');

        $plaintext = 'Hello, OpenSSL!';
        $cipherText = $encryptor->encrypt($plaintext);

        $this->assertNotNull($cipherText);

        $decryptedText = $encryptor->decrypt($cipherText);

        $this->assertEquals($plaintext, $decryptedText);
    }

    public function testEncryptNotSupported()
    {
        $this->expectException(EncryptionException::class);
        $this->expectExceptionMessage('Encryption algorithm is not supported: aes-128-cbc-cts');
        $encryptor = new Encryptor('aes-128-cbc-cts', $this->key, 'invalid_iv');
        $encryptor->encrypt('test');
    }

    public function testDecryptNotSupported()
    {
        $this->expectException(EncryptionException::class);
        $this->expectExceptionMessage('Decryption algorithm is not supported: aes-128-cbc-cts');
        $encryptor = new Encryptor('aes-128-cbc-cts', $this->key, $this->iv);
        $encryptor->decrypt('test');
    }

    public function testDecryptFailed()
    {
        $this->expectException(EncryptionException::class);
        $this->expectExceptionMessage('Decryption failed');
        $encryptor = new Encryptor($this->cipher, $this->key, $this->iv);
        $encryptor->decrypt('test');
    }
}
