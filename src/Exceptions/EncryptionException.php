<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Exceptions;

use Exception;

/**
 * Custom exception class for handling encryption-related errors.
 */
class EncryptionException extends Exception
{
    /**
     * Constructor for EncryptionException.
     *
     * @param string $message The exception message.
     * @param int $code The exception code (default: 0).
     * @param Exception|null $previous The previous exception used for chaining (optional).
     */
    public function __construct(string $message, int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
