<?php


namespace wish\models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'id_item';
    public $timestamps = false;

    protected $fillable = [
        'id_item',
        'reservation_name',
        'reservation_message',
    ];

    public function item(){
        return $this->belongsTo('\wish\models\Item','id_item');
    }

    public function removeReservation(){

        $this->delete();
    }


}