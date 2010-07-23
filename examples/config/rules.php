<?php

/**
 * Define validation rules for each form here. Use the form name attribute as
 * the array key, seperate multiple validation rules with pipes: "required|username"
 *
 * Available validation methods:
 *
 *   - required
 *   - requiredif[field=value]
 *   - email
 *   - username
 *   - confirm[element(str)]
 *   - numeric
 *   - length[length(int)]
 *   - minlength[length(int)]
 *   - maxlength[length(int)]
 *
 * requiredif useage
 * The required method can also be set to conditional based on the value of another field.
 * The following operators can be used: + , = , != , < , > , <= , >= (currently only "=" is implemented, more to come...)
 *   e.g. $validation['new_name'] = 'requiredif[name=new]';
 *
 * Multiple conditions can be chained with "&":
 *   e.g. $validation['new_name'] = 'requiredif[name=new&user=yes]';
 *
 * Array Format:
 *
 * 	   $validation['form_name'] = array(
 * 		  'username' => 'required',
 * 		  'password' => 'required|password'
 * 	   );
 */

$validation['basic'] = array(
	'name' => 'required',
	'email' => 'required|email'
);