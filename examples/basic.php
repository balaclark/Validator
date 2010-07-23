<?php

include 'foo/Foo.php';

// include the class & helpers
require '../lib/Validator.Class.php';
require '../lib/Validator.Helpers.php';

$rules['basic'] = array(
	'name' => 'required',
	'email' => 'required|email'
);

// initialise the Validator
$validator = new Validator($rules);

// run the validation checks on the submitted data
$valid = $validator->valid('basic', $_POST);

// if the form was valid, do your stuff!
if ($valid) {
	// go for it..
}

// get any error messages
$errors = $validator->errors();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Validator - Basic Example</title>

		<style>
			label { display: block }
			.error { color: red }
			.success { color: green }
		</style>
	</head>
	<body>

		<?php if (!$valid): ?>

		<form name="basic" action="" method="post">

			<label for="name">Name *</label>
			<input name="name" id="name" value="<?php form_value('name', $_POST) ?>" />
			<?php form_error('name', $errors) ?>

			<br />

			<label for="email">Email *</label>
			<input name="email" id="email" value="<?php form_value('email', $_POST) ?>" />
			<?php form_error('email', $errors) ?>

			<br />

			<label for="comment">Comment</label>
			<textarea name="comment" id="comment"><?php form_value('comment', $_POST) ?></textarea>
			<?php form_error('comment', $errors) ?>

			<br />

			<input type="submit" name="submit" value="submit" />

		</form>

		<?php elseif ($valid): ?>
		<p class="success">The form was valid!</p>
		<?php endif; ?>

	</body>
</html>