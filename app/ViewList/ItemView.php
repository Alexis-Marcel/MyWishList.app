<?php

namespace wish\ViewList;

use wish\models\Liste;
use wish\models\Reservation;

class ItemView extends View
{
    public function getItemById($item,$secureToken) {

        $reservBtn="";
        $tableReservation = "";
                
        if($this->container->reservationCheck->checkReservation($item->id)){

            $list = Liste::where('token','=',$secureToken)->first();
            if(!$this->container->reservationCheck->checkCreator($secureToken) || $this->container->dateFunction->expired(date("Y-m-d"),$list->expiration)){
                $reservation = Reservation::where('id_item','=',$item->id)->first();

                $tableReservation ="
                <table class='table'>
                <thead>
                <tr>
                    <th scope='col'>Name</th>
                    <th scope='col'>Message</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$reservation->reservation_name}</td>
                        <td>{$reservation->reservation_message}</td>
                    </tr>
                </tbody>
            </table>
            ";
            }

            $reservBtn = "

            <li class='list-group-item'>
                This item is reserved
            </li>
            ";
        }
        else {
            $url = $this->container->router->pathFor('item.reservation',['secureToken' => $secureToken,'id' => $item->id]);
            $reservBtn = "

            <li class='list-group-item'>
                <a href='{$url}' id='add-btn' type='button' class='btn mt-1 align-items-end'>Reserve this item</a>
            </li>
            ";
        }

        $url = "";
        if($item->url != null){
            $url = "<li class='list-group-item'><a  id='url-link' href='{$item->url}'>{$item->url}</a></li>";
        }

        $content="
            <div class='row justify-content-center'>
                <div class='card p-2' style='width: 18rem;'>
                    <img src='/img/{$item->img}' class='card-img-top' alt='{$item->nom} picture'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$item->nom}</h5>
                        <p class='card-text'>{$item->descr}</p>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item'>{$item->tarif}€</li>
                        {$url}
                        {$reservBtn}
                    </ul>
                </div>
            </div>
            {$tableReservation}
            ";

        return $content;
    }

    public function getItemByListe($listeItems,$secureToken) {

        $content ="";
        $count=1;
        foreach ($listeItems as $it){

            $url = $this->container->router->pathFor( 'item',['id'=> $it->id, 'secureToken' => $secureToken] ) ;

            $content.=" 
                <tr onclick=\"window.location='{$url}'\">
                        <th scope='row'>{$count}</th>
                        <td>{$it->nom}</td>
                        <td>{$it->tarif}€</td>
                </tr>
            ";
            $count++;
        }

        return $content;
    }

}