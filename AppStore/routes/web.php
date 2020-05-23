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

//ADMIN
Route::post('admin/login', 'UserController@loginAdmin')->name('admin.login');
Route::view('admin/login','admin.pages.login')->name('login.admin');
Route::get('getproducttype' , 'AjaxController@getProductType');

Route::group(['prefix' => 'admin' , 'middleware' => 'adminMiddleware'] , function() {
    //.../admin/...
    Route::view('/','admin.pages.index');
    Route::resource('category','CategoryController');
    Route::resource('producttype','ProductTypeController');
    Route::resource('product','ProductController');
    Route::post('updatePro/{id}','ProductController@update');
    Route::resource('order','OrderController');
    Route::resource('orderdetail','OrderDetailController');
});

//CLIENT
Route::get('callback/{social}', 'UserController@handleProviderCallback');
Route::get('login/{social}', 'UserController@redirectProvider')->name('login.social');
Route::get('logout','UserController@logout');
Route::post('register','UserController@registerClient')->name('register');
Route::post('updatepass','UserController@updatePassClient');
Route::post('login','UserController@loginClient');

Route::get('/', 'HomeController@index');
Route::get('trangchu', 'HomeController@index');
Route::resource('cart','CartController');
Route::get('addcart/{id}','CartController@addCart') ->name('addCart');
Route::get('checkout','CartController@checkout')->name('cart.ckeckout');

Route::resource('customer','CustomerController');
Route::get('{slug}.html', 'HomeController@getDetail');
