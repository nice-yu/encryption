# Overview

`niceyu/encryption` is a simple yet powerful PHP encryption library that supports multiple encryption algorithms, including AES and RSA. It can handle different output formats such as base64 and hex. This library aims to provide developers with an easy-to-use interface for data encryption and decryption, ensuring secure data transmission.

## Installation

Install this library in your project using Composer:
```bash
composer require niceyu/encryption
```

## Usage

### AES Encryption
- `AES` is a symmetric encryption algorithm that supports multiple modes. For other modes, please see the [Encryption Algorithm Comparison Table](#encryption-algorithm-comparison-table).
- Below is an example of how to use the `AES-128-CBC` mode for encryption and decryption.

```php
use NiceYu\Encryption\Encryptors\Encryptor;

$cipher = 'AES-128-CBC';
$key = 'thisisaverysecurekey1234';
$iv = '1234567890123456';

// Create Encryptor instance
$encryptor = new Encryptor($cipher, $key, $iv);

// Encrypt data
$plaintext = 'Hello, OpenSSL!';
$encrypted = $encryptor->encrypt($plaintext);

// Decrypt data
$decrypted = $encryptor->decrypt($encrypted);

echo "Encrypted data: $encrypted\n";
echo "Decrypted data: $decrypted\n";
```

### RSA Encryption

`RSA` is an asymmetric encryption algorithm commonly used to encrypt small chunks of data or to encrypt symmetric keys.

```php
use NiceYu\Encryption\Encryptors\RSAEncryptor;

$publicKey = file_get_contents('path/to/public_key.pem');
$privateKey = file_get_contents('path/to/private_key.pem');

// Create RSAEncryptor instance
$encryptor = new RSAEncryptor($publicKey, $privateKey);

// Encrypt data
$plaintext = 'Hello, RSA!';
$encrypted = $encryptor->encrypt($plaintext);

// Decrypt data
$decrypted = $encryptor->decrypt($encrypted);

echo "Encrypted data: $encrypted\n";
echo "Decrypted data: $decrypted\n";
```

## Function Descriptions

### is_encrypt

Checks if the given encryption algorithm is valid.

```php
function is_encrypt(string $cipher): bool
```

**Parameters**
- `cipher` (string): Name of the encryption algorithm. See [Encryption Algorithm Comparison Table](#encryption-algorithm-comparison-table) for more details.

**Returns**
- `bool`: Returns `true` if the encryption algorithm is valid, otherwise `false`.

### encrypt_flex_key

Automatically truncates the encryption key based on the input encryption algorithm.

```php
function encrypt_flex_key(string $cipher, string $data): string
```

**Parameters**
- `cipher` (string): Name of the encryption algorithm.
- `data` (string): Key data. If the exact length is unknown, provide a longer string; it will be automatically truncated.

**Returns**
- `string`: The generated encryption key.

### encrypt_flex_iv

Automatically truncates the initialization vector (IV) based on the input encryption algorithm.

```php
function encrypt_flex_iv(string $cipher, string $data): string
```

**Parameters**
- `cipher` (string): Name of the encryption algorithm.
- `data` (string): Initialization vector data. If the exact length is unknown, provide a longer string; it will be automatically truncated.

**Returns**
- `string`: The generated initialization vector.

## Example Code

Below is a complete example demonstrating how to use the `niceyu/encryption` library for AES and RSA encryption and decryption operations.

```php
require 'vendor/autoload.php';

use NiceYu\Encryption\Encryptors\Encryptor;
use NiceYu\Encryption\Encryptors\RSAEncryptor;

// AES encryption and decryption example
$cipher = 'AES-128-CBC';
$key = 'thisisaverysecurekey1234';
$iv = '1234567890123456';
$encryptor = new Encryptor($cipher, $key, $iv);

$plaintext = 'Hello, AES!';
$encrypted = $encryptor->encrypt($plaintext);
$decrypted = $encryptor->decrypt($encrypted);

echo "Encrypted data: $encrypted\n";
echo "Decrypted data: $decrypted\n";

// RSA encryption and decryption example
$publicKey = file_get_contents('path/to/public_key.pem');
$privateKey = file_get_contents('path/to/private_key.pem');
$rsaEncryptor = new RSAEncryptor($publicKey, $privateKey);

$plaintextRSA = 'Hello, RSA!';
$encryptedRSA = $rsaEncryptor->encrypt($plaintextRSA);
$decryptedRSA = $rsaEncryptor->decrypt($encryptedRSA);

echo "Encrypted data: $encryptedRSA\n";
echo "Decrypted data: $decryptedRSA\n";
```

Please note that ECB mode is a basic encryption mode that encrypts each data block independently without considering the relationship between blocks. This can lead to security issues, so ECB mode is not recommended for practical applications. In actual applications, it is recommended to use more secure encryption modes such as CBC, CTR, or GCM.

### Supported Encryption Methods Table
| Encryption Method | Key Length | IV Length |
|-------------------|------------|-----------|
| rsa               | pem        | pem       |
| aes-128-cbc       | 16         | 16        |
| aes-128-cfb       | 16         | 16        |
| aes-128-cfb1      | 16         | 16        |
| aes-128-cfb8      | 16         | 16        |
| aes-128-ctr       | 16         | 16        |
| aes-128-ofb       | 16         | 16        |
| aes-128-wrap-pad  | 16         | 4         |
| aes-128-xts       | 32         | 16        |
| aes-192-cbc       | 24         | 16        |
| aes-192-cfb       | 24         | 16        |
| aes-192-cfb1      | 24         | 16        |
| aes-192-cfb8      | 24         | 16        |
| aes-192-ctr       | 24         | 16        |
| aes-192-ofb       | 24         | 16        |
| aes-192-wrap-pad  | 24         | 4         |
| aes-256-cbc       | 32         | 16        |
| aes-256-cfb       | 32         | 16        |
| aes-256-cfb1      | 32         | 16        |
| aes-256-cfb8      | 32         | 16        |
| aes-256-ctr       | 32         | 16        |
| aes-256-ofb       | 32         | 16        |
| aes-256-wrap-pad  | 32         | 4         |
| aes-256-xts       | 32         | 16        |
| aria-128-cbc      | 16         | 16        |
| aria-128-cfb      | 16         | 16        |
| aria-128-cfb1     | 16         | 16        |
| aria-128-cfb8     | 16         | 16        |
| aria-128-ctr      | 16         | 16        |
| aria-128-ofb      | 16         | 16        |
| aria-192-cbc      | 24         | 16        |
| aria-192-cfb      | 24         | 16        |
| aria-192-cfb1     | 24         | 16        |
| aria-192-cfb8     | 24         | 16        |
| aria-192-ctr      | 24         | 16        |
| aria-192-ofb      | 24         | 16        |
| aria-256-cbc      | 32         | 16        |
| aria-256-cfb      | 32         | 16        |
| aria-256-cfb1     | 32         | 16        |
| aria-256-cfb8     | 32         | 16        |
| aria-256-ctr      | 32         | 16        |
| aria-256-ofb      | 32         | 16        |
| camellia-128-cbc  | 16         | 16        |
| camellia-128-cfb  | 16         | 16        |
| camellia-128-cfb1 | 16         | 16        |
| camellia-128-cfb8 | 16         | 16        |
| camellia-128-ctr  | 16         | 16        |
| camellia-128-ofb  | 16         | 16        |
| camellia-192-cbc  | 24         | 16        |
| camellia-192-cfb  | 24         | 16        |
| camellia-192-cfb1 | 24         | 16        |
| camellia-192-cfb8 | 24         | 16        |
| camellia-192-ctr  | 24         | 16        |
| camellia-192-ofb  | 24         | 16        |
| camellia-256-cbc  | 32         | 16        |
| camellia-256-cfb  | 32         | 16        |
| camellia-256-cfb1 | 32         | 16        |
| camellia-256-cfb8 | 32         | 16        |
| camellia-256-ctr  | 32         | 16        |
| camellia-256-ofb  | 32         | 16        |
| chacha20          | 32         | 16        |
| des-ede-cbc       | 16         | 8         |
| des-ede-cfb       | 16         | 8         |
| des-ede-ofb       | 16         | 8         |
| des-ede3-cbc      | 24         | 8         |
| des-ede3-cfb      | 24         | 8         |
| des-ede3-cfb1     | 24         | 8         |
| des-ede3-cfb8     | 24         | 8         |
| des-ede3-ofb      | 24         | 8         |
| sm4-cbc           | 16         | 16        |
| sm4-cfb           | 16         | 16        |
| sm4-ctr           | 16         | 16        |
| sm4-ofb           | 16         | 16        |
| aria-256-ecb      | 32         | NOT       |
| camellia-128-ecb  | 16         | NOT       |
| camellia-192-ecb  | 24         | NOT       |
| camellia-256-ecb  | 32         | NOT       |
| des-ede-ecb       | 16         | NOT       |
| sm4-ecb           | 16         | NOT       |
| des-ede3-ecb      | 24         | NOT       |
| aes-128-ecb       | 16         | NOT       |
| aes-192-ecb       | 24         | NOT       |
| aes-256-ecb       | 32         | NOT       |
| aria-128-ecb      | 16         | NOT       |
| aria-192-ecb      | 24         | NOT       |