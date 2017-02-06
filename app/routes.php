<?php
Route::get('/', function(){ 
    $groups = (new GroupController)->all();
    return View::make('new', compact('groups')); 
});

Route::post('/user/create', ['as' => 'createUser', 'uses' => 'UserController@create']);
Route::post('/groups', 'GroupController@all');