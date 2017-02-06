<?php
use \Adldap\Adldap as LDAP;
use \Adldap\Exceptions\AdldapException as AdldapException;

class LoginController {

    function login(){
        $user = Input::all();
        $config = ADCONFIG;
        $config['admin_username'] = ADCONFIG['PREFIX'].'\\'.$user['user'];
        $config['admin_password'] = $user['password'];

        try{
            $this->ad = new LDAP($this->config); 
            $this->ad->getConnection()->close();
            $user = new User(['user' => $user['user']]);
            Auth::login($user);
        }catch(AdldapException $e){
            if($this->ad !== null){
                $this->ad->getConnection()->close();
            }
            Session::flash('error', 'Usuário/Senha incorretos');
            return Redirect::back();
        }

    }
}