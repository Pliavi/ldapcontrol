<?php
### LOGIN
Route::get('login', 'LoginController@loginView');
Route::post('signin', 'LoginController@login');

### USUÁRIO
### \ Views
Route::get('/',                  [ 'as' => 'findUserView'        , 'uses' => 'UserController@findView' ]);
Route::get('/novo_usuario',      [ 'as' => 'newUserView'         , 'uses' => 'UserController@newUserView' ]);
Route::get('/info_usuario',      [ 'as' => 'userInfoView'        , 'uses' => 'UserController@infoView' ]);
Route::post('/confirma_usuario', [ 'as' => 'userConfirmationView', 'uses' => 'UserController@confirmationView' ]);
### \ Funções
Route::get('/usuario', [ 'as' => 'findUser',   'uses' => 'UserController@find' ]);
Route::post('/criar_usuario',    [ 'as' => 'createUser', 'uses' => 'UserController@create' ]);

### "API"
Route::post('/groups', 'GroupController@all');

### TESTES
Route::get('test', 'TestController@test');