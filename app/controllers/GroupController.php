<?php

use \Adldap\Schemas\ActiveDirectory as ActiveDirectory;

class GroupController extends LdapController {

    public function all() {
        $groups = $this->ad->groups()->all()->toArray();
        $fGroups = [];
        foreach ($groups as $group) {
            if(explode(',', $group['dn'])[1] == "OU=Grupos"){
                if(count(explode('-', $group['name'][0])) == 1 && $group['name'][0] !== "DESKTOP PERSONALIZATION"){
                    $fGroups[] = $group;
                }
            }
        }
        return $fGroups;
    }

}