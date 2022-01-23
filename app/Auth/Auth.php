<?php

namespace wish\Auth;

use wish\Models\User;

class Auth
{
    public function user()
    {
        if(isset($_SESSION['user']))
        return User::find($_SESSION['user']);
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt($name,$password){

        $user = User::where('name', $name)->first();

        if(!$user) {
            return false;
        }

       if(password_verify($password, $user->password)) {
           $_SESSION['user'] = $user->id;
           return true;
       }
    }

    public function deleteAccount($password){
        $user = User::find($_SESSION['user']);

        if(password_verify($password, $user->password)) {
            $user->removeAccount();
            $this->logout();
            return true;
        }

    }

    public function logout(){
        unset($_SESSION['user']);
    }
}