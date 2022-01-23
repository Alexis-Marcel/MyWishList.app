<?php

namespace wish\Date;

class Date
{

    function expired($debut,$fin){

        $tDeb = explode("-", $debut);
        $tFin = explode("-", $fin);

        $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
            mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

        if($diff<0){
            return true;
        }

        return false;
    }

    function dayLeft($debut, $fin) {

        $tDeb = explode("-", $debut);
        $tFin = explode("-", $fin);

        $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
            mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

        return(($diff / 86400)+1);

    }

}