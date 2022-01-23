<?php

namespace wish\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\Models\Item;

class ReservationMiddleware extends Middleware
{
    public function  __invoke(Request $request, Response $response, $next) {

        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');
        $secureToken = $route->getArgument('secureToken');

        if($this->container->reservationCheck->checkCreator($secureToken)){

            $this->container->flash->addMessage('error','The list creator cannot reserve an item.');
            return $response->withRedirect($this->container->router->pathFor('item',['secureToken' => $secureToken,'id' => $id]));
        }

        $list =Item::find($id)->liste;
        if($this->container->dateFunction->expired(date("Y-m-d"),$list->expiration)){

            $this->container->flash->addMessage('error','The deadline for the list has been reached. Unable to reserve an item.');
            return $response->withRedirect($this->container->router->pathFor('item',['secureToken' => $secureToken,'id' => $id]));

        }

        if($this->container->reservationCheck->checkReservation($id)){
            $this->container->flash->addMessage('error','This item is already reserved');
            return $response->withRedirect($this->container->router->pathFor('item',['secureToken' => $secureToken,'id' => $id]));

        }

        $response = $next($request,$response);
        return $response;
    }
}