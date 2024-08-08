<?php

declare(strict_types=1);

namespace NiceYu\Encryption\Tests;

use NiceYu\Encryption\Encryptors\RSAEncryptor;
use NiceYu\Encryption\Exceptions\EncryptionException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \NiceYu\Encryption\Encryptors\RSAEncryptor
 * @covers \NiceYu\Encryption\Encryptors\AbstractEncryptor
 * @covers \NiceYu\Encryption\Exceptions\EncryptionException
 */
class RSAEncryptorTest extends TestCase
{
    private string $publicKey;
    private string $privateKey;

    protected function setUp(): void
    {
        parent::setUp();

        // Generate temporary RSA keys for testing
        $res = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)["key"];

        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function testConstructorWithValidKeys()
    {
        $rsaEncryptor = new RSAEncryptor($this->publicKey, $this->privateKey);
        $this->assertInstanceOf(RSAEncryptor::class, $rsaEncryptor);
    }

    public function testConstructorWithInvalidPublicKey()
    {
        $this->expectException(EncryptionException::class);
        new RSAEncryptor('invalid_public_key', $this->privateKey);
    }

    public function testConstructorWithInvalidPrivateKey()
    {
        $this->expectException(EncryptionException::class);
        new RSAEncryptor($this->publicKey, 'invalid_private_key');
    }

    public function testEncryptWithInvalidPublicKey()
    {
        $invalidPublicKey = "-----BEGIN PUBLIC KEY-----
invalid_key
-----END PUBLIC KEY-----";

        $this->expectException(EncryptionException::class);
        $rsaEncryptor = new RSAEncryptor($invalidPublicKey, $this->privateKey);
        $rsaEncryptor->encrypt("test message");
    }

    public function testDecryptWithInvalidPrivateKey()
    {
        $rsaEncryptor = new RSAEncryptor($this->publicKey, $this->privateKey);
        $plaintext = "This is a test message.";
        $encrypted = $rsaEncryptor->encrypt($plaintext);
        $this->assertNotNull($encrypted);

        $invalidPrivateKey = "-----BEGIN PRIVATE KEY-----
invalid_key
-----END PRIVATE KEY-----";

        $this->expectException(EncryptionException::class);
        $invalidEncryptor = new RSAEncryptor($this->publicKey, $invalidPrivateKey);
        $invalidEncryptor->decrypt($encrypted);
    }

    public function testEncryptAndDecrypt()
    {
        $rsaEncryptor = new RSAEncryptor($this->publicKey, $this->privateKey);
        $plaintext = "This is a test message.";
        $encrypted = $rsaEncryptor->encrypt($plaintext);
        $this->assertNotNull($encrypted);

        $decrypted = $rsaEncryptor->decrypt($encrypted);
        $this->assertEquals($plaintext, $decrypted);
    }

    public function testEncryptAndDecryptBase64()
    {
        $encryptor = new RSAEncryptor($this->publicKey, $this->privateKey, RSAEncryptor::OUTPUT_BASE64);

        $plaintext = 'Hello, RSA!';
        $cipherText = $encryptor->encrypt($plaintext);

        $this->assertNotNull($cipherText);

        $decryptedText = $encryptor->decrypt($cipherText);

        $this->assertEquals($plaintext, $decryptedText);
    }

    public function testEncryptAndDecryptHex()
    {
        $encryptor = new RSAEncryptor($this->publicKey, $this->privateKey, RSAEncryptor::OUTPUT_HEX);

        $plaintext = 'Hello, RSA!';
        $cipherText = $encryptor->encrypt($plaintext);

        $this->assertNotNull($cipherText);

        $decryptedText = $encryptor->decrypt($cipherText);

        $this->assertEquals($plaintext, $decryptedText);
    }

    public function testInvalidPublicKey()
    {
        $this->expectException(EncryptionException::class);

        $encryptor = new RSAEncryptor('invalid_public_key', $this->privateKey);
        $encryptor->encrypt('test');
    }

    public function testInvalidPrivateKey()
    {
        $this->expectException(EncryptionException::class);

        $encryptor = new RSAEncryptor($this->publicKey, 'invalid_private_key');
        $encryptor->decrypt('test');
    }
}
