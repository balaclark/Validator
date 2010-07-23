<?php

$validation['basic'] = array(
	'name' => 'required',
	'email' => 'required|email'
);

$validation['advanced'] = array(
	'name' => 'required',
	'password' => 'required|minlength[3]|maxlength[6]',
	'password2' => 'confirm[password]',
	'email' => 'requiredif[optin=yes]|email'
);