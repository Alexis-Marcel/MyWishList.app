<?php

namespace wish\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use wish\models\Liste;


class HomeController extends Controller
{

    public function index(Request $request, Response $response, $parameters)
    {
        if($this->auth->check()){
            $lists = $this->auth->user()->getListes;
            $content = $this->ListeView->getListes($lists);
        }
        else {
            $content = "<p>Login to access your own lists</p>";
        }
        $publique = Liste::where('visibilite','=',0)->get();

        $contentpub = $this->ListeView->getListes($publique);

        $this->view->getEnvironment()->addGlobal('listes',$content);

        $this->view->getEnvironment()->addGlobal('ListePublique',$contentpub);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->homeLink());

        return $this->view->render($response, 'home.twig');
    }

    public function getTokenPage(Request $request, Response $response, $parameters)
    {
        $token = filter_var($request->getParam('token'), FILTER_SANITIZE_STRING);

        return $response->withRedirect($this->router->pathFor('liste',['secureToken' => $token]));

    }
}