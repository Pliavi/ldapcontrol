<?php

use \Adldap\Schemas\ActiveDirectory as ActiveDirectory;

class TestController extends LdapController {

    public function test() {
        // return json_encode($this->ad->users()->search()->whereEquals('sAMAccountname', '20080')->firstOrFail());//->name[0];
        return json_encode($this->ad->users()->search()->whereEquals('name', 'teste2')->firstOrFail());//->name[0];
    }

}