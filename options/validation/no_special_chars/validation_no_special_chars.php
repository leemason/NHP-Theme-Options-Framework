<?php
class NHP_Validation_no_special_chars extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0.1
	*/
	function __construct($field, $value, $current){
		
		parent::__construct();
		$this->field = $field;
		$this->value = $value;
		$this->current = $current;
		$this->validate();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and validates them
	 *
	 * @since NHP_Options 1.0.1
	*/
	function validate(){
		
		$this->value = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $this->value);
				
	}//function
	
}//class
?>