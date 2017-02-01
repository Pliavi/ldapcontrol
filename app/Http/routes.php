<?php
use \App\Http\Controllers\LdapController as LDAP;

$app->get('/', function () use ($app) {
    $ad = new LDAP;
    $ad->connect('localhost');
    $ad->anon();
});
