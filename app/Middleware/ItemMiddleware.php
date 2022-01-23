<?php

namespace wish\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\models\Item;

class ItemMiddleware extends Middleware
{

    public function  __invoke(Request $request, Response $response, $next) {

        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');
        $secureToken = $route->getArgument('secureToken');

        $list =Item::find($id)->liste;

        if($secureToken != $list->token){
            $this->container->flash->addMessage('error','This item does not belong to this list.');
            return $response->withRedirect($this->container->router->pathFor('liste',['secureToken' => $secureToken]));
        }

        $response = $next($request,$response);
        return $response;
    }
}