<?php

use \Adldap\Schemas\ActiveDirectory as ActiveDirectory;

class TestController extends LdapController {

    public function test() {
        // return json_encode($this->ad->users()->search()->whereEquals('sAMAccountname', '20080')->firstOrFail());//->name[0];
        $user = $this->ad->users()->search()->whereEquals('samaccountname', '20080')->firstOrFail();
        // $user->lockouttime = 0;
        // $user->pwdlastset = 0;
        // $user->useraccountcontrol = 544;
        return json_encode($user);
        // if($user->save()){
        //     echo "deu";
        // }else{
        //     echo 'errpo'. $this->ad->getConnection()->getLastError();
        // }
    }

}