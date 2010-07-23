<?php

/**
 * Validator.Helpers.php
 * 
 * This collection of functions assist in autopopulating form elements. 
 * 
 * @author Bala Clark
 * @package Validator
 */

/**
 * Create the HTML for a select box, allows default values to be dynamically set on load.
 *
 * @param string $name Input name
 * @param array $data Population data
 * @param array $form [optional] Submitted form data
 * @param bool $id_as_value [optional] Use the data array key as input value
 * @return string
 */
function form_select($name, $data, $form = null, $id_as_value = true) {

	$default = (!is_null($form) && isset($form[$name])) ? $form[$name] : null;

	$html = "<select name='$name' id='$name'>";

	foreach($data as $key => $value) {

		$class = ('other' == strtolower($value)) ? 'class="show-other"' : '';
		$selected = (!is_null($default) && ($key == $default || $value == $default)) ? 'selected="selected"' : '';
		if (false === $id_as_value) $key = $value;

		$html .= "<option value='$key' $class $selected>$value</option>";
	}

	$html .= '</select>';

	return $html;
}

/**
 * Create the HTML for radio buttons, allows default values to be dynamically set on load.
 *
 * @param string $name Input name
 * @param array $data Population data
 * @param array $form [optional] Submitted form data
 * @param bool $id_as_value [optional] Use the data array key as input value
 * @return string
 */
function form_radio($name, $data, $form = null, $id_as_value = true) {

	$html = '';
	$default = (!is_null($form) && isset($form[$name])) ? $form[$name] : null;

	foreach($data as $key => $value) {

		$checked = (!is_null($default) && ($key == $default || $value == $default)) ? 'checked="checked"' : '';
		if (false === $id_as_value) $key = $value;

		$html .= "
			<input type='radio' name='$name' value='$key' id='$key' $checked />
			<label for='$key'>$value</label>
		";
	}

	return $html;
}

/**
 * Output a form value if present.
 *
 * Has beta support for multiple level elements (e.g. $_POST['name[first]']). Currently
 * it only works for elements two levels deep or less.
 *
 * @param string $name The input name
 * @param array $form The submitted form array (e.g. $_POST / $_GET / $_REQUEST)
 */
function form_value($name, $form) {

	if (isset($form[$name])) echo $form[$name];

	// check for sub array (only single level supported right now)
	else if (preg_match('/(\w+)\[(\w+)\]/', $name, $matches)) {
		if (isset($form[$matches[1]][$matches[2]])) echo $form[$matches[1]][$matches[2]];
	}
}

/**
 * Returns a span containing an error message if one is present.
 *
 * @param string $name The element name to search for
 * @param array $errors An associative array of errors
 * @return string A HTML span element with the error message
 */
function form_error($name, $errors) {
	if (isset($errors[$name])) echo "<span class='error'>{$errors[$name]}</span>";
}