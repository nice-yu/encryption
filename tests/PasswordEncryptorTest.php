<?php

declare(strict_types=1);

namespace NiceYu\Encryption\Tests;

use NiceYu\Encryption\Encryptors\Encryptor;
use NiceYu\Encryption\Encryptors\RSAEncryptor;
use PHPUnit\Framework\TestCase;
use NiceYu\Encryption\Encryptors\PasswordEncryptor;
use NiceYu\Encryption\Exceptions\EncryptionException;

/**
 * @covers \is_encrypt
 * @covers \encrypt_flex_key
 * @covers \NiceYu\Encryption\Encryptors\RSAEncryptor
 * @covers \NiceYu\Encryption\Encryptors\AbstractEncryptor
 * @covers \NiceYu\Encryption\Exceptions\EncryptionException
 * @covers \NiceYu\Encryption\Encryptors\PasswordEncryptor
 * @covers \NiceYu\Encryption\Encryptors\Encryptor
 */
class PasswordEncryptorTest extends TestCase
{
    private $publicKey;
    private $privateKey;
    private $rsaEncryptor;
    private $passwordEncryptor;

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

        $this->rsaEncryptor = new RSAEncryptor($this->publicKey, $this->privateKey);
        $this->passwordEncryptor = new PasswordEncryptor($this->rsaEncryptor);
    }

    public function testConstructorWithValidEncryptor()
    {
        $this->assertInstanceOf(PasswordEncryptor::class, $this->passwordEncryptor);
    }

    public function testRsaEncryptPassword()
    {
        $password = "my_secure_password";
        $encryptedPassword = $this->passwordEncryptor->encrypt($password);
        $this->assertNotNull($encryptedPassword);
        $this->assertNotEquals($password, $encryptedPassword);
    }

    public function testRsaDecryptPassword()
    {
        $password = "my_secure_password";
        $encryptedPassword = $this->passwordEncryptor->encrypt($password);
        $decryptedPassword = $this->passwordEncryptor->decrypt($encryptedPassword);
        $this->assertEquals($password, $decryptedPassword);
    }

    public function testAesEncryptPassword()
    {
        $cipher = 'AES-128-CBC';
        $key = 'thisisaverysecurekey1234';
        $iv = '1234567890123456';
        $aesEncryptor = new Encryptor($cipher, $key, $iv);
        $passwordEncryptor = new PasswordEncryptor($aesEncryptor);

        $password = "my_secure_password";
        $encryptedPassword = $passwordEncryptor->encrypt($password);
        $this->assertNotNull($encryptedPassword);
        $this->assertNotEquals($password, $encryptedPassword);
    }

    public function testAesDecryptPassword()
    {
        $cipher = 'AES-128-CBC';
        $key = 'thisisaverysecurekey1234';
        $iv = '1234567890123456';
        $aesEncryptor = new Encryptor($cipher, $key, $iv);
        $passwordEncryptor = new PasswordEncryptor($aesEncryptor);

        $password = "my_secure_password";
        $encryptedPassword = $passwordEncryptor->encrypt($password);
        $decryptedPassword = $passwordEncryptor->decrypt($encryptedPassword);
        $this->assertEquals($password, $decryptedPassword);
    }

    public function testEncryptPasswordFailure()
    {
        $this->expectException(EncryptionException::class);

        // 用一个非法的公钥来强制触发加密失败
        $invalidPublicKey = "invalid_public_key";
        $rsaEncryptor = new RSAEncryptor($invalidPublicKey, $this->privateKey);
        $passwordEncryptor = new PasswordEncryptor($rsaEncryptor);
        $passwordEncryptor->encrypt("test");
    }

    public function testDecryptPasswordFailure()
    {
        $this->expectException(EncryptionException::class);

        // 用一个非法的私钥来强制触发解密失败
        $invalidPrivateKey = "invalid_private_key";
        $rsaEncryptor = new RSAEncryptor($this->publicKey, $invalidPrivateKey);
        $passwordEncryptor = new PasswordEncryptor($rsaEncryptor);
        $passwordEncryptor->decrypt("test");
    }
}
