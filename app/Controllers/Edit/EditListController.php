<?php

namespace wish\Controllers\Edit;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use wish\Controllers\Controller;
use wish\Models\Liste;

class EditListController extends Controller
{
    public function getAddList(Request $request, Response $response)
    {
        return $this->view->render($response, 'edit/addList.twig');
    }

    public function postAddList(Request $request, Response $response)
    {
        $tokenSecure = $this->kodex_random_string();

        $validation = $this->validator->validate($request, [
            'title' => v::notEmpty(),
            'descr' => v::notEmpty(),
            'expiration' => v::date('Y-m-d')->noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){

            $this->flash->addMessage('error','Could not create the list with those details');
            //redirect back
            return $response->withRedirect($this->router->pathFor('edit.addlist'));
        }

        $liste = Liste::create([
            'user_id' => $this->auth->user()->id,
            'titre' => filter_var($request->getParam('title'), FILTER_SANITIZE_STRING),
            'description' => filter_var($request->getParam('descr'), FILTER_SANITIZE_STRING),
            'expiration' => filter_var($request->getParam('expiration'), FILTER_SANITIZE_STRING),
            'token' => $tokenSecure,

        ]);

        if(isset($_COOKIE ['wishLists'])){
            $wishlists = unserialize($_COOKIE['wishLists'], ["allowed_classes" => false]);
        }
        $wishlists[] =$tokenSecure;

        setcookie('wishLists', serialize($wishlists), time() + 365*24*3600,'/','webetu.iutnc.univ-lorraine.fr', false, false);

        $this->flash->addMessage('success',"The list \"{$request->getParam('title')}\" has been created !");



        //redirect home
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getEditList(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $list = Liste::where('token','=',$secureToken)->first();

        $this->view->getEnvironment()->addGlobal('old_value',[
            'token' => $secureToken,
            'title' => $list->titre,
            'descr' => $list->description,
            'expiration' => $list->expiration
        ]);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editListLink($secureToken));


        return $this->view->render($response, 'edit/editList.twig');
    }

    public function postEditList(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);

        $validation = $this->validator->validate($request, [
            'title' => v::notEmpty(),
            'descr' => v::notEmpty(),
            'expiration' => v::date('Y-m-d')->noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()){

            $this->flash->addMessage('error','Could not modify the list with those details.');
            //redirect back
            return $response->withRedirect($this->router->pathFor('edit.editlist',['secureToken' => $secureToken]));
        }
        $list = Liste::where('token','=',$secureToken);

        $list->update([
            'titre' => filter_var($request->getParam('title'), FILTER_SANITIZE_STRING),
            'description' => filter_var($request->getParam('descr'), FILTER_SANITIZE_STRING),
            'expiration' => filter_var($request->getParam('expiration'), FILTER_SANITIZE_STRING),
        ]);

        $this->flash->addMessage('success',"The list \"{$request->getParam('title')}\" has been modified !");

        return $response->withRedirect($this->router->pathFor('liste',['secureToken' => $secureToken]));
    }

    public function getRemoveList(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $list = Liste::where('token','=',$secureToken)->first();

        $this->view->getEnvironment()->addGlobal('value',[
            'token' => $secureToken,
            'title' => $list->titre,
            'descr' => $list->description,
            'expiration' => $list->expiration
        ]);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editListLink($secureToken));

        return $this->view->render($response, 'edit/removeList.twig');
    }

    public function postRemoveList(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $list = Liste::where('token','=',$secureToken)->first();
        $list->removeList();

        $this->flash->addMessage('success',"The list \"{$list->titre}\" has been removed !");

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getchangeVisibility(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $list = Liste::where('token','=',$secureToken)->first();

        $this->view->getEnvironment()->addGlobal('value',[
            'token' => $secureToken,
            'title' => $list->titre,
            'descr' => $list->description,
            'expiration' => $list->expiration
        ]);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editListLink($secureToken));

        return $this->view->render($response, 'edit/changeVisibility.twig');
    }

    public function postchangeVisibility(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $list = Liste::where('token','=',$secureToken)->first();
        if($list->visibilite == 0){
            $list->visibilite = 1;
        }else{
            $list->visibilite = 0;
        }
        $list->save();

        $this->flash->addMessage('success',"The list \"{$list->titre}\" has been modified !");

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getShareList(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);

        $this->view->getEnvironment()->addGlobal('value',[
            'token' => $secureToken,
        ]);



        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editListLink($secureToken));

        return $this->view->render($response, 'edit/shareList.twig');
    }



    function kodex_random_string($length=20){
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for($i=0; $i<$length; $i++){
            $string .= $chars[rand(0, strlen($chars)-1)];
        }
        return $string;
    }

}