<?php

namespace App\Services;

use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;

class AuthService
{
    protected UserRepository $userRepository;
    protected TokenRepository $tokenRepository;
    
    public function __construct(
        UserRepository $userRepository, 
        TokenRepository $tokenRepository
    ) {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Authenticate a user by their email and password.
     *
     * @param  string $email
     * @param  string $password
     * @return array|false
     */
    public function authenticate(string $email, string $password): array|false
    {
        $user = $this->userRepository->getByEmail($email);
    
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }
    
        $token = $this->tokenRepository->generateToken($user->id);
    
        return [$user, $token];
    }    
}
