> [简体中文](README.zh-CN.md) | [English](README.md)

## 概述

`niceyu/encryption` 是一个简单而强大的 PHP 加密库，支持多种加密算法，包括 AES 和 RSA。它能够处理不同格式的输出，如 base64 和 hex。这个库旨在为开发者提供一个简便的接口来实现数据的加密和解密，确保数据传输的安全性。

## 安装

在项目中使用 Composer 安装此库：
```bash
composer require niceyu/encryption
```

## 使用方法

### AES 加密
- `AES` 是一种对称加密算法，支持多种模式, 其他模式请看 [算法对照表](#算法对照表)
- 下面是如何使用 `AES-128-CBC` 模式进行加密和解密的示例。

```php
use NiceYu\Encryption\Encryptors\Encryptor;

$cipher = 'AES-128-CBC';
$key = 'thisisaverysecurekey1234';
$iv = '1234567890123456';

// 创建 Encryptor 实例
$encryptor = new Encryptor($cipher, $key, $iv);

// 加密数据
$plaintext = 'Hello, OpenSSL!';
$encrypted = $encryptor->encrypt($plaintext);

// 解密数据
$decrypted = $encryptor->decrypt($encrypted);

echo "加密后的数据: $encrypted\n";
echo "解密后的数据: $decrypted\n";
```

### RSA 加密

`RSA` 是一种非对称加密算法，常用于加密小块数据或加密对称密钥。

```php
use NiceYu\Encryption\Encryptors\RSAEncryptor;

$publicKey = file_get_contents('path/to/public_key.pem');
$privateKey = file_get_contents('path/to/private_key.pem');

// 创建 RSAEncryptor 实例
$encryptor = new RSAEncryptor($publicKey, $privateKey);

// 加密数据
$plaintext = 'Hello, RSA!';
$encrypted = $encryptor->encrypt($plaintext);

// 解密数据
$decrypted = $encryptor->decrypt($encrypted);

echo "加密后的数据: $encrypted\n";
echo "解密后的数据: $decrypted\n";
```

## 函数说明

### is_encrypt

检查给定的加密算法是否有效。

```php
function is_encrypt(string $cipher): bool
```

**参数**
- `cipher` (string): 加密算法名称 [算法对照表](#算法对照表)

**返回值**
- `bool`: 如果加密算法有效返回 `true`，否则返回 `false`。

### encrypt_flex_key

基于输入加密算法的自动截取加密密钥。

```php
function encrypt_flex_key(string $cipher, string $data): string
```

**参数**
- `cipher` (string): 加密算法名称
- `data` (string): key 密钥数据, 如果无法确定, 可以长一点，会自动截取。

**返回值**
- `string`: 生成的加密密钥。

### encrypt_flex_iv

基于输入加密算法的自动截取初始化向量 (IV)。

```php
function encrypt_flex_iv(string $data): string
```

**参数**
- `cipher` (string): 加密算法名称
- `data` (string): 初始化向量数据, 如果无法确定, 可以长一点，会自动截取。

**返回值**
- `string`: 生成的初始化向量。

## 示例代码

以下是一个完整的示例代码，演示如何使用 `niceyu/encryption` 库进行 AES 和 RSA 加密解密操作。

```php
require 'vendor/autoload.php';

use NiceYu\Encryption\Encryptors\Encryptor;
use NiceYu\Encryption\Encryptors\RSAEncryptor;

// AES 加密解密示例
$cipher = 'AES-128-CBC';
$key = 'thisisaverysecurekey1234';
$iv = '1234567890123456';
$encryptor = new Encryptor($cipher, $key, $iv);

$plaintext = 'Hello, AES!';
$encrypted = $encryptor->encrypt($plaintext);
$decrypted = $encryptor->decrypt($encrypted);

echo "AES 加密后的数据: $encrypted\n";
echo "AES 解密后的数据: $decrypted\n";

// RSA 加密解密示例
$publicKey = file_get_contents('path/to/public_key.pem');
$privateKey = file_get_contents('path/to/private_key.pem');
$rsaEncryptor = new RSAEncryptor($publicKey, $privateKey);

$plaintextRSA = 'Hello, RSA!';
$encryptedRSA = $rsaEncryptor->encrypt($plaintextRSA);
$decryptedRSA = $rsaEncryptor->decrypt($encryptedRSA);

echo "RSA 加密后的数据: $encryptedRSA\n";
echo "RSA 解密后的数据: $decryptedRSA\n";
```

请注意，ECB 模式是一种基本的加密模式，它将每个数据块独立加密，不考虑前后数据块之间的关系。这可能导致一些安全问题，因此不推荐在实际应用中使用 ECB 模式。在实际应用中，建议使用更安全的加密模式，如 CBC、CTR 或 GCM。

### 算法对照表
| 加密方式              | key 位数 | iv 位数 |
|-------------------|--------|-------|
| rsa               | pem    | pem   |
| aes-128-cbc       | 16     | 16    |
| aes-128-cfb       | 16     | 16    |
| aes-128-cfb1      | 16     | 16    |
| aes-128-cfb8      | 16     | 16    |
| aes-128-ctr       | 16     | 16    |
| aes-128-ofb       | 16     | 16    |
| aes-128-wrap-pad  | 16     | 4     |
| aes-128-xts       | 32     | 16    |
| aes-192-cbc       | 24     | 16    |
| aes-192-cfb       | 24     | 16    |
| aes-192-cfb1      | 24     | 16    |
| aes-192-cfb8      | 24     | 16    |
| aes-192-ctr       | 24     | 16    |
| aes-192-ofb       | 24     | 16    |
| aes-192-wrap-pad  | 24     | 4     |
| aes-256-cbc       | 32     | 16    |
| aes-256-cfb       | 32     | 16    |
| aes-256-cfb1      | 32     | 16    |
| aes-256-cfb8      | 32     | 16    |
| aes-256-ctr       | 32     | 16    |
| aes-256-ofb       | 32     | 16    |
| aes-256-wrap-pad  | 32     | 4     |
| aes-256-xts       | 32     | 16    |
| aria-128-cbc      | 16     | 16    |
| aria-128-cfb      | 16     | 16    |
| aria-128-cfb1     | 16     | 16    |
| aria-128-cfb8     | 16     | 16    |
| aria-128-ctr      | 16     | 16    |
| aria-128-ofb      | 16     | 16    |
| aria-192-cbc      | 24     | 16    |
| aria-192-cfb      | 24     | 16    |
| aria-192-cfb1     | 24     | 16    |
| aria-192-cfb8     | 24     | 16    |
| aria-192-ctr      | 24     | 16    |
| aria-192-ofb      | 24     | 16    |
| aria-256-cbc      | 32     | 16    |
| aria-256-cfb      | 32     | 16    |
| aria-256-cfb1     | 32     | 16    |
| aria-256-cfb8     | 32     | 16    |
| aria-256-ctr      | 32     | 16    |
| aria-256-ofb      | 32     | 16    |
| camellia-128-cbc  | 16     | 16    |
| camellia-128-cfb  | 16     | 16    |
| camellia-128-cfb1 | 16     | 16    |
| camellia-128-cfb8 | 16     | 16    |
| camellia-128-ctr  | 16     | 16    |
| camellia-128-ofb  | 16     | 16    |
| camellia-192-cbc  | 24     | 16    |
| camellia-192-cfb  | 24     | 16    |
| camellia-192-cfb1 | 24     | 16    |
| camellia-192-cfb8 | 24     | 16    |
| camellia-192-ctr  | 24     | 16    |
| camellia-192-ofb  | 24     | 16    |
| camellia-256-cbc  | 32     | 16    |
| camellia-256-cfb  | 32     | 16    |
| camellia-256-cfb1 | 32     | 16    |
| camellia-256-cfb8 | 32     | 16    |
| camellia-256-ctr  | 32     | 16    |
| camellia-256-ofb  | 32     | 16    |
| chacha20          | 32     | 16    |
| des-ede-cbc       | 16     | 8     |
| des-ede-cfb       | 16     | 8     |
| des-ede-ofb       | 16     | 8     |
| des-ede3-cbc      | 24     | 8     |
| des-ede3-cfb      | 24     | 8     |
| des-ede3-cfb1     | 24     | 8     |
| des-ede3-cfb8     | 24     | 8     |
| des-ede3-ofb      | 24     | 8     |
| sm4-cbc           | 16     | 16    |
| sm4-cfb           | 16     | 16    |
| sm4-ctr           | 16     | 16    |
| sm4-ofb           | 16     | 16    |
| aria-256-ecb      | 32     | 不需要   |
| camellia-128-ecb  | 16     | 不需要   |
| camellia-192-ecb  | 24     | 不需要   |
| camellia-256-ecb  | 32     | 不需要   |
| des-ede-ecb       | 16     | 不需要   |
| sm4-ecb           | 16     | 不需要   |
| des-ede3-ecb      | 24     | 不需要   |
| aes-128-ecb       | 16     | 不需要   |
| aes-192-ecb       | 24     | 不需要   |
| aes-256-ecb       | 32     | 不需要   |
| aria-128-ecb      | 16     | 不需要   |
| aria-192-ecb      | 24     | 不需要   |