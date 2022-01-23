<?php

use wish\Middleware\AuthMiddleware;
use wish\Middleware\EditMiddleware;
use wish\Middleware\GuestMiddleware;
use wish\Middleware\ItemMiddleware;
use wish\Middleware\ReservationMiddleware;

/**
 * Home
 */
$app->get('/', \wish\Controllers\HomeController::class . ':index')->setName('home');
$app->post('/', \wish\Controllers\HomeController::class . ':getTokenPage');


/**
 * Actions reserved for the user not connected
 */
$app->group('',function (){

    $this->get('/auth/signup', \wish\Controllers\Auth\AuthController::class . ':getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', \wish\Controllers\Auth\AuthController::class . ':postSignUp');

    $this->get('/auth/signin', \wish\Controllers\Auth\AuthController::class . ':getSignUp')->setName('auth.signin');
    $this->post('/auth/signin', \wish\Controllers\Auth\AuthController::class . ':postSignIn');

})->add(new GuestMiddleware($container));

/**
 * Actions reserved for the connected user
 */
$app->group('',function (){

    $this->get('/auth/signout', \wish\Controllers\Auth\AuthController::class . ':getSignOut')->setName('auth.signout');

    $this->get('/auth/password/change', \wish\Controllers\Auth\PasswordController::class . ':getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', \wish\Controllers\Auth\PasswordController::class . ':postChangePassword');

    $this->get('/auth/account/delete', \wish\Controllers\Auth\DeleteAccountController::class . ':getDeleteAccount')->setName('auth.account.delete');
    $this->post('/auth/account/delete', \wish\Controllers\Auth\DeleteAccountController::class . ':postDeleteAccount');

    $this->get('/edit/addList',\wish\Controllers\Edit\EditListController::class . ':getAddList')->setName('edit.addlist') ;
    $this->post('/edit/addList',\wish\Controllers\Edit\EditListController::class . ':postAddList');

})->add(new AuthMiddleware($container));

/**
 *
 */
$app->group('/liste/{secureToken}/item/{id}/',function ()use ($container){

    $this->get('',\wish\Controllers\ItemController::class . ':index')->setName('item') ;

    $this->group('',function (){
        $this->get('reservation',\wish\Controllers\Reservation\ReservationController::class . ':getReservation')->setName('item.reservation') ;
        $this->post('reservation',\wish\Controllers\Reservation\ReservationController::class . ':postReservation');

    })->add(new ReservationMiddleware($container));

})->add(new ItemMiddleware($container));

/**
 * Actions reserved for the list creator
 */
$app->group('/liste/{secureToken}/',function (){

    $this->get('edit/editList',\wish\Controllers\Edit\EditListController::class . ':getEditList')->setName('edit.editlist') ;
    $this->post('edit/editList',\wish\Controllers\Edit\EditListController::class . ':postEditList');

    $this->get('edit/removeList',\wish\Controllers\Edit\EditListController::class . ':getRemoveList')->setName('edit.removelist') ;
    $this->post('edit/removeList',\wish\Controllers\Edit\EditListController::class . ':postRemoveList');

    $this->get('edit/addItem',\wish\Controllers\Edit\EditItemController::class . ':getAddItem')->setName('edit.additem') ;
    $this->post('edit/addItem',\wish\Controllers\Edit\EditItemController::class . ':postAddItem');

    $this->get('item/{id}/edit/editItem',\wish\Controllers\Edit\EditItemController::class . ':getEditItem')->setName('edit.edititem') ;
    $this->post('item/{id}/edit/editItem',\wish\Controllers\Edit\EditItemController::class . ':postEditItem');

    $this->get('item/{id}/edit/removeItem',\wish\Controllers\Edit\EditItemController::class . ':getRemoveItem')->setName('edit.removeitem') ;
    $this->post('item/{id}/edit/removeItem',\wish\Controllers\Edit\EditItemController::class . ':postRemoveItem');

    $this->get('edit/changeVisibility',\wish\Controllers\Edit\EditListController::class . ':getchangeVisibility')->setName('edit.changeVisibility') ;
    $this->post('edit/changeVisibility',\wish\Controllers\Edit\EditListController::class . ':postchangeVisibility');

    $this->get('edit/share',\wish\Controllers\Edit\EditListController::class . ':getShareList')->setName('edit.share') ;

})->add(new EditMiddleware($container));

/**
 * Access to the lists
 */
$app->get('/liste/{secureToken}[/]',\wish\Controllers\ListeController::class . ':index')->setName('liste') ;
