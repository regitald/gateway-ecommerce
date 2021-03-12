<?php


    //== start routes auth===============================
    $router->group(['prefix' => 'api/user'], function () use ($router) {
        $router->post('/login', 'API\UserController@login');
        $router->get('/logout', 'API\UserController@logout');
        $router->post('/register', 'API\UserController@register');
    });
    //== end routes auth===============================

    $router->group(['prefix' => 'api'], function () use ($router) {
        //== start routes product===============================
        $router->get('/product', 'API\ProductController@index');
        $router->get('/product/{id}', 'API\ProductController@show');
        $router->get('/category', 'API\ProductController@category');
        $router->get('/category/{id}', 'API\ProductController@categoryShow');

        //== end routes product===============================
    $router->group(['middleware' => ['jwt.auth']], function() use ($router) {
        //== start routes order===============================
        $router->get('/shipping', 'API\GeneralController@shipping');
        $router->get('/payment', 'API\GeneralController@payment');

        $router->get('/cart', 'API\OrderController@index');
        $router->post('/cart', 'API\OrderController@store');
        $router->post('/cart/update', 'API\OrderController@updateQty');
        $router->delete('/cart/delete', 'API\OrderController@delete');
        $router->post('/checkout', 'API\OrderController@checkout');
        //== end routes order===============================
    });
});



