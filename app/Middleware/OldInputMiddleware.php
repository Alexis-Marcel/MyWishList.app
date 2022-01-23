<?php

namespace wish\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class OldInputMiddleware extends Middleware
{
    public function  __invoke(Request $request, Response $response, $next) {

        $this->container->view->getEnvironment()->addGlobal('old',$_SESSION['old']);

        $_SESSION['old'] = $request->getParams();

        $response = $next($request,$response);
        return $response;
    }
}