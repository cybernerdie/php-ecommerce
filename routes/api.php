<?php

use App\Core\Route;

Route::get('/', 'HomeController@index');

// Authentication
Route::post('/api/auth/login', 'AuthController@login');
Route::post('/api/auth/register', 'AuthController@register');
Route::post('/api/auth/logout', 'AuthController@logout')->middleware('auth');

// Product
Route::get('/api/products', 'ProductController@index');

// Cart
Route::get('/api/cart', 'CartController@getCartItems')->middleware('auth');
Route::post('/api/cart', 'CartController@storeCartItem')->middleware('auth');
Route::put('/api/cart/{id}', 'CartController@updateCartItem')->middleware('auth');
Route::delete('/api/cart', 'CartController@clearCart')->middleware('auth');
Route::delete('/api/cart/{id}', 'CartController@removeCartItem')->middleware('auth');

// Checkout
Route::post('/api/checkout', 'OrderController@checkout')->middleware('auth');
Route::get('/api/orders', 'OrderController@getOrders')->middleware('auth');