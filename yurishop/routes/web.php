<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Added by Thang LD */
Route::get('/', 'Front\PagesController@home');
Route::get('/test', 'Admin\OrdersController@test');
Route::post('/thong-tin-san-pham', 'Front\PagesController@product_info');
Route::post('/get-prop', 'Front\PagesController@get_prop');
/*  --- */

Route::get('/', ['as' => 'front.home',   'uses' => 'Front\PagesController@home']);

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'auth'], function() {
    //admin area
    Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'PagesController@getDashboard','middleware' => ['role:admin|manager']]);
    Route::get('/blank', ['as' => 'admin.blank', 'uses' => 'PagesController@getBlank']);

    //role area
    Route::get('roles',['as'=>'admin.roles.index','uses'=>'RolesController@index','middleware' => ['permission:role-list']]);
    Route::get('roles/create',['as'=>'admin.roles.create','uses'=>'RolesController@create','middleware' => ['permission:role-create']]);
    Route::post('roles/create',['as'=>'admin.roles.store','uses'=>'RolesController@store','middleware' => ['permission:role-create']]);
    Route::get('roles/{id}',['as'=>'admin.roles.show','uses'=>'RolesController@show', 'middleware'=> ['permission:role-show']]);
    Route::get('roles/{id}/edit',['as'=>'admin.roles.edit','uses'=>'RolesController@edit','middleware' => ['permission:role-edit']]);
    Route::patch('roles/{id}',['as'=>'admin.roles.update','uses'=>'RolesController@update','middleware' => ['permission:role-edit']]);
    Route::delete('roles/{id}',['as'=>'admin.roles.destroy','uses'=>'RolesController@destroy','middleware' => ['permission:role-delete']]);

    //user area
    Route::get('users',['as'=>'admin.users.index','uses'=>'UsersController@index','middleware' => ['role:admin|manager']]);
    Route::get('users/create',['as'=>'admin.users.create','uses'=>'UsersController@create','middleware' => ['role:admin|manager']]);
    Route::post('users/create',['as'=>'admin.users.store','uses'=>'UsersController@store','middleware' => ['role:admin|manager']]);
    Route::get('users/{id}',['as'=>'admin.users.show','uses'=>'UsersController@show', 'middleware'=> ['role:admin|manager']]);
    Route::get('users/{id}/edit',['as'=>'admin.users.edit','uses'=>'UsersController@edit','middleware' => ['role:admin|manager']]);
    Route::patch('users/{id}',['as'=>'admin.users.update','uses'=>'UsersController@update','middleware' => ['role:admin|manager']]);
    Route::delete('users/{id}',['as'=>'admin.users.destroy','uses'=>'UsersController@destroy','middleware' => ['role:admin']]);

     //rate area
    Route::get('rates',['as'=>'admin.rates.index','uses'=>'RatesController@index','middleware' => ['role:admin|manager']]);
    Route::get('rates/create',['as'=>'admin.rates.create','uses'=>'RatesController@create','middleware' => ['role:admin|manager']]);
    Route::post('rates/create',['as'=>'admin.rates.store','uses'=>'RatesController@store','middleware' => ['role:admin|manager']]);
    Route::get('rates/{id}',['as'=>'admin.rates.show','uses'=>'RatesController@show', 'middleware'=> ['role:admin|manager']]);
    Route::get('rates/{id}/edit',['as'=>'admin.rates.edit','uses'=>'RatesController@edit','middleware' => ['role:admin|manager']]);
    Route::patch('rates/{id}',['as'=>'admin.rates.update','uses'=>'RatesController@update','middleware' => ['role:admin|manager']]);
    Route::delete('rates/{id}',['as'=>'admin.rates.destroy','uses'=>'RatesController@destroy','middleware' => ['role:admin|manager']]);

    //Orders
    Route::get('orders',['as'=>'admin.orders.index','uses'=>'OrdersController@index','middleware' => ['role:admin|manager']]);
    Route::get('orders/create',['as'=>'admin.orders.create','uses'=>'OrdersController@create','middleware' => ['permission:order-create']]);
    Route::post('orders/create',['as'=>'admin.orders.store','uses'=>'OrdersController@store','middleware' => ['role:admin|manager']]);
    Route::get('orders/{id}',['as'=>'admin.orders.show','uses'=>'OrdersController@show', 'middleware' => ['role:admin|manager']]);
    Route::get('orders/{id}/edit',['as'=>'admin.orders.edit','uses'=>'OrdersController@edit','middleware' => ['role:admin|manager']]);
    Route::patch('orders/{id}',['as'=>'admin.orders.update','uses'=>'OrdersController@update','middleware' => ['role:admin|manager']]);
    Route::delete('orders/{id}',['as'=>'admin.orders.destroy','uses'=>'OrdersController@destroy','middleware' => ['role:admin|manager']]);
    Route::delete('orders/delete/{id}',['as'=>'admin.orders.delete','uses'=>'OrdersController@delete','middleware' => ['role:admin|manager']]);
    Route::post('orders',['as'=>'admin.orders.find','uses'=>'OrdersController@find','middleware' => ['role:admin|manager']]);
    Route::get('orders/export/{order_id}',['as'=>'admin.orders.export','uses'=>'OrdersController@export','middleware' => ['role:admin|manager']]);
    
    //Order-Sub
    Route::patch('orders/ajust/quantity/item/{id}/{orderid}/{quantity}',['as'=>'admin.orders.ajustquantity','uses'=>'OrdersController@ajustquantity','middleware' => ['role:admin|manager']]);
    Route::patch('orders/send/ordershop/{id}',['as'=>'admin.orders.sendshop','uses'=>'OrdersController@sendshop','middleware' => ['role:admin|manager']]);
    Route::patch('orders/item/destroy/{id}',['as'=>'admin.orders.itemdestroy','uses'=>'OrdersController@itemdestroy','middleware' => ['role:admin|manager']]);
    Route::patch('orders/{id}/{orderdetail_id}/{unitprice}/item/ajust/unitprice',['as'=>'admin.orders.ajustunitprice','uses'=>'OrdersController@ajustunitprice','middleware' => ['role:admin|manager']]);
    Route::patch('orders/{id}/{orderdetail_id}/{quantity}/item/adjust/quantity',['as'=>'admin.orders.adjust_quantity','uses'=>'OrdersController@adjust_quantity','middleware' => ['role:admin|manager']]);
    Route::post('orders/{id}/{orderdetail_id}/adjust_size',['as'=>'admin.orders.adjust_size','uses'=>'OrdersController@adjust_size','middleware' => ['role:admin|manager']]);
    Route::patch('orders/{id}/{orderdetail_id}/{color}/item/adjust/color',['as'=>'admin.orders.adjust_color','uses'=>'OrdersController@adjust_color','middleware' => ['role:admin|manager']]);
    Route::patch('orders/item/{id}/{freight1detail_id}/{unitprice}/ajust/freight1/sub',['as'=>'admin.orders.ajustfreightsub','uses'=>'OrdersController@ajustfreightsub','middleware' => ['role:admin|manager']]);
    Route::post('orders/{id}/adjust_landingcode',['as'=>'admin.orders.adjust_landingcode','uses'=>'OrdersController@adjust_landingcode','middleware' => ['role:admin|manager']]);
    Route::patch('orders/shop/{id}/{name}/ajust/name',['as'=>'admin.orders.ajustShopName','uses'=>'OrdersController@ajustshopname','middleware' => ['role:admin|manager']]);
    Route::post('orders/product-info',['as'=>'admin.orders.product-info','uses'=>'OrdersController@productInfo','middleware' => ['role:admin|manager']]);
    Route::patch('orders/{id}/add-product',['as'=>'admin.orders.add-product','uses'=>'OrdersController@addProduct','middleware' => ['role:admin|manager']]);
    //Freight1Detail
    Route::patch('orders/ajust/available/{id}',['as'=>'admin.ordershops.setAvailable','uses'=>'OrdersController@setAvailable','middleware' => ['role:admin|manager']]);
    Route::get('orders/shop/route',['as'=>'admin.orders.route','uses'=>'OrdersController@route','middleware' => ['role:admin|manager']]);
    Route::post('orders/shop/route',['as'=>'admin.orders.set-route','uses'=>'OrdersController@setRoute','middleware' => ['role:admin|manager']]);

    //OrderShops
    Route::get('ordershops',['as'=>'admin.ordershops.index','uses'=>'OrderShopsController@index','middleware' => ['role:admin|manager']]);
    Route::get('ordershops/create',['as'=>'admin.ordershops.create','uses'=>'OrderShopsController@create','middleware' => ['role:admin|manager']]);
    Route::post('ordershops/create',['as'=>'admin.ordershops.store','uses'=>'OrderShopsController@store','middleware' => ['role:admin|manager']]);
    Route::get('ordershops/{id}',['as'=>'admin.ordershops.show','uses'=>'OrderShopsController@show', 'middleware' => ['role:admin|manager']]);
    Route::get('ordershops/{id}/edit',['as'=>'admin.ordershops.edit','uses'=>'OrderShopsController@edit','middleware' => ['role:admin|manager']]);
    Route::patch('ordershops/{id}',['as'=>'admin.ordershops.update','uses'=>'OrderShopsController@update','middleware' => ['role:admin|manager']]);
    Route::delete('ordershops/{id}',['as'=>'admin.ordershops.destroy','uses'=>'OrderShopsController@destroy','middleware' => ['role:admin|manager']]);
    Route::post('ordershops',['as'=>'admin.ordershops.find','uses'=>'OrderShopsController@find','middleware' => ['role:admin|manager']]);

    //Ordershops-Sub
    Route::patch('ordershops/ajust/available/{id}',['as'=>'admin.ordershops.setAvailable','uses'=>'OrderShopsController@setAvailable','middleware' => ['role:admin|manager']]);
    Route::patch('ordershops/ajust/status/{id}',['as'=>'admin.ordershops.setStatus','uses'=>'OrderShopsController@setStatus','middleware' => ['role:admin|manager']]);
    Route::get('ordershops/export/{shop_id}/{type}',['as'=>'admin.ordershops.export','uses'=>'OrderShopsController@export','middleware' => ['role:admin|manager']]);
    Route::patch('ordershops/shop/{id}/ajust/name',['as'=>'admin.ordershops.ajustShopName','uses'=>'OrderShopsController@ajustshopname','middleware' => ['role:admin|manager']]);

    //App Settings
    //rate area
    Route::get('appsettings/show',['as'=>'admin.appsettings.show','uses'=>'AppSettingsController@show', 'middleware'=> ['role:admin|manager']]);
    Route::get('appsettings/{id}/edit',['as'=>'admin.appsettings.edit','uses'=>'AppSettingsController@edit','middleware' => ['role:admin|manager']]);
    Route::patch('appsettings/{id}',['as'=>'admin.appsettings.update','uses'=>'AppSettingsController@update','middleware' => ['role:admin|manager']]);


});


Auth::routes();
 
// registration activation routes
Route::get('activation/key/{activation_key}', ['as' => 'activation_key', 'uses' => 'Auth\ActivationKeyController@activateKey']);
Route::get('activation/resend', ['as' =>  'activation_key_resend', 'uses' => 'Auth\ActivationKeyController@showKeyResendForm']);
Route::post('activation/resend', ['as' =>  'activation_key_resend.post', 'uses' => 'Auth\ActivationKeyController@resendKey']);

// forgot_username
Route::get('username/reminder', ['as' =>  'username_reminder', 'uses' => 'Auth\ForgotUsernameController@showForgotUsernameForm']);
Route::post('username/reminder', ['as' =>  'username_reminder.post', 'uses' => 'Auth\ForgotUsernameController@sendUserameReminder']);

// Products - old
// Route::resource('products', 'Front\ProductController', ['only' => ['index', 'show']]);

// Shoppings - old
// Route::resource('cart', 'Front\CartController');
// Route::delete('emptyCart', 'Front\CartController@emptyCart');
// Route::post('switchToWishlist/{id}', 'Front\CartController@switchToWishlist');
// Route::resource('wishlist', 'Front\WishlistController');
// Route::delete('emptyWishlist', 'Front\WishlistController@emptyWishlist');
// Route::post('switchToCart/{id}', 'Front\WishlistController@switchToCart');


//Profiles
Route::get('profile', ['as'=>'front.profiles.index','uses'=>'Front\ProfileController@index','middleware' => 'auth']);
Route::patch('profile', ['as'=>'front.profiles.update','uses'=>'Front\ProfileController@update','middleware' => 'auth']);


//Address
Route::get('profile/address',['as'=>'front.profiles.address','uses'=>'Front\ProfileController@address','middleware' => 'auth']);
Route::get('profile/address/create',['as'=>'front.profiles.createaddress','uses'=>'Front\ProfileController@createaddress','middleware' => 'auth']);
Route::post('profile/address/create',['as'=>'front.profiles.storeaddress','uses'=>'Front\ProfileController@storeaddress','middleware' => 'auth']);
Route::get('profile/address/{id}',['as'=>'front.profiles.showaddress','uses'=>'Front\ProfileController@showaddress', 'middleware'=> 'auth']);
Route::get('profile/address/{id}/edit',['as'=>'front.profiles.editaddress','uses'=>'Front\ProfileController@editaddress','middleware' => 'auth']);
Route::patch('profile/address/{id}',['as'=>'front.profiles.updateaddress','uses'=>'Front\ProfileController@updateaddress','middleware' => 'auth']);
Route::delete('profile/address/{id}',['as'=>'front.profiles.destroyaddress','uses'=>'Front\ProfileController@destroyaddress','middleware' => 'auth']);


//Orders
Route::get('order',['as'=>'front.orders.index','uses'=>'Front\OrdersController@index','middleware' => 'auth']);
Route::get('order/create',['as'=>'front.orders.create','uses'=>'Front\OrdersController@create','middleware' => 'auth']);
Route::post('order/create',['as'=>'front.orders.store','uses'=>'Front\OrdersController@store','middleware' => 'auth']);
Route::get('order/{id}',['as'=>'front.orders.show','uses'=>'Front\OrdersController@show', 'middleware'=> 'auth']);
Route::get('order/{id}/edit',['as'=>'front.orders.edit','uses'=>'Front\OrdersController@edit','middleware' => 'auth']);
Route::patch('order/{id}',['as'=>'front.orders.update','uses'=>'Front\OrdersController@update','middleware' => 'auth']);
Route::delete('order/{id}',['as'=>'front.orders.destroy','uses'=>'Front\OrdersController@destroy','middleware' => 'auth']);
Route::post('order',['as'=>'front.orders.find','uses'=>'Front\OrdersController@find','middleware' => 'auth']);

//Order-Sub
Route::delete('order/itemdestroy/{id}', ['as'=>'front.orders.itemdestroy','uses'=>'Front\OrdersController@itemdestroy','middleware' => 'auth']);
Route::patch('order/feedback/{id}',['as'=>'front.orders.feedback','uses'=>'Front\OrdersController@feedback','middleware' => 'auth']);
Route::patch('order/note/{id}',['as'=>'front.orders.note','uses'=>'Front\OrdersController@note','middleware' => 'auth']);
Route::patch('order/item/feedback/{id}',['as'=>'front.orders.itemfeedback','uses'=>'Front\OrdersController@itemfeedback','middleware' => 'auth']);
Route::patch('order/item/ajust/available/{id}',['as'=>'admin.orders.setAvailable','uses'=>'Front\OrdersController@setAvailable','middleware' => 'auth']);

//Cart
Route::get('cart',['as'=>'front.carts.index','uses'=>'Front\CartsController@index', 'middleware' => 'auth']);
Route::get('cart/create',['as'=>'front.carts.create','uses'=>'Front\CartsController@create', 'middleware' => 'auth']);
Route::post('cart/create',['as'=>'front.carts.store','uses'=>'Front\CartsController@store', 'middleware' => 'auth']);
Route::get('cart/{id}',['as'=>'front.carts.show','uses'=>'Front\CartsController@show', 'middleware' => 'auth']);
Route::get('cart/{id}/edit',['as'=>'front.carts.edit','uses'=>'Front\CartsController@edit', 'middleware' => 'auth']);
Route::patch('cart/{id}/{qty}',['as'=>'front.carts.update','uses'=>'Front\CartsController@update', 'middleware' => 'auth']);
Route::delete('cart/{id}',['as'=>'front.carts.destroy','uses'=>'Front\CartsController@destroy', 'middleware' => 'auth']);

//Cart-Sub
Route::delete('emptyCart', 'Front\CartsController@emptyCart');
