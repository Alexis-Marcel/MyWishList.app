<?php

namespace wish\Controllers\Auth;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\Controllers\Controller;
use wish\Models\User;
use Respect\Validation\Validator as v;

class  DeleteAccountController extends Controller
{

    public function getDeleteAccount(Request $request, Response $response)
    {
        return $this->view->render($response,'auth/deleteAccount.twig');
    }

    public function postDeleteAccount(Request $request, Response $response)
    {
        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING) ;

        $auth = $this->auth->deleteAccount($password);

        if(!$auth) {
            $this->flash->addMessage('error','Could not delete your account with those details.');
            return $response->withRedirect($this->router->pathFor('auth.account.delete'));

        }

        return $response->withRedirect($this->router->pathFor('home'));

    }

}