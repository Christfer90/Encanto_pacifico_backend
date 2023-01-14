<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'usuario'], function () use ($router)
{
    $router->get('consultar', 'UsuarioController@index');
    $router->post('registrar', 'UsuarioController@registrar');
    $router->put('actualizar', 'UsuarioController@actualizar');
    $router->delete('eliminar', 'UsuarioController@eliminar');
    $router->post('login', 'UsuarioController@login');
    $router->get('logout', 'UsuarioController@logout');

});

$router->group(['prefix' => 'admin'], function () use ($router)
{
    $router->get('consultar', 'AdminController@index');
    $router->post('registrar', 'AdminController@registrar');
    $router->put('actualizar', 'AdminController@actualizar');
    $router->delete('eliminar', 'AdminController@eliminar');
    $router->post('login', 'AdminController@login');
    $router->get('logout', 'AdminController@logout');
});

$router->group(['prefix'=>'encanto-pacifico'], function () use ($router) 
{
    $router->get('/categorias', 'CategoriasController@index');
    $router->post('/categorias/crear', 'CategoriasController@crear');
    $router->put('/categorias/actualizar/{id}', 'CategoriasController@actualizar');
    $router->delete('/categorias/eliminar/{id}', 'CategoriasController@eliminiar');
    $router->get('/productos', 'ProductosController@index');
    $router->post('/productos/crear', 'ProductosController@crear');
    $router->put('/productos/actualizar/{id}', 'ProductosController@actualizar');
    $router->delete('/productos/eliminar/{id}', 'ProductosController@eliminar');
});
