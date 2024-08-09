<?php
declare(strict_types=1);
namespace NiceYu\Encryption\Encryptors;

use JMS\Serializer\SerializerBuilder;
use NiceYu\Encryption\Dto\JwtAuthDto;
use NiceYu\Encryption\Dto\PayloadDto;

/**
 * class JwtAuthEncryptor
 */
class JwtAuthEncryptor
{
    private EncryptorInterface $encryptor;

    private PayloadDto $payload;

    /**
     * @param EncryptorInterface $encryptor
     * @param PayloadDto $payload
     */
    public function __construct(EncryptorInterface $encryptor, PayloadDto $payload)
    {
        $this->payload = $payload;
        $this->encryptor = $encryptor;
    }

    /**
     * jwt auth encrypt
     * @param string $id
     * @param int $expire
     * @return string
     */
    public function encrypt(string $id, int $expire = 3600): string
    {
        $dto = new JwtAuthDto();
        $dto->id = $id;
        $dto->expire = $expire;
        $dto->payload = $this->payload;

        /** encrypt the content */
        $serializer = SerializerBuilder::create()->build();

        /** Return token */
        return $this->encryptor->encrypt($serializer->serialize($dto,'json'));
    }

    /**
     * jwt auth decrypt
     * @param string $token
     * @return JwtAuthDto|null
     */
    public function decrypt(string $token): ?JwtAuthDto
    {
        /** Parse the token out */
        $data = $this->encryptor->decrypt($token);

        /** Convert info into AuthDto */
        $obj = SerializerBuilder::create()->build()->deserialize($data, JwtAuthDto::class, 'json');

        /** Verification expiration time */
        if (($obj->expire + $obj->payload->nbf) < time()) {
            return null;
        }
        return $obj;
    }
}