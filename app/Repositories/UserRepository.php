<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Contracts\Database\Query\Builder;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Returns the base query builder instance for user table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('users');
    }

     /**
     * Add a product to the cart.
     *
     * @param array $data
     * @return mixed
     */
    public function storeUser(array $data)
    {
        $userId = $this->baseQuery()
            ->insertGetId($data);

        return $this->findById($userId);
    }

    /**
     * Retrieves a user record by ID.
     *
     * @param int $id
     * @return object|null
     */
    public function findById(int $id)
    {
        return $this->baseQuery()
            ->where('id', $id)
            ->first();
    }

    /**
     * Retrieves a user record by email.
     *
     * @param string $email
     * @return object|null
     */
    public function getByEmail(string $email)
    {
        return $this->baseQuery()
            ->where('email', $email)
            ->first();
    }

    /**
     * Returns the user associated with the given token, if the token is valid and has not expired.
     *
     * @param string $token
     * @return mixed
     */
    public function getByToken(string $token)
    {
        return $this->baseQuery()
            ->select('users.*')
            ->join('tokens', 'users.id', '=', 'tokens.user_id')
            ->where('tokens.token', $token)
            ->where('tokens.expiry', '>', date('Y-m-d H:i:s'))
            ->first();
    }

    /**
     * Deletes a user
     *
     * @param int $userId
     * @return int
     */
    public function deleteUser(int $userId): int
    {
        return $this->baseQuery()
            ->where('id', $userId)
            ->delete();
    }
}