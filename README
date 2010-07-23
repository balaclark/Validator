
Validator
===============

Usage
---------------

coming soon...

Rules
---------------

Format
---------------
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

requiredif useage
-----------------
The required method can also be set to conditional based on the value of another field.
currently only the equals (=) operator is supported, full operator support will come
in a later version.

e.g. $validation['new_name'] = 'requiredif[name=new]';

Multiple conditions can be chained with "&":
e.g. $validation['new_name'] = 'requiredif[name=new&user=yes]';

