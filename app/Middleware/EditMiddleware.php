<?php

namespace wish\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\Models\Item;

class EditMiddleware extends Middleware
{

    public function  __invoke(Request $request, Response $response, $next) {

        $route = $request->getAttribute('route');
        $secureToken = $route->getArgument('secureToken');

        if(!$this->container->reservationCheck->checkCreator($secureToken)){
            $this->container->flash->addMessage('error','Only the creator of the list can modify it.');
            return $response->withRedirect($this->container->router->pathFor('liste',['secureToken' => $secureToken]));
        }

        $response = $next($request,$response);
        return $response;
    }
}