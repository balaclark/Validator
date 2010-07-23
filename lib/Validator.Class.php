<?php

/**
 * Validator.Class.php
 *
 * @author Bala Clark
 * @package Validator
 */

/**
 * This class will validate an array of data (typically from $_GET or $_POST) against
 * rules defined in a config array.
 *
 * TODO: store error messages in config.
 *
 * @author Bala Clark
 * @package Validator.Class
 */
class Validator {

	const RULES_SEPARATOR = '|';

	protected $rules = array();
	protected $fields = array();
	protected $messages = array();
	protected $config;

	/**
	 * Construct.
	 * Sets up validation rules
	 *
	 * @param mixed $rules Either an array of rules, or a file path containing the array of rules
	 */
	function __construct($rules) {

		if (is_string($rules) && file_exists($rules)) require $rules;
		else if (is_array($rules)) $validation = $rules;
		
		$this->rules = $validation;
	}

	/**
	 * Return any error messages
	 *
	 * @return array
	 */
	function errors() {
		return $this->messages;
	}

	/**
	 * Validator. Uses the valiation config to define rules.
	 *
	 * @param string $form Form name
	 * @param array $fields Fields to validate
	 * @return boolean
	 */
	function valid($form, $fields) {
		
		// only proceed if there are some rules & fields set
		if (!isset($this->rules[$form]) || empty($fields)) {
			throw new exception('No validation rules set for form: "' . $form . '"');
		}
		
		$this->fields = $fields;
		$this->messages = array();

		foreach ($this->rules[$form] as $field_name => $rule) {

			// validate single rule
			if (!strpos($rule, self::RULES_SEPARATOR)) {
				$this->validate($rule, $field_name);
			}

			// validate multiple rules
			else {
				$rules = explode(self::RULES_SEPARATOR, $rule);
				foreach($rules as $rule) {
					$this->validate($rule, $field_name);
				}
			}
		}

		// return true if valid, otherwise return error messages
		if (empty($this->messages)) return true;
	}

	/**
	 * Runs a given validation method. Sets error messages if any
	 *
	 * @param string $rule
	 * @param string $field_name
	 * @return void
	 */
	protected function validate($rule, $field_name) {

		$field_var = null;

		// strip out any rule vars
		if (strpos($rule, '[') !== false) {
			$arr = $this->strip_var($rule);
			$field_var = $arr['var'];
			$rule = $arr['rule'];
		}

		if (!method_exists($this, $rule))
			throw new Exception ("Validation rule: {$rule} doesn't exist");

		// check for fields that are sub arrays
		if (preg_match('/(\w+)\[(\w+)\]/', $field_name, $matches)) {
			$field = (isset($this->fields[$matches[1]][$matches[2]])) ? $this->fields[$matches[1]][$matches[2]] : null;
		}

		// check for normal flat array fields
		else $field = (isset($this->fields[$field_name])) ? $this->fields[$field_name] : null;

		// run the validation method
		$msg = $this->$rule($field, $field_name, $field_var);
		if (!empty($msg)) $this->messages[$field_name] = $msg;
	}

	/**
	 * Strip out any vars sent with a rule
	 *
	 * @param string $string The rule name[var]
	 * @return array ('rule', 'var')
	 */
	protected function strip_var($string) {

		$var = preg_replace(array('/[a-z].*\[/', '/\]/i'), '', $string);
		$rule = preg_replace('/\[([a-z]|[0-9]).*\]/i', '', $string);

		return array('rule' => $rule, 'var' => $var);
	}

	// validation rules --------------------------------------------------------

	/**
	 * Check field is set.
	 *
	 * @param mixed $value
	 * @return void
	 */
	protected function required($value) {
		if (is_string($value)) $value = trim($value);
		if (is_null($value) || (is_array($value) && empty($value)) ||((is_numeric($value) && !isset($value)) || (!is_numeric($value) && empty($value)))) return 'This field is required.';
	}

	/**
	 * Check if field is set as long as a condition is met.
	 * Checks that another field meets certain criteria before requiring the current
	 * field not be empty.
	 *
	 * Multiple conditions can be passed, seperated by "&"
	 *
	 * TODO: support all math operators, not just "="
	 * TODO: refactor this so that any validation method can be set to "if"
	 * TODO: support OR as well as AND for multiple conditions
	 *
	 * @param $value mixed current field value
	 * @param $field_name string field to check against
	 * @param $condition string the condition statement
	 * @return Error message
	 * @see Validator::required()
	 */
	protected function requiredif($value, $field_name, $conditions) {

		$conditions = explode('&', $conditions);

		foreach ($conditions as $condition) {

			// get the condition's operator // TODO: support more operators
			preg_match('/([\w|-]+)(=)([\w|-]+)/', $condition, $matches); // TODO: test this expression more fully with all valid input name characters
			if (!isset($matches) || count($matches) != '4') throw new Exception('Misformed validation conditional: '.$condition);

			list($origin, $reference, $operator, $ref_value) = $matches;
			if (!isset($this->fields[$reference])) throw new Exception ('Reference field doesn\'t exist');

			// check if the condition is met, don't continue running if it is not
			switch ($operator) {
				case '=': if ($this->fields[$reference] !== $ref_value) return false; break;
				default: throw new Exception ('Invalid operator.');
			}

		}

		// if we get to this point the condition has been met, so validate
		return $this->required($value);

	}

	/**
	 * Check for valid, non-existing email address.
	 *
	 * @param string $email
	 * @return string Error message
	 */
	protected function email($email) {

		if (empty($email)) return;

		if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $email))
			return 'Please enter a valid email address';
	}

	/**
	 * Check that two fields have the same value.
	 *
	 * @param string $value The value to check
	 * @param string $field
	 * @param string $field_confirm
	 * @return string Error message
	 */
	protected function confirm($value, $field_name, $field_confirm) {

		if (!isset($this->fields[$field_confirm]))
			throw new Exception ("Validation error, \"$field_name\" confirmation field \"$field_confirm\" does not exist.");

		if ($this->fields[$field_name] != $this->fields[$field_confirm])
			return 'Fields do not match.';
	}

	protected function length($value, $field_name, $length) {
		if (!empty($value) && strlen($value) != $length) return "This field must be $length characters long.";
	}

	/**
	 * Check value meets a minimum string length
	 *
	 * @param string $value
	 * @param string $field_name
	 * @param string $length Min length
	 * @return string Error message
	 */
	protected function minlength($value, $field_name, $length) {
		if(!empty($value) && strlen($value) < $length) return "This field must be at least $length characters long.";
	}

	/**
	 * Check value meets a maximum string length
	 *
	 * @param string $value
	 * @param string $field_name
	 * @param string $length Max length
	 * @return string Error message
	 */
	protected function maxlength($value, $field_name, $length) {
		if(!empty($value) && (strlen($value) > $length)) return "This field cannot be more than $length characters long.";
	}

	protected function string($value) {
		if (!empty($value) && !is_string($value)) return "This field can only contain text.";
	}

	protected function numeric($value) {
		if (!empty($value) && !is_numeric($value)) return "This field can only contain numbers.";
	}
}
