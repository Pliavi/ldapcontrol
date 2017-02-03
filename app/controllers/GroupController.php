<?php

class GroupController extends LdapController {

    public function all() {
        return $this->ad->groups()->all()->toArray();
    }

}