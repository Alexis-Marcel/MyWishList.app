<?php

namespace wish\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware extends Middleware
{
    public function  __invoke(Request $request, Response $response, $next) {

        if(!$this->container->auth->check()) {
            $this->container->flash->addMessage('error','Please sign in before doing that.');
            return $response->withRedirect($this->container->router->pathFor('auth.signup'));

        }

        $response = $next($request,$response);
        return $response;
    }
}