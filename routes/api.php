<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/////////////////// Super Admin Route Start//////////////////////////////
Route::post('login',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\SigninController@signin_doverify"));

Route::post('add-brands',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_brands"));
Route::post('list-brands',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_brands"));
Route::post('edit-brands',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_brands"));
Route::post('delete-brands',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_brands"));

Route::post('add-cat1',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@add_cat1"));
Route::post('list-cat1',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_cat1"));
Route::post('delete-cat1',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_cat1"));
Route::post('edit-cat1',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_cat1"));



Route::post('add-cat2',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@add_cat2"));
Route::post('list-cat2',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_cat2"));
Route::post('delete-cat2',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_cat2"));
Route::post('edit-cat2',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_cat2"));

 


Route::post('add-cat3',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@add_cat3"));
Route::post('list-cat3',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_cat3"));
Route::post('delete-cat3',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_cat3"));
Route::post('edit-cat3',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_cat3"));





Route::post('add-product',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_product"));
Route::post('list-product',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_product"));
Route::post('edit-product',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\ProductController@edit_product"));
Route::post('delete-product',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\ProductController@delete_product"));



//Route::post('add-variables-value',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@add_variables_values"));
//Route::post('list-variables-value',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@list_variables_values"));
//Route::post('delete-variables-value',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_variables_values"));
//Route::post('edit-variables-value',array('middleware' => 'jwt.verify', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_variables_values"));

Route::post('add-document',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_document"));
Route::post('token-verification',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\SigninController@token_verify"));

Route::post('add-variation',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_variation"));
Route::post('list-variation',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_variation"));
Route::post('delete-variation',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_variation"));
Route::post('edit-variation',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_variation"));



Route::post('add-variation-value',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_variation_values"));
Route::post('list-variation-value',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_variation_values"));
Route::post('delete-variation-value',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_variation_values"));
Route::post('edit-variation-value',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_variation_values"));




Route::post('add-variation-value-map',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_variation_values_map"));
Route::post('list-variation-value-map',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_variation_values_map"));
Route::post('delete-variation-value-map',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_variation_values_map"));
Route::post('edit-variation-value-map',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_variation_values_map"));




Route::post('add-coupon',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_coupon"));
Route::post('list-coupon',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_coupon"));
Route::post('edit-coupon',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_coupon"));
Route::post('deactivate-coupon',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@deactivate_coupon"));
Route::post('activate-coupon',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@activate_coupon"));



Route::post('add-product-images',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_product_images"));
Route::post('list-product-images',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_product_images"));
Route::post('delete-product-images',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@delete_product_images"));


Route::post('add-products-to-brand',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_products_to_brand"));

Route::post('add-featured-products',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_featured_products"));
Route::post('list-featured-products',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_featured_products"));




///fronend api's
/////////////////// User Route Start//////////////////////////////
Route::post('create-user',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@create"));
Route::post('user-login',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@login"));

Route::post('verify-code',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@verify_code"));



Route::post('add-home-sliders',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_home_sliders"));
Route::post('list-home-sliders',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_home_sliders"));
Route::post('delete-home-sliders',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_home_sliders"));

Route::post('add-wishlist',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@add_wishlist"));
Route::post('list-wishlist',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@list_wishlist"));
Route::post('delete-wishlist',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@delete_wishlist"));

Route::post('add-appointment',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\User\UserController@add_appointment"));


Route::post('list-product-by-cat',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_product_by_cat"));
Route::post('product-byid',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@product_byid"));


Route::post('single-product',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@single_product"));


Route::post('cat-tree',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@cat_tree"));


//Add shipping Template
Route::post('add-shipping-template',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_shipping"));
Route::post('list-shipping-template',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_shipping"));
Route::post('delete-shipping-template',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@delete_shipping"));
Route::post('edit-shipping-template',array('middleware' => 'cors', 'uses' =>  "App\Http\Controllers\Admin\ProductController@edit_shipping"));

//add-region-to-template 
Route::post('add-region-to-template',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_region"));
Route::post('list-region-to-template',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_region"));
Route::post('delete-region-template',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@delete_region"));
Route::post('edit-region-template',array('middleware' => 'cors', 'uses' =>  "App\Http\Controllers\Admin\ProductController@edit_region"));
// <<<<<<< HEAD
// =======

Route::post('shop-look-image',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@shop_look_image"));

Route::post('add-shop-look-category',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_shop_look_category"));
Route::post('list-shop-look-category',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_shop_look_category"));
Route::post('delete-shop-look-category',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@delete_shop_look_category"));
Route::post('edit-shop-look-category',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@edit_shop_look_category"));

Route::post('add-product-to-category',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@add_product_to_category"));
Route::post('list-product-to-category',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\MasterController@list_product_to_category"));

// >>>>>>>>>>>

// <<<<<<<<<<<<<quickbook api

Route::post('add-product-quickbooks',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@add_product_quickbooks"));

//<<<<<<<<<<<<<<<<<<<user list api

Route::post('list-user',array('middleware' => 'cors', 'uses' => "App\Http\Controllers\Admin\ProductController@list_users"));

//>>>>>>>>>>>>>>>>>>>>>