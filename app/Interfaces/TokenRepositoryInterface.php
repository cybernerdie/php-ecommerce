<?php

namespace App\Interfaces;

interface TokenRepositoryInterface
{
    public function baseQuery();

    public function generateToken(int $userId);

    public function saveToken(int $userId, string $token);
    
    public function deleteToken(int $userId);
}