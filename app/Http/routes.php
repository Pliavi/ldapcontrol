<?php
use \App\Http\Controllers\LdapController as LDAP;

$app->get('/', function () use ($app) {
    return view('index');
});

$app->get('/new', function () use ($app) {
    return view('new');
});