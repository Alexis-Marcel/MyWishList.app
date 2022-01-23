<?php

namespace wish\Controllers\Edit;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;
use wish\Controllers\Controller;
use wish\models\Item;
use wish\models\Liste;

class EditItemController extends Controller
{
    public function getAddItem(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);

        $this->view->getEnvironment()->addGlobal('value',[
            'token' => $secureToken,
        ]);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editListLink($secureToken));

        return $this->view->render($response, 'edit/addItem.twig');
    }

    public function postAddItem(Request $request, Response $response, $parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);

        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'descr' => v::notEmpty(),
            'image' => v::noWhitespace()->notEmpty(),
            'url' => v::optional(v::url()),
            'price' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()){

            $this->flash->addMessage('error','Could not create the item with those details.');
            //redirect back
            return $response->withRedirect($this->router->pathFor('edit.additem',['secureToken' => $secureToken]));
        }

        $item = Item::create([
            'liste_id' => Liste::where('token','=',$secureToken)->first()->no,
            'nom' => filter_var($request->getParam('name'), FILTER_SANITIZE_STRING),
            'descr' => filter_var($request->getParam('descr'), FILTER_SANITIZE_STRING),
            'img' => filter_var($request->getParam('image'), FILTER_SANITIZE_STRING),
            'url' => filter_var($request->getParam('url'), FILTER_SANITIZE_STRING),
            'tarif' => filter_var($request->getParam('price'), FILTER_SANITIZE_STRING),

        ]);

        $name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING);

        $this->flash->addMessage('success',"The item \"{$name}\" has been created !");


        return $response->withRedirect($this->router->pathFor('liste',['secureToken' => $secureToken]));

    }

    public function getEditItem(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $id = filter_var($parameters['id'], FILTER_SANITIZE_STRING);

        if($this->container->reservationCheck->checkReservation($id)){
            $this->container->flash->addMessage('error','A reserved item cannot be modified');
            return $response->withRedirect($this->container->router->pathFor('item',['secureToken' => $secureToken,'id' => $id]));

        }

        $item = Item::find($id);

        $this->view->getEnvironment()->addGlobal('old_value',[
            'token' => $secureToken,
            'id' => $id,
            'name' => $item->nom,
            'descr' => $item->descr,
            'image' => $item->img,
            'url' => $item->url,
            'price' => $item->tarif
        ]);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editItemLink($secureToken,$id));


        return $this->view->render($response, 'edit/editItem.twig');
    }

    public function postEditItem(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $id = filter_var($parameters['id'], FILTER_SANITIZE_STRING);
        $item = Item::find($id);

        $validation = $this->validator->validate($request, [
            'name' => v::notEmpty(),
            'descr' => v::notEmpty(),
            'image' => v::noWhitespace()->notEmpty(),
            'url' => v::optional(v::url()),
            'price' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()){

            $this->flash->addMessage('error','Could not edit the item with those details.');
            //redirect back
            return $response->withRedirect($this->router->pathFor('edit.edititem',['id' =>$id,'secureToken' => $secureToken]));
        }

        $item->update([
            'nom' => filter_var($request->getParam('name'), FILTER_SANITIZE_STRING),
            'descr' => filter_var($request->getParam('descr'), FILTER_SANITIZE_STRING),
            'img' => filter_var($request->getParam('image'), FILTER_SANITIZE_STRING),
            'url' => filter_var($request->getParam('url'), FILTER_SANITIZE_STRING),
            'tarif' => filter_var($request->getParam('price'), FILTER_SANITIZE_STRING),
        ]);

        $name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING);

        $this->flash->addMessage('success',"The item \"{$name}\" has been modified !");

        return $response->withRedirect($this->router->pathFor('item',['secureToken' => $secureToken,'id' => $id]));
    }

    public function getRemoveItem(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $id = filter_var($parameters['id'], FILTER_SANITIZE_STRING);
        $item = Item::find($id);

        $this->view->getEnvironment()->addGlobal('value',[
            'token' => $secureToken,
            'id' => $id,
            'name' => $item->nom,
            'descr' => $item->descr,
            'url' => $item->url,
            'price' => $item->tarif
        ]);

        $this->view->getEnvironment()->addGlobal('link',$this->LinkView->editItemLink($secureToken,$id));

        return $this->view->render($response, 'edit/removeItem.twig');
    }

    public function postRemoveItem(Request $request, Response $response,$parameters)
    {
        $secureToken = filter_var($parameters['secureToken'], FILTER_SANITIZE_STRING);
        $id = filter_var($parameters['id'], FILTER_SANITIZE_STRING);

        $item = Item::find($id);
        $item->removeItem();

        $this->flash->addMessage('success',"The item \"{$item->nom}\" has been removed !");

        return $response->withRedirect($this->router->pathFor('liste',['secureToken' => $secureToken]));

    }

}