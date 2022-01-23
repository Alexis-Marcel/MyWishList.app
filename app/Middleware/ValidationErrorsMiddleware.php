<?php

namespace wish\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

class ValidationErrorsMiddleware extends Middleware
{
    public function  __invoke(Request $request, Response $response, $next) {

        if(isset($_SESSION['errors'])){
            $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);

            unset($_SESSION['errors']);
        }

        $response = $next($request,$response);
        return $response;
    }
}