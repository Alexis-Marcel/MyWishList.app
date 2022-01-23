<?php

namespace wish\models;

use Illuminate\Database\Eloquent\Model;

class Liste extends Model{
    protected $table = 'liste';
    protected $primaryKey = 'no' ;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'expiration',
        'token'
    ];

    public function recupItems() {
        return $this->hasMany('\wish\models\Item','liste_id') ;
    }

    public function getUser(){
        return $this->belongsTo('\wish\models\User','user_id');
    }

    public function removeList(){

        $this->delete();

        $listItems = $this->recupItems;
        foreach ($listItems as $it){
            $it->removeItem();
        }

    }
}

