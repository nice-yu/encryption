<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Encryptors;

/**
 * Abstract class for encryption, providing common methods for encoding and decoding.
 */
abstract class AbstractEncryptor implements EncryptorInterface
{
    protected string $output;

    /**
     * Constructor for AbstractEncryptor.
     *
     * @param string $output The output format for encryption (default: 'base64').
     */
    public function __construct(string $output = 'base64')
    {
        $this->output = $output;
    }

    /**
     * Encodes the given data based on the output format.
     *
     * @param string $data The data to be encoded.
     * @return string The encoded data.
     */
    protected function encodeOutput(string $data): string
    {
        switch ($this->output) {
            case self::OUTPUT_BASE64:
                return base64_encode($data);
            case self::OUTPUT_HEX:
                return bin2hex($data);
            default:
                return $data;
        }
    }

    /**
     * Decodes the given data based on the output format.
     *
     * @param string $data The data to be decoded.
     * @return string The decoded data.
     */
    protected function decodeInput(string $data): string
    {
        switch ($this->output) {
            case self::OUTPUT_BASE64:
                return base64_decode($data);
            case self::OUTPUT_HEX:
                return hex2bin($data);
            default:
                return $data;
        }
    }
}
