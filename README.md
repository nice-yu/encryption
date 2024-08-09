# Encryption

> [简体中文](README.zh-CN.md) | [English](README.md)

## 概述

`niceyu/encryption` 是一个简单而强大的 PHP 加密库，支持多种加密算法，包括 OpenSSL(全部) 和 RSA。它能够处理不同格式的输出，如 base64 和 hex。这个库旨在为开发者提供一个简便的接口来实现数据的加密和解密，确保数据传输的安全性。


## 安装

在项目中使用 Composer 安装此库：
```bash
composer require niceyu/encryption
```

## 使用方法
| 加密器               | 简体中文                    | English              |
|-------------------|-------------------------|----------------------|
| RSAEncryptor      | [简体中文](README.zh-CN.md) | [English](README.md) |
| OpensslEncryptor  | [简体中文](README.zh-CN.md) | [English](README.md) |
| RandomEncryptor   | [简体中文](README.zh-CN.md) | [English](README.md) |
| PasswordEncryptor | [简体中文](README.zh-CN.md) | [English](README.md) |
| JwtAuthEncryptor  | [简体中文](README.zh-CN.md) | [English](README.md) |

## 单元测试
```shell
PHPUnit 9.6.20 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.4
Configuration: /var/www/packages/encryption/phpunit.xml

Encryptor (NiceYu\Encryption\Tests\Encryptor)
✔ Encrypt and decrypt base 64 [5.43 ms]
✔ Encrypt and decrypt hex [0.35 ms]
✔ Invalid cipher [0.31 ms]
✔ Encrypt not base 64 hex [0.54 ms]
✔ Encrypt not supported [0.58 ms]
✔ Decrypt not supported [0.22 ms]
✔ Decrypt failed [27.37 ms]

Functions (NiceYu\Encryption\Tests\Functions)
✔ Is encrypt with valid cipher [1.49 ms]
✔ Is encrypt with invalid cipher [1.19 ms]
✔ Encrypt flex key with valid cipher [0.13 ms]
✔ Encrypt flex key with invalid cipher [0.15 ms]
✔ Encrypt flex key with short key [0.13 ms]
✔ Encrypt flex key with long key [0.30 ms]
✔ Encrypt flex iv with valid cipher [0.13 ms]
✔ Encrypt flex iv with invalid cipher [0.13 ms]
✔ Encrypt flex iv with short iv [0.13 ms]
✔ Encrypt flex iv with long iv [0.17 ms]

Jwt Auth Encryptor (NiceYu\Encryption\Tests\JwtAuthEncryptor)
✔ Aes encrypt and decrypt [313.23 ms]
✔ Aes decrypt with expired token [88.26 ms]
✔ Rsa encrypt and decrypt [125.75 ms]
✔ Rsa decrypt with expired token [101.82 ms]

Password Encryptor (NiceYu\Encryption\Tests\PasswordEncryptor)
✔ Constructor with valid encryptor [76.88 ms]
✔ Rsa encrypt password [31.48 ms]
✔ Rsa decrypt password [64.90 ms]
✔ Aes encrypt password [180.93 ms]
✔ Aes decrypt password [72.77 ms]
✔ Encrypt password failure [105.62 ms]
✔ Decrypt password failure [110.38 ms]

RSAEncryptor (NiceYu\Encryption\Tests\RSAEncryptor)
✔ Constructor with valid keys [84.94 ms]
✔ Constructor with invalid public key [112.06 ms]
✔ Constructor with invalid private key [147.95 ms]
✔ Encrypt with invalid public key [129.42 ms]
✔ Decrypt with invalid private key [167.48 ms]
✔ Encrypt and decrypt [135.20 ms]
✔ Encrypt and decrypt base 64 [66.21 ms]
✔ Encrypt and decrypt hex [66.27 ms]
✔ Invalid public key [175.64 ms]
✔ Invalid private key [132.21 ms]
```
## 许可证

本项目采用 [MIT 许可证](LICENSE) 开源。详细信息请查看 LICENSE 文件。