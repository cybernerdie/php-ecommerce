<?php

require 'vendor/autoload.php';
require 'core/bootstrap.php';
require 'routes/api.php';
require 'core/cors.php';

use App\Core\App;
use App\Core\Route;
use App\Core\Request;
use App\Core\Response;
use App\Exceptions\CustomException;

App::bind('router', new Route());
App::bind('request', new Request());

$router = App::get('router');
$request = App::get('request');

try {
    $router->route($request);
} catch (CustomException $e) {
    error_log($e, 3, "error.log");
    return Response::send(false, HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
}
