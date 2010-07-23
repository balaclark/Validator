
Validator
=========

Usage
-----

The validator is very simple to use. See the included examples for some simple
working code.

Setting Rules
-------------
Validation rules must be defined in an associative array and can be passed into
the constructor directly as an array, or can be saved in a separate file. If saved
separately, the file path where they are stored must be passed into the constructor.

If rules are stored in a separate file, the rules array **must** be called $validation.

Array Format:

<pre>
$validation['form_name'] = array(
	'username' => 'required',
	'password' => 'required|password'
);
</pre>

Use the form name attribute as the array key, separate multiple validation rules
with pipes: e.g. "required|username"

Available validation methods
----------------------------

* required
* requiredif[field=value]
* email
* username
* confirm[element(str)]
* numeric
* length[length(int)]
* minlength[length(int)]
* maxlength[length(int)]

requiredif()
------------
The required method can also be set to conditional based on the value of another field.
currently only the equals (=) operator is supported, full operator support will come
in a later version.

e.g. $validation['new_name'] = 'requiredif[name=new]';

Multiple conditions can be chained with "&":
e.g. $validation['new_name'] = 'requiredif[name=new&user=yes]';

Helper Functions
----------------
Validator.Helper.php includes some useful HTML helpers that can speed up your form
development. Included functions are:

 *	**form_value( _$name_ , _$form_ )**
	Helps remember entered text.

 *	**form_error( _$name_ , _$errors_ )**
	Will output an error message for an element.

 *	**form_radio( _$name_ , _$data_ [, _$form_, _$id_as_value_] )**
	Creates a HTML radio input. Using this function allows previously entered values
	to be easily remembered.

 *	**form_select( _$name_ , _$data_ [, _$form_ , _$id_as_value_] )**
	Creates a HTML select element from an array. Using this function allows previously
	entered values to be easily remembered.

License
=======
Copyright 2010 Bala Clark

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.