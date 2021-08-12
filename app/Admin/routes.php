<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('products', ProductsController::class);
    $router->resource('categorylarge', Category\CategoryLargeController::class);
    $router->resource('categorymiddle', Category\CategoryMiddleController::class);
    $router->resource('categorysmall', Category\CategorySmallController::class);
    $router->resource('quality-control', QualityController::class);
    $router->resource('vendors', VendorController::class);
    $router->resource('process', ProcessController::class);
    $router->resource('purchases', PurchaseController::class);
    $router->resource('ingredients', IngredientsController::class);
    $router->resource('government', GovController::class);
    $router->resource('brand', BrandController::class);
    $router->resource('manufacturers', ManufacturerController::class);
    
    $router->get('api/catMid', 'ProductsController@categoryMiddle');
    $router->get('api/catSmall', 'ProductsController@categorySmall');
    $router->get('import', 'Category\CategoryMiddleController@import');
    Route::get('users/export/', 'ProductsController@export');
    //$router->get('api/users', 'ProductsController@users');
    
});
