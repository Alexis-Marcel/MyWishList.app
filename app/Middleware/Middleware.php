<?php

namespace wish\Middleware;

class Middleware
{
    protected \Slim\Container $container;

    public function __construct($c)
    {
        $this->container = $c;
    }

}