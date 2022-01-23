<?php

namespace wish\Reservation;

use wish\Models\Reservation;

class ReservationCheck
{
    public function checkCreator($secureToken)
    {
        if(isset($_COOKIE ['wishLists'])){
            $wishlists = unserialize($_COOKIE['wishLists'], ["allowed_classes" => false]);

            if (in_array($secureToken, $wishlists)) {
                return true;
            }
        }

        return false;

    }

    public function checkReservation($itemId)
    {
        return Reservation::where('id_item', $itemId)->count() !== 0;
    }

}