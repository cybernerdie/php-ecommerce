<?php

namespace App\Controllers;

use App\Core\Response;

class HomeController
{
    public function index()
    {
        return Response::send(true, HTTP_OK, 'Welcome here');
    }
}
