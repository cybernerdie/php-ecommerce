<?php

namespace App\Repositories;

use App\Interfaces\TokenRepositoryInterface;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Contracts\Database\Query\Builder;

class TokenRepository implements TokenRepositoryInterface
{
    /**
     * Returns the base query builder instance for token table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function baseQuery(): Builder
    {
        return DB::table('tokens');
    }

    /**
     * Generates a new token for the given user ID and saves it to the database.
     *
     * @param int $userId
     * @return string
     */
    public function generateToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $this->saveToken($userId, $token);

        return $token;
    }

    /**
     * Saves the given token for the given user ID to the database.
     *
     * @param int $userId
     * @param string $token
     * @return bool
     */
    public function saveToken(int $userId, string $token): bool
    {
        $expiry = time() + TOKEN_EXPIRY;

        return $this->baseQuery()
            ->insert([
                'user_id' => $userId,
                'token' => $token,
                'expiry' => date('Y-m-d H:i:s', $expiry),
            ]);
    }

    /**
     * Deletes the token associated with the given user ID from the database.
     *
     * @param int $userId
     * @return int
     */
    public function deleteToken(int $userId): int
    {
        return $this->baseQuery()
            ->where('user_id', $userId)
            ->delete();
    }
}