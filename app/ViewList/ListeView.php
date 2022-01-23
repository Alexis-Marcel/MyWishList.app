<?php

namespace wish\ViewList;

class ListeView extends View
{

    public function getListeByNo($liste) {
        $info ="";
        if($this->container->dateFunction->expired(date("Y-m-d"),$liste->expiration)){
            $info = "(expired)";
        }
        $content = "<h2 class='m-3'>{$liste->titre} {$info}</h2>";
        $content .= "<p>{$liste->description}</p>";
        $content .= "<p>End date of the list : {$liste->expiration}</p>";
        if($this->container->dateFunction->expired(date("Y-m-d"),$liste->expiration)){
            $content .= "<p>The deadline for the list has been reached.</p>";
        }
        else {
            $content .= "<p>Days left : {$this->container->dateFunction->dayLeft(date("Y-m-d"),$liste->expiration)}</p>";
        }
        if($liste->visibilite == 0){
            $content .= "<p>Visibility : public</p>";
        }else {
            $content .= "<p>Visibility : private</p>";
        }
        return $content;

    }

    public function getListes($listes){
        $content = "";

        $count="1";
        foreach ($listes as $li){

            $url = $this->container->router->pathFor( 'liste', ['secureToken'=> $li->token] ) ;
            $content.=" 
                <tr onclick=\"window.location='{$url}'\">
                        <th scope='row'>{$count}</th>
                        <td>{$li->titre}</td>
                        <td>{$li->expiration}</td>
                </tr>
            ";
            $count++;
        }

        return $content;
    }
}