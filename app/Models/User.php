<?php

namespace wish\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email',
        'name',
        'password'
    ];

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function getListes() {
        return $this->hasMany('\wish\Models\Liste','user_id') ;
    }

    public function removeAccount(){

        $this->delete();

        $lists = $this->getListes;
        foreach ($lists as $l){
            $l->removeList();
        }

    }

}