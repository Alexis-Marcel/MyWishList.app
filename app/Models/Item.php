<?php


namespace wish\models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'liste_id',
        'nom',
        'descr',
        'img',
        'url',
        'tarif'
    ];

    public function liste(){
        return $this->belongsTo('\wish\models\Liste','liste_id');
    }

    public function recupReservation() {
        return $this->hasMany('\wish\models\Reservation','id_item') ;
    }

    public function removeItem(){

        $this->delete();
        $reservation = $this->recupReservation->first();
        if($reservation != null)
        $reservation->removeReservation();
    }
}