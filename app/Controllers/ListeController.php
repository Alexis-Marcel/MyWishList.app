<?php

namespace wish\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\models\Liste;

class ListeController extends Controller
{

    public function index(Request $request, Response $response, $parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $liste = Liste::where( 'token', '=', $secureToken )->first()  ;
        $content = $this->ListeView->getListeByNo($liste);
        $this->view->getEnvironment()->addGlobal('listeInformation',$content);

        $listeItems = $liste->recupItems;
        $content = $this->ItemView->getItemByListe($listeItems,$secureToken);
        $this->view->getEnvironment()->addGlobal('listeItem',$content);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->listeLink($secureToken));

        return $this->view->render($response, 'liste.twig');
    }
}