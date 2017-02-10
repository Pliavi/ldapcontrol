<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

	use UserTrait;

	protected $fillable = ['name', 'id', 'password'];
	protected $hidden = array('password');

}
