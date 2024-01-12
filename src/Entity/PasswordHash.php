<?php

namespace App\Entity;

abstract class PasswordHash
{
    /**
     * Be careful when changing these
     */
    private const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;
    private const PASSWORD_OPTIONS = ['cost' => 12];

    public static function hash(string $password): string
    {
        $passwordHash = password_hash($password, static::PASSWORD_ALGORITHM, static::PASSWORD_OPTIONS);

        if (!$passwordHash) {
            throw new \LogicException('Failed to hash password.');
        }

        return $passwordHash;
    }

    public static function verify(string $hash, string $password): bool
    {
        return password_verify($password, $hash);
    }

    public static function randomPassword(
        int $length = 12,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string
    {
        return substr(str_shuffle($keyspace), 0, $length);
    }
}
