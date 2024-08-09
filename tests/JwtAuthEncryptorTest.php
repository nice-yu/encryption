<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Tests;

use NiceYu\Encryption\Dto\PayloadDto;
use NiceYu\Encryption\Dto\JwtAuthDto;
use NiceYu\Encryption\Encryptors\Encryptor;
use NiceYu\Encryption\Encryptors\RSAEncryptor;
use NiceYu\Encryption\Encryptors\JwtAuthEncryptor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \is_encrypt
 * @covers \encrypt_flex_key
 * @covers \NiceYu\Encryption\Encryptors\RSAEncryptor
 * @covers \NiceYu\Encryption\Encryptors\AbstractEncryptor
 * @covers \NiceYu\Encryption\Exceptions\EncryptionException
 * @covers \NiceYu\Encryption\Encryptors\JwtAuthEncryptor
 * @covers \NiceYu\Encryption\Encryptors\Encryptor
 */
class JwtAuthEncryptorTest extends TestCase
{
    private JwtAuthEncryptor $jwtAuthEncryptor;
    private string $cipher = 'AES-128-CBC';
    private string $key = 'thisisaverysecurekey1234';
    private string $iv = '1234567890123456';
    private string $publicKey = '';
    private string $privateKey = '';

    protected function setUp(): void
    {
        // Setup payload
        $payload = new PayloadDto();
        $payload->iss = 'example.org';
        $payload->aud = 'example.com';
        $payload->iat = time();
        $payload->nbf = time();

        // Setup encryptor
        $encryptor = new Encryptor($this->cipher, $this->key, $this->iv);

        // Instantiate JwtAuthEncryptor
        $this->jwtAuthEncryptor = new JwtAuthEncryptor($encryptor, $payload);


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

    public function testAesEncryptAndDecrypt(): void
    {
        $id = "1";
        $token = $this->jwtAuthEncryptor->encrypt($id);
        $decryptedDto = $this->jwtAuthEncryptor->decrypt($token);

        $this->assertInstanceOf(JwtAuthDto::class, $decryptedDto);
        $this->assertEquals($id, $decryptedDto->id);
        $this->assertEquals(3600, $decryptedDto->expire);
    }

    public function testAesDecryptWithExpiredToken(): void
    {
        $id = "1";
        $payload = new PayloadDto();
        $payload->iss = 'example.org';
        $payload->aud = 'example.com';
        $payload->iat = time();
        $payload->nbf = time() - 3610; // 设置 nbf 为过去的时间

        $encryptor = new Encryptor($this->cipher, $this->key, $this->iv);
        $jwtAuthEncryptor = new JwtAuthEncryptor($encryptor, $payload);

        $token = $jwtAuthEncryptor->encrypt($id);
        $decryptedDto = $jwtAuthEncryptor->decrypt($token);
        $this->assertNull($decryptedDto);
    }

    public function testRsaEncryptAndDecrypt(): void
    {
        $id = "1";
        $payload = new PayloadDto();
        $payload->iss = 'example.org';
        $payload->aud = 'example.com';
        $payload->iat = time();
        $payload->nbf = time(); // 设置 nbf 为过去的时间

        $encryptor = new RSAEncryptor($this->publicKey, $this->privateKey);
        $jwtAuthEncryptor = new JwtAuthEncryptor($encryptor, $payload);

        $token = $jwtAuthEncryptor->encrypt($id);
        $decryptedDto = $jwtAuthEncryptor->decrypt($token);

        $this->assertInstanceOf(JwtAuthDto::class, $decryptedDto);
        $this->assertEquals($id, $decryptedDto->id);
        $this->assertEquals(3600, $decryptedDto->expire);
    }

    public function testRsaDecryptWithExpiredToken(): void
    {
        $id = "1";
        $payload = new PayloadDto();
        $payload->iss = 'example.org';
        $payload->aud = 'example.com';
        $payload->iat = time();
        $payload->nbf = time() - 3610; // 设置 nbf 为过去的时间

        $encryptor = new RSAEncryptor($this->publicKey, $this->privateKey);
        $jwtAuthEncryptor = new JwtAuthEncryptor($encryptor, $payload);

        $token = $jwtAuthEncryptor->encrypt($id);
        $decryptedDto = $jwtAuthEncryptor->decrypt($token);
        $this->assertNull($decryptedDto);
    }
}
