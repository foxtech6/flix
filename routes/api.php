<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('trips', 'TripController@index');

    $router->post('reserves', 'ReserveController@store');
    $router->get('reserves/{email}', 'ReserveController@index');
    $router->put('reserves/{id}/places', 'ReserveController@updatePlaces');
    $router->delete('reserves/{id}', 'ReserveController@delete');

    $router->get('admin/trip/{id}/reserves', 'Admin\ReserveController@index');
});
