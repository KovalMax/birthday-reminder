<?php

declare(strict_types=1);

use BirthdayReminder\Person\Controller\AddPersonAction;
use BirthdayReminder\Person\Controller\GetPersonsAction;
use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/', fn () => $router->app->version());

// Persons routes
$router->group(
    ['prefix' => 'api/persons'],
    function () use ($router) {
        $router->post('', AddPersonAction::class);
        $router->get('', GetPersonsAction::class);
    }
);
