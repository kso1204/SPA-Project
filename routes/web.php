<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your lication. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::post('/create_tag', 'TestController@test');

Route::prefix('app')->middleware(AdminCheck::class)->group(function(){

    Route::post('/create_tag', 'AdminController@addTag');
    Route::get('/get_tags', 'AdminController@getTag');
    Route::post('/edit_tag', 'AdminController@editTag');
    Route::post('/delete_tag', 'AdminController@deleteTag');
    Route::post('/upload', 'AdminController@upload');
    Route::post('/delete_image', 'AdminController@deleteImage');
    
    Route::post('/create_category', 'AdminController@addCategory');
    Route::get('/get_category', 'AdminController@getCategory');
    Route::post('/edit_category', 'AdminController@editCategory');
    Route::post('/delete_category', 'AdminController@deleteCategory');
    
    
    Route::post('/create_user', 'AdminController@createUser');
    Route::get('/get_users', 'AdminController@getUsers');
    Route::post('/edit_users', 'AdminController@editUsers');
    Route::post('/admin_login', 'AdminController@adminLogin');

    // role route
    Route::post('/create_role', 'AdminController@addRole');
    Route::get('/get_roles', 'AdminController@getRole');
    Route::post('/edit_role', 'AdminController@editRole');


    Route::post('/assign_roles', 'AdminController@assignRole');
});


Route::get('/logout','AdminController@logout');
Route::get('/','AdminController@index');
Route::get('{slug}','AdminController@index');

/* 
Route::get('/', function () {
    return view('welcome');
});


Route::get('{slug}', function(){
     return view('welcome');
}); */