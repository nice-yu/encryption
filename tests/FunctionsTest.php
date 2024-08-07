<?php

declare(strict_types=1);

namespace NiceYu\Encryption\Tests;

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/Resources/functions.php';

/**
 * @covers \is_encrypt
 * @covers \encrypt_flex_key
 * @covers \encrypt_flex_iv
 */
class FunctionsTest extends TestCase
{
    public function testIsEncryptWithValidCipher()
    {
        $this->assertTrue(\is_encrypt('AES-128-CBC'));
    }

    public function testIsEncryptWithInvalidCipher()
    {
        $this->assertFalse(\is_encrypt('INVALID-CIPHER'));
    }

    public function testEncryptFlexKeyWithValidCipher()
    {
        $cipher = 'AES-128-CBC';
        $key = 'mysecretkey12345'; // Must be 16 bytes for AES-128
        $expectedKey = substr($key, 0, 16);

        $result = \encrypt_flex_key($cipher, $key);

        $this->assertEquals($expectedKey, $result);
    }

    public function testEncryptFlexKeyWithInvalidCipher()
    {
        $cipher = 'INVALID-CIPHER';
        $key = 'mysecretkey12345';

        $result = \encrypt_flex_key($cipher, $key);

        $this->assertNull($result);
    }

    public function testEncryptFlexKeyWithShortKey()
    {
        $cipher = 'AES-128-CBC';
        $key = 'shortkey'; // Less than 16 bytes
        $expectedKey = substr($key, 0, 16);

        $result = \encrypt_flex_key($cipher, $key);

        $this->assertEquals($expectedKey, $result);
    }

    public function testEncryptFlexKeyWithLongKey()
    {
        $cipher = 'AES-128-CBC';
        $key = 'thisisaverylongkeythatexceedstheblocksize'; // More than 16 bytes
        $expectedKey = substr($key, 0, 16);

        $result = \encrypt_flex_key($cipher, $key);

        $this->assertEquals($expectedKey, $result);
    }

    public function testEncryptFlexIvWithValidCipher()
    {
        $cipher = 'AES-128-CBC';
        $iv = '1234567890123456'; // Must be 16 bytes for AES-128
        $expectedIv = substr($iv, 0, 16);

        $result = \encrypt_flex_iv($cipher, $iv);

        $this->assertEquals($expectedIv, $result);
    }

    public function testEncryptFlexIvWithInvalidCipher()
    {
        $cipher = 'INVALID-CIPHER';
        $iv = '1234567890123456';

        $result = \encrypt_flex_iv($cipher, $iv);

        $this->assertNull($result);
    }

    public function testEncryptFlexIvWithShortIv()
    {
        $cipher = 'AES-128-CBC';
        $iv = 'shortiv'; // Less than 16 bytes
        $expectedIv = substr($iv, 0, 16);

        $result = \encrypt_flex_iv($cipher, $iv);

        $this->assertEquals($expectedIv, $result);
    }

    public function testEncryptFlexIvWithLongIv()
    {
        $cipher = 'AES-128-CBC';
        $iv = 'thisisaverylongivthatexceedstheblocksize'; // More than 16 bytes
        $expectedIv = substr($iv, 0, 16);

        $result = \encrypt_flex_iv($cipher, $iv);

        $this->assertEquals($expectedIv, $result);
    }
}
