<?php
$router->group(['prefix' => 'cms'], function () use ($router) {
    $router->get('/product', 'CMS\ProductController@index');
    $router->get('/product/{id}', 'CMS\ProductController@show');
    $router->post('/product', 'CMS\ProductController@store');
    $router->put('/product/{id}', 'CMS\ProductController@update');
    $router->delete('/product/{id}', 'CMS\ProductController@destroy');

    $router->get('/category', 'CMS\CategoryController@index');
    $router->get('/category/{id}', 'CMS\CategoryController@show');
    $router->post('/category', 'CMS\CategoryController@store');
    $router->put('/category/{id}', 'CMS\CategoryController@update');
    $router->delete('/category/{id}', 'CMS\CategoryController@destroy');
});