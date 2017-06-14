<?php


class Validate {

	private $_passed = false;
	private $_errors = array();
	private $_db = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {
				
				$value = trim($source[$item]);
				$item = escape($item);

				if(!empty($value)) {
					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be a minimum of {$rule_value} characters");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be a maximum of {$rule_value} characters");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}");
							}
						break;
						// Check if the item exists in the database
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} already exists.");
							}
						break;
						// If value is numeric
						case 'numeric':
							if(is_numeric($value)) {
								$this->addError("{$item} must be alphanumeric.");
							}
						break;
					}
				}

			}
		}

		if(empty($this->errors())) {
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}

}