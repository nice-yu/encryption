<?php
declare(strict_types=1);

namespace NiceYu\Encryption\Dto;

class JwtAuthDto
{
    public string $id;
    public int $expire;
    public PayloadDto $payload;
}