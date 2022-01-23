<?php

namespace wish\ViewList;

use Slim\Container;

class View
{
    protected Container $container;


    public function __construct(Container $c)
    {

        $this->container = $c;
    }

}