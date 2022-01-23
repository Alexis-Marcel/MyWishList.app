<?php

namespace wish\Controllers\Auth;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use wish\Controllers\Controller;
use wish\Models\User;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{

    public function getChangePassword(Request $request, Response $response)
    {
        return $this->view->render($response,'auth/changePassword.twig');
    }

    public function postChangePassword(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'password_old' => v::matchesPassword($this->auth->user()->password)->noWhitespace()->notEmpty(),
            'password' => v::length(5,null)->noWhitespace()->notEmpty(),
            'repassword' => v::passwordConfirmation(filter_var($request->getParam('password'), FILTER_SANITIZE_STRING)),
        ]);

        if($validation->failed()){

            $this->flash->addMessage('error','Could not change your password with those details.');
            //redirect back
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING) ;


        $this->auth->user()->setPassword($password);

        $this->auth->logout();

        $this->flash->addMessage('success','Your password was changed.');

        return $response->withRedirect($this->router->pathFor('auth.signup'));


    }

}