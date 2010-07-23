<?php

// include the class & helpers
require '../lib/Validator.Class.php';
require '../lib/Validator.Helpers.php';

// use a file to store the validation array
$config_file = dirname(__FILE__) . '/config/rules.php';

// initialise the Validator
$validator = new Validator($config_file);

// run the validation checks on the submitted data
$valid = $validator->valid('advanced', $_POST);

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
		<title>Validator - Advanced Example</title>

		<style>
			label {
				display: block;
				margin: 10px 0 0 0;
			}
			label span { font-size: 0.8em }
			.inline { display: inline }
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

			<label for="password">Password (3-6 characters)*</label>
			<input type="password" name="password" id="password" value="<?php form_value('password', $_POST) ?>" />
			<?php form_error('password', $errors) ?>

			<br />

			<label for="password2">Confirm password *</label>
			<input type="password" name="password2" id="password" value="<?php form_value('password2', $_POST) ?>" />
			<?php form_error('password2', $errors) ?>

			<br />
			
			<label for="comment">Comment</label>
			<textarea name="comment" id="comment"><?php form_value('comment', $_POST) ?></textarea>
			<?php form_error('comment', $errors) ?>

			<br />

			<label for="optin" class="inline">Email me</label>
			<input type="checkbox" name="optin" id="optin" value="yes" <?php if (isset($_POST['optin'])) echo 'checked="checked"' ?> />

			<br />

			<label for="email">Email <span>(this field will become required if the above is checked)</span></label>
			<input name="email" id="email" value="<?php form_value('email', $_POST) ?>" />
			<?php form_error('email', $errors) ?>

			<br />

			<input type="submit" name="submit" value="submit" />

		</form>

		<?php elseif ($valid): ?>
		<p class="success">The form was valid!</p>
		<?php endif; ?>

	</body>
</html>