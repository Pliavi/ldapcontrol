<?php
use \Adldap\Adldap as LDAP;
use \Adldap\Exceptions\AdldapException as AdldapException;

class LoginController extends BaseController{

    public function loginView(){ return View::make('login'); }

    public function login(){
        $user = Input::all();
        $config = ADCONFIG;
        $config['admin_username'] = ADCONFIG['admin_prefix'].$user['user'];
        $config['admin_password'] = $user['password'];

        try{
            # Tenta conexão com a conta do usuário e busca seu nome, falha em caso de senha incorreta
            $ad = new LDAP($config); 
            $user = $ad->users()->search()->whereEquals('sAMAccountname', $user['user'])->firstOrFail()->name[0];

            # Loga o usuário manualmente (sem verificação, pois a mesma foi feita pelo AD)
            // $user = new User(['id' => 1, 'name' => $user]);
            // Auth::login($user);
            Session::flash('auth', $user);
            Session::flash('success', "Bem vindo, {$user}!");

            # Encerra a conexão, pois é desnecessária, o usuario logado não terá privilégios
            $ad->getConnection()->close(); 
            
            return Redirect::route('findUserView');
        } catch(AdldapException $e) {
            if(isset($ad)){
                $ad->getConnection()->close();
            }
            Session::flash('error', 'Usuário/Senha incorretos');
            return Redirect::back();
        }

    }

    public function logout(){
        Session::forget('auth');
        return Redirect::guest('login');
    }
}