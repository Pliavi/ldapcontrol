<?php
### Extend Validator
Validator::extend('alpha_spaces', function($attribute, $value) {
    return preg_match('/^[\pL\s]+$/u', $value);
});

### LOGIN
Session::keep(['auth']); # Mantém o usuário logado
Route::get('login', 'LoginController@loginView');
Route::post('signin', 'LoginController@login');
Route::get('logout', [ 'as' => 'logout', 'uses' => 'LoginController@logout']);

Route::group(['before' => 'auth'], function(){
    ### USUÁRIO
    ### \ Views
    Route::get('/',                  [ 'as' => 'findUserView'        , 'uses' => 'UserController@findView' ]);
    Route::get('/novo_usuario',      [ 'as' => 'newUserView'         , 'uses' => 'UserController@newUserView' ]);
    Route::get('/info_usuario',      [ 'as' => 'userInfoView'        , 'uses' => 'UserController@infoView' ]);
    Route::post('/confirma_usuario', [ 'as' => 'userConfirmationView', 'uses' => 'UserController@confirmationView' ]);
    ### \ Funções
    Route::post('/usuario', [ 'as' => 'findUser',   'uses' => 'UserController@find' ]);
    Route::get('/criar_usuario',    [ 'as' => 'createUser', 'uses' => 'UserController@create' ]);

    ### "API"
    Route::post('/groups', 'GroupController@all');
});

### TESTES
Route::get('test', 'TestController@test');