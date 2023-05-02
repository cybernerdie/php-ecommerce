<?php

namespace App\Requests;

use App\Core\Request;

class StoreCartRequest extends Request
{
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }
}
