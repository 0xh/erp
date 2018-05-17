<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
//    $router->get('import/jrw','WebcrawlerController@jrwLogin')->name('import.jrw');
    $router->get('import/jd','WebcrawlerController@jdSkuImport')->name('import.jd');
    $router->any('import/sku2jd','WebcrawlerController@sku2JD')->name('import.sku2jd');
    $router->any('import/jd2task','WebcrawlerController@jdSku2Task')->name('import.jd2task');

});

