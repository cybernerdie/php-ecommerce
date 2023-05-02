<?php

namespace App\Requests;

use App\Core\Request;

class RegisterRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }
}
