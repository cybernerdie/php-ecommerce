<?php

namespace App\Controllers;

use App\Requests\LoginRequest;
use App\Core\Request;
use App\Core\Response;
use App\Requests\RegisterRequest;
use App\Resources\UserResource;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;

class AuthController
{
    protected UserRepository $userRepository;
    protected TokenRepository $tokenRepository;
    protected AuthService $auth;
    
    public function __construct(
        UserRepository $userRepository, 
        TokenRepository $tokenRepository,
        AuthService $auth
    ) {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->auth = $auth;
    }
    
    /**
     * Logs in a user given their email and password.
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        [$user, $token] = $this->auth->authenticate($email, $password);

        if (!$user) {
            return Response::send(false, 401, 'Invalid credentials');
        }

        $userResource = (new UserResource($user))->toArray();

        $data = [
            'user' => $userResource,
            'token' => $token
        ];

        return Response::send(true, 200, 'Login successful.', $data);
    }

    /**
     * Registers a new user.
     *
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = password_hash($request->password, PASSWORD_DEFAULT);
        
        $user = $this->userRepository->storeUser($validatedData);
        $userResource = (new UserResource($user))->toArray();

        // Generate a new token for the user
        $token =  $this->tokenRepository->generateToken($user->id);

        $data = [
            'user' => $userResource,
            'token' => $token
        ];

        return Response::send(true, HTTP_CREATED, 'Registration successful', $data);
    }

    /**
     * Logs out the authenticated user.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $userId = $request->user->id;
        $this->tokenRepository->deleteToken($userId);

        return Response::send(true, 200, 'Logout successful');
    }
}