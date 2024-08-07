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
