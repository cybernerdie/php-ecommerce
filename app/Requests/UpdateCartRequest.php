<?php

namespace App\Requests;

use App\Core\Request;

class UpdateCartRequest extends Request
{
    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:1',
        ];
    }
}
