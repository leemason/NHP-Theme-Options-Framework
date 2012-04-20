<?php
class NHP_Options_upload extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0
	*/
	function __construct($field = array(), $value =''){
		
		parent::__construct();
		$this->field = $field;
		$this->value = $value;
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since NHP_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))?$this->field['class']:'regular-text';
		
		
		echo '<input type="hidden" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$this->value.'" class="'.$class.'" />';
		//if($this->value != ''){
			echo '<img class="nhp-opts-screenshot" id="nhp-opts-screenshot-'.$this->field['id'].'" src="'.$this->value.'" />';
		//}
		
		if($this->value == ''){$remove = ' style="display:none;"';$upload = '';}else{$remove = '';$upload = ' style="display:none;"';}
		echo ' <a href="javascript:void(0);" class="nhp-opts-upload button-secondary"'.$upload.' rel-id="'.$this->field['id'].'">'.__('Browse', 'nhp-opts').'</a>';
		echo ' <a href="javascript:void(0);" class="nhp-opts-upload-remove"'.$remove.' rel-id="'.$this->field['id'].'">'.__('Remove Upload', 'nhp-opts').'</a>';
		
		echo ($this->field['desc'] != '')?'<br/><br/><span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since NHP_Options 1.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'nhp-opts-field-upload-js', 
			$this->url.'fields/upload/field_upload.js', 
			array('jquery', 'thickbox', 'media-upload'),
			time(),
			true
		);
                
                wp_enqueue_style('thickbox');
		
		wp_localize_script('nhp-opts-field-upload-js', 'nhp_upload', array('url' => $this->url.'fields/upload/blank.png'));
		
	}//function
	
}//class
?>