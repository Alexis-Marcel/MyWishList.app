<?php

namespace wish\Controllers\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use wish\Controllers\Controller;

use Respect\Validation\Validator as v;
use wish\Models\User;

class AuthController extends Controller
{

    public function getSignOut(Request $request, Response $response){

        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));

    }

    public function getSignup(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }


    public function postSignUp(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
           'name' => v::nameAvailable()->alpha()->noWhitespace()->notEmpty(),
           'password' => v::length(5,null)->noWhitespace()->notEmpty(),
            'repassword' => v::passwordConfirmation(filter_var($request->getParam('password'), FILTER_SANITIZE_STRING)),
        ]);

        if($validation->failed()){

            $this->flash->addMessage('error','Could not sign you up with those details.');
            //redirect back
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING) ;
        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING) ;

        $user = User::create([
           'name' => $name,
           'password' => password_hash($password,PASSWORD_DEFAULT),

        ]);

        $this->flash->addMessage('success','You have been signed up !');

        $this->auth->attempt($user->name, $request->getParam('password'));

        //redirect home
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function postSignIn(Request $request, Response $response)
    {
        $name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING) ;
        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING) ;
        $auth = $this->auth->attempt(
            $name,
            $password,
        );

        if(!$auth) {
            $this->flash->addMessage('error','Could not sign you in with those details.');
            return $response->withRedirect($this->router->pathFor('auth.signup'));

        }

        return $response->withRedirect($this->router->pathFor('home'));

    }
}