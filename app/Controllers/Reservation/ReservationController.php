<?php

namespace wish\Controllers\Reservation;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\Controllers\Controller;
use wish\Models\Item;
use wish\Models\Reservation;

class ReservationController extends Controller
{
    public function getReservation(Request $request, Response $response, $parameters)
    {

        $secureToken = $parameters['secureToken'];
        $id = $parameters['id'];


        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editItemLink($secureToken,$id));

        $this->view->getEnvironment()->addGlobal('value',[
            'token' => $secureToken,
            'id' => $id,
            'nameItem' => Item::find($id)->nom,
        ]);

        return $this->view->render($response, 'Reservation/reservation.twig');
    }

    public function postReservation(Request $request, Response $response, $parameters)
    {

        $secureToken = $parameters['secureToken'];
        $id = $parameters['id'];

        Reservation::create([
            'id_item' => $id,
            'reservation_name' => filter_var($request->getParam('name'), FILTER_SANITIZE_STRING),
            'reservation_message' => filter_var($request->getParam('message'), FILTER_SANITIZE_STRING),
        ]);

        $name = Item::find($id)->nom;

        $this->flash->addMessage('success',"The item \"{$name}\" has been reserved !");

        return $response->withRedirect($this->router->pathFor('item',['secureToken' => $secureToken,'id' => $id]));

    }

}