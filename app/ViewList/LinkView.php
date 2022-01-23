<?php

namespace wish\ViewList;

use Slim\Container;

class LinkView extends View
{


    public function homeLink(){
        $content="
        <li>
            <a href=\"{$this->container->router->pathFor('edit.addlist')}\">Create a list</a>
        </li>
        ";

        return $content;
    }

    public function editListLink($secureToken){
        $content="
        <li>
            <a href=\"{$this->container->router->pathFor('liste',['secureToken' => $secureToken])}\">Back to the list</a>
        </li>
        ";

        return $content;
    }

    public function editItemLink($secureToken,$id){
        $content="
        <li>
            <a href=\"{$this->container->router->pathFor('item',['secureToken' => $secureToken,'id' => $id])}\">Back to the item</a>
        </li>
        ";

        return $content;
    }

    public function listeLink($secureToken){
        $content="
        <li>
            <a href=\"{$this->container->router->pathFor('edit.editlist',['secureToken' => $secureToken])}\">Edit the list</a>
        </li>
        <li>
            <a href=\"{$this->container->router->pathFor('edit.additem',['secureToken' => $secureToken])}\">Add items</a>
        </li>
        <li>
            <a href=\"{$this->container->router->pathFor('edit.share',['secureToken' => $secureToken])}\">Share the list</a>
        </li>
        <li>
            <a href=\"{$this->container->router->pathFor('edit.removelist',['secureToken' => $secureToken])}\">Delete the list</a>
        </li>
        <li>
            <a href=\"{$this->container->router->pathFor('edit.changeVisibility',['secureToken' => $secureToken])}\">Change visibility</a>
        </li>
        ";

        return $content;
    }

    public function itemLink($secureToken,$id){
        $content="
        <li>
            <a href=\"{$this->container->router->pathFor('liste',['secureToken' => $secureToken])}\">Back to the list</a>
        </li>
        <li>
            <a href=\"{$this->container->router->pathFor('edit.edititem',['secureToken' => $secureToken,'id' => $id])}\">Edit the item</a>
        </li>
        <li>
            <a href=\"{$this->container->router->pathFor('edit.removeitem',['secureToken' => $secureToken,'id' => $id])}\">Remove the item from the list</a>
        </li>
        ";

        return $content;
    }

}