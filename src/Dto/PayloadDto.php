<?php
declare(strict_types=1);
namespace NiceYu\Encryption\Dto;

class PayloadDto
{
    /**
     * Issuer
     * @var string
     */
    public string $iss;

    /**
     * Audience
     * @var string
     */
    public string $aud;

    /**
     * Issued At
     * @var int
     */
    public int $iat;

    /**
     * Not Before
     * @var int
     */
    public int $nbf;

}