<?php

namespace wish\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\models\Item;
use wish\models\Liste;

class ItemController extends Controller
{
    public function index(Request $request, Response $response, $parameters)
    {
        $id = filter_var($parameters['id'], FILTER_SANITIZE_STRING);
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);

        $item = Item::where( 'id', '=', $id )->first();

        $content = $this->ItemView->getItemById($item,$secureToken);
        $this->view->getEnvironment()->addGlobal('item',$content);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->itemLink($secureToken,$id));

        return $this->view->render($response, 'item.twig');
    }

}