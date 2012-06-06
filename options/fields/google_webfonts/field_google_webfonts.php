<?php
class NHP_Options_google_webfonts extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		$this->field['fonts'] = array();
		
		$fonts = get_transient('nhp-opts-google-webfonts');
		if(!is_array(json_decode($fonts))){
			
			$fonts = wp_remote_retrieve_body(wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?key='.$this->args['google_api_key']));
			set_transient('nhp-opts-google-webfonts', $fonts, 60 * 60 * 24);
				
		}
		$this->field['fonts'] = json_decode($fonts);
		
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
		
		echo '<p class="description" style="color:red;">'.__('The fonts provided below are free to use custom fonts from the <a href="http://www.google.com/webfonts" target="_blank">Google Web Fonts directory</a>.<br/>Please <a href="http://www.google.com/webfonts" target="_blank">browse the directory</a> to preview a font, then select your choice below.', 'nhp-opts').'</p>';
		
		echo '<select id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.'rows="6" >';
		
		
		foreach($this->field['fonts']->items as $cut){
			
			foreach($cut->variants as $variant){
				
				echo '<option value="'.$cut->family.':'.$variant.'" '.selected($this->value, $cut->family.':'.$variant, false).'>'.$cut->family.' - '.$variant.'</option>';
			}
		}
		echo '</select>';

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
	}//function
	
}//class
?>