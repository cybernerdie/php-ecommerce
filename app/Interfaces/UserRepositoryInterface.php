<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function baseQuery();

    public function storeUser(array $data);

    public function findById(int $id);
    
    public function getByEmail(string $email);

    public function getByToken(string $token);

    public function deleteUser(int $userId);
}