<?php

use Respect\Validation\Factory;
use Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);

$container = $app->getContainer();

\wish\DbConfig\Eloquent::start(__DIR__ . '/../app/DbConfig/conf.ini');

$container['auth'] = function ($container) {
    return new wish\Auth\Auth;
};

$container['reservationCheck'] = function ($container) {
    return new wish\Reservation\ReservationCheck;
};

$container['dateFunction'] = function ($container) {
    return new wish\Date\Date;
};

$container['view'] = function ($container){

    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);



    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth',[
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);


    $view->getEnvironment()->addGlobal('flash',$container->flash);

    return $view;
};

$container['validator'] = function ($container) {

    return new \wish\Validation\Validator();
};

$container['flash'] = function ($container) {

    return new \Slim\Flash\Messages();
};

$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

$container['AuthController'] = function ($container) {

    return new wish\Controllers\Auth\AuthController($container);
};

$container['ListeView'] = function ($container) {

    return new wish\ViewList\ListeView($container);
};

$container['ItemView'] = function ($container) {

    return new wish\ViewList\ItemView($container);
};

$container['LinkView'] = function ($container) {

    return new wish\ViewList\LinkView($container);
};


$app->add(new \wish\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \wish\Middleware\OldInputMiddleware($container));
$app->add(new \wish\Middleware\CsrfViewMiddleware($container));


$app->add($container->csrf);

Factory::setDefaultInstance(
    (new Factory())
        ->withRuleNamespace('wish\\Validation\\Rules')
        ->withExceptionNamespace('wish\\Validation\\Exceptions')
);

require __DIR__ . '/../app/routes.php';
