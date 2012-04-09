<?php
class NHP_Options_radio_img extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0
	*/
	function __construct($field, $value){
		
		parent::__construct();
		$this->field = $field;
		$this->value = $value;
		$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since NHP_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?'class="'.$this->field['class'].'" ':'';
		
		echo '<fieldset>';
			
			foreach($this->field['options'] as $k => $v){

				$selected = (checked($this->value, $k, false) != '')?' nhp-radio-img-selected':'';

				echo '<label class="nhp-radio-img'.$selected.'" for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				echo '<input type="radio" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' value="'.$k.'" '.checked($this->value, $k, false).'/>';
				echo '<img src="'.$v['img'].'" alt="'.$v['title'].'" onclick="jQuery:nhp_radio_img_select(\''.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'\');" />';
				echo '<br/><span>'.$v['title'].'</span>';
				echo '</label>';
				
				
			}//foreach

		echo ($this->field['desc'] != '')?'<br/><span class="description">'.$this->field['desc'].'</span>':'';
		
		echo '</fieldset>';
		
	}//function
	
}//class
?>