<?php

namespace App\Core\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

class Connection
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function boot()
    {
        $capsule = new Capsule;

        $capsule->addConnection($this->config);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

        return $capsule;
    }
}