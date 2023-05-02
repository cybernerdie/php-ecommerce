<?php

namespace App\Requests;

use App\Core\Request;

class AllProductRequest extends Request
{
    public function rules()
    {
        return [
            'page' => 'nullable|integer',
            'sort_by' => 'nullable|string|in:name,price,date',
            'sort_order' => 'nullable|string|in:asc,desc',
            'search_item' => 'nullable|string'
        ];
    }
}
