<?php

namespace wish\Controllers;

use \Slim\Container;

class Controller
{
    protected Container $container;

    public function __construct(Container $c)
    {
        $this->container = $c;
    }

    public  function __get($property)
    {
        if ($this->container->{$property}){
            return $this->container->{$property};
        }
    }
}