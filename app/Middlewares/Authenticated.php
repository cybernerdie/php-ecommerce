<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\UserRepository;
use Core\Interfaces\MiddlewareInterface;

class Authenticated implements MiddlewareInterface
{
    public function handle(Request $request)
    {
        $header = $request->getHeader('Authorization');

        if (!$header || !preg_match('/^Bearer\s+(.*?)$/', $header, $matches)) {
            return Response::send(false, HTTP_UNAUTHORIZED, 'Invalid authorization header');
        }

        $token = $matches[1];
        $user = (new UserRepository())->getByToken($token);

        if (!$user) {
            return Response::send(false, HTTP_UNAUTHORIZED, 'Unauthenticated');
        }

        $request->setUser($user);

        return true;
    }
}
