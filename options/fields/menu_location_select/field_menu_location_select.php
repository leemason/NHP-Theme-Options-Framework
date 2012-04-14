<?php
class NHP_Options_menu_location_select extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0.1
	*/
	function __construct($field = array(), $value =''){
		
		parent::__construct();
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since NHP_Options 1.0.1
	*/
	function render(){
		
		$class = (isset($this->field['class']))?'class="'.$this->field['class'].'" ':'';
		global $_wp_registered_nav_menus;

		echo '<select id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' >';
		

		foreach ( $_wp_registered_nav_menus as $k => $v ) {
			echo '<option value="'.$k.'"'.selected($this->value, $k, false).'>'.$v.'</option>';
		}

		echo '</select>';
	
		echo ($this->field['desc'] != '')?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function
	
}//class
?>