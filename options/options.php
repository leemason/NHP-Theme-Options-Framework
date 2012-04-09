<?php
//apply_filters('nhp-opts-page-icon-id', 'icon-themes') - change div class for page icon
//do_action('nhp-opts-enqueue'); - load page speicfic js and css - use this in custom field sto register custom js/css
//do_action('nhp-opts-load-page', $screen); - hooks into the load-page hook
//do_action('nhp-opts-page-before-form'); - before form
//do_action('nhp-opts-before-field', $field(array), $value); - before each field
//do_action('nhp-opts-after-field', $field(array), $value); - after each field
//do_action('nhp-opts-page-after-form'); - after form
//do_action('nhp-opts-after-theme-info', $theme_data(array)); - after theme info
//do_action('nhp-opts-get-fields'); - used to require custom filed classes
//do_action('nhp-opts-get-validation'); - used to require custom validation classes
//do_action('nhp-opts-register-settings'); - hook after registering settings
//do_action('nhp-opts-options-validate', $plugin_options, $options); - hooks into the values validation method

class NHP_Options{

	/**
	 * Class Constructor. Defines the args for the theme options class
	 *
	 * @since NHP_Options 1.0
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function __construct($sections = array(), $args = array()){
		
		//get field classes
		$this->get_fields();
		
		//get validation classes
		$this->get_validation();
		
		//get sections
		$this->sections = $sections;
		
		$defaults = array();
		
		if( file_exists(TEMPLATEPATH.'/options/options.php') ){
			$defaults['theme_dir'] = trailingslashit(get_template_directory());
			$defaults['theme_url'] = trailingslashit(get_template_directory_uri());
		}elseif( file_exists(STYLESHEETPATH.'/options/options.php') ){
			$defaults['theme_dir'] = trailingslashit(get_stylesheet_directory());
			$defaults['theme_url'] = trailingslashit(get_stylesheet_directory_uri());
		}
		
		$defaults['theme_data'] = get_theme_data($defaults['theme_dir'] .'style.css');
		$defaults['theme_data']['short_name'] = strtolower(preg_replace('/ /', '_', $defaults['theme_data']['Title']));
		
		$defaults['opt_name'] = $defaults['theme_data']['short_name'];
		
		$defaults['menu_title'] = __('Theme Options', 'nhp-opts');
		$defaults['page_title'] = $defaults['theme_data']['Title'].__(' Theme Options', 'nhp-opts');
		$defaults['page_slug'] = 'nhp_theme_options';
		$defaults['page_cap'] = 'manage_options';
		
		$defaults['show_theme_info'] = true;
		$defaults['dev_mode'] = true;
		
		
		foreach($defaults['theme_data'] as $tkey => $tdata){
			if(is_array($tdata)){$tdata = implode(', ',$tdata);}
			//if($tkey != ''){
				$helpdata[] = '<p><strong>'.$tkey.'</strong> - '.$tdata.'</p>';
			//}//if
		}//foreach
		
		
		$defaults['help_tabs'][] = array(
										'id' => 'nhp-opts-1',
										'title' => __('Theme Information', 'nhp-opts'),
										'content' => implode($helpdata)
										);
										
		$defaults['help_sidebar'] = __('', 'nhp-opts');
		
		
		
		
		$this->args = wp_parse_args( $args, $defaults );
		
		//setup the errors array for later
		$this->errors = array();
		
		//set option with defaults
		add_action('admin_init', array(&$this, '_set_default_options'));
		
		//options page
		add_action('admin_menu', array(&$this, '_options_page'));
		
		//register setting
		add_action('admin_init', array(&$this, '_register_setting'));
		
		
		add_action('nhp-opts-page-before-form', array(&$this, '_errors_js'), 1);
		
	}//function
	
	
	/**
	 * PHP4 Fallback, i know WP is PHP5 now i just cant help myself im so used to it.
	 *
	 * @since NHP_Options 1.0
	 *
	 * @param $array $args Arguments. Class constructor arguments.
	*/
	function NHP_Options($args){
		__construct($args);
	}//function
	
	
	
	/**
	 * Get Fields - requires all the built in classes for field use
	 *
	 * @since NHP_Options 1.0
	 *
	*/
	function get_fields(){
		require_once('fields/text/field_text.php');
		require_once('fields/textarea/field_textarea.php');
		require_once('fields/editor/field_editor.php');
		require_once('fields/checkbox/field_checkbox.php');
		require_once('fields/multi_checkbox/field_multi_checkbox.php');
		require_once('fields/select/field_select.php');
		require_once('fields/multi_select/field_multi_select.php');
		require_once('fields/radio/field_radio.php');
		require_once('fields/radio_img/field_radio_img.php');
		require_once('fields/button_set/field_button_set.php');
		require_once('fields/upload/field_upload.php');
		require_once('fields/color/field_color.php');
		require_once('fields/date/field_date.php');
		require_once('fields/info/field_info.php');
		require_once('fields/divide/field_divide.php');
		
		do_action('nhp-opts-get-fields');
	}//function
	
	
	/**
	 * Get Validation - requires all the built in classes for validation use
	 *
	 * @since NHP_Options 1.0
	 *
	*/
	function get_validation(){
		require_once('validation/email/validation_email.php');
		require_once('validation/no_html/validation_no_html.php');
		require_once('validation/html/validation_html.php');
		require_once('validation/html_custom/validation_html_custom.php');
		require_once('validation/url/validation_url.php');
		require_once('validation/numeric/validation_numeric.php');
		require_once('validation/js/validation_js.php');
		
		do_action('nhp-opts-get-validation');
	}//function
	
	
	/**
	 * Get default options into an array suitable for the settings API
	 *
	 * @since NHP_Options 1.0
	 *
	*/
	function _default_values(){
		
		$defaults = array();
		
		foreach($this->sections as $k => $section){
		
			foreach($section['fields'] as $fieldk => $field){
				
					$defaults[$field['id']] = $field['std'];
				
			}//foreach
			
		}//foreach
		
		return $defaults;
		
	}
	
	
	
	/**
	 * Set default options on admin_init if option doesnt exist (theme activation hook caused problems, so admin_init it is)
	 *
	 * @since NHP_Options 1.0
	 *
	*/
	function _set_default_options(){
		if(!get_option($this->args['opt_name'])){
			add_option($this->args['opt_name'], $this->_default_values());
		}
	}//function
	
	
	/**
	 * Class Theme Options Page Function, creates main options page.
	 *
	 * @since NHP_Options 1.0
	*/
	function _options_page(){
		$this->page = add_theme_page(
						$this->args['page_title'], 
						$this->args['menu_title'], 
						$this->args['page_cap'], 
						$this->args['page_slug'], 
						array(&$this, '_options_page_html')
					);
		add_action('admin_print_styles-'.$this->page, array(&$this, '_enqueue'));
		add_action('load-'.$this->page, array(&$this, '_load_page'));
	}//function	
	

	/**
	 * enqueue styles/js for theme page
	 *
	 * @since NHP_Options 1.0
	*/
	function _enqueue(){
		
		wp_enqueue_style(
			$this->args['theme_data']['short_name'].'-css', 
			$this->args['theme_url'].'options/css/options.css',
			array('farbtastic'),
			time(),
			'all'
		);
		
		wp_enqueue_style(
			'nhp-opts-jquery-ui-css',
			apply_filters('nhp-opts-ui-theme', $this->args['theme_url'].'options/css/jquery-ui-aristo/aristo.css'),
			'',
			time(),
			'all'
		);
		
		wp_enqueue_script(
			$this->args['theme_data']['short_name'].'-js', 
			$this->args['theme_url'].'options/js/options.js', 
			array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'thickbox', 'farbtastic','media-upload'),
			time(),
			true
		);
		
		do_action('nhp-opts-enqueue');
		
		
	}//function
	
	
	
	/**
	 * show page help
	 *
	 * @since NHP_Options 1.0
	*/
	function _load_page(){
		
		$screen = get_current_screen();
		
		if(is_array($this->args['help_tabs'])){
			foreach($this->args['help_tabs'] as $tab){
				$screen->add_help_tab($tab);
			}//foreach
		}//if
		
		if($this->args['help_sidebar'] != ''){
			$screen->set_help_sidebar($this->args['help_sidebar']);
		}//if
		
		do_action('nhp-opts-load-page', $screen);
		
	}//function
	
	
	
	
	/**
	 * Register Option for use
	 *
	 * @since NHP_Options 1.0
	*/
	function _register_setting(){
		
		register_setting($this->args['opt_name'].'_group', $this->args['opt_name'], array(&$this,'_validate_options'));
		
		foreach($this->sections as $k => $section){
			
			add_settings_section($k.'_section', $section['title'], array(&$this, '_section_desc'), $k.'_section_group');
			
			foreach($section['fields'] as $fieldk => $field){
				
				$th = (isset($field['sub_desc']))?$field['title'].'<span class="description">'.$field['sub_desc'].'</span>':$field['title'];
				
				add_settings_field($fieldk.'_field', $th, array(&$this,'_field_input'), $k.'_section_group', $k.'_section', $field); // checkbox
				
			}//foreach
			
		}//foreach
		
		do_action('nhp-opts-register-settings');
		
	}//function
	
	
	
	/**
	 * Validate the Options options before insertion
	 *
	 * @since NHP_Options 1.0
	*/
	function _validate_options($plugin_options){
		
		if(!empty($plugin_options['defaults'])){
			$plugin_options = $this->_default_values();
			return $plugin_options;
		}//if set defaults
		
		
		//get current options
		$options = get_option($this->args['opt_name']);
		
		//validate fields (if needed)
		$plugin_options = $this->_validate_values($plugin_options, $options);
		
		if($this->errors){
			set_transient('nhp-opts-errors', $this->errors, 1000 );
		}//if errors
		
		do_action('nhp-opts-options-validate', $plugin_options, $options);
		
		
		unset($plugin_options['defaults']);
		
		return $plugin_options;	
	
	}//function
	
	
	
	
	/**
	 * Validate values from options form (used in settings api validate function)
	 * calls the custom validation class for the field so authors can override with custom classes
	 *
	 * @since NHP_Options 1.0
	*/
	function _validate_values($plugin_options, $options){
		foreach($this->sections as $k => $section){
			
			foreach($section['fields'] as $fieldk => $field){
				$field['section_id'] = $k;

				if(isset($field['validate']) && $plugin_options[$field['id']] != ''){
					$validate = 'NHP_Validation_'.$field['validate'];
					if(class_exists($validate)){
						$validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
						$plugin_options[$field['id']] = $validation->value;
						if($validation->error){
							$this->errors[] = $validation->error;
						}
					}//if
				}//if
				
			}//foreach
			
		}//foreach
		return $plugin_options;
	}//function
	
	
	
	
	
	
	
	
	/**
	 * HTML OUTPUT.
	 *
	 * @since NHP_Options 1.0
	*/
	function _options_page_html(){
		
		echo '<div class="wrap">';
			echo '<div id="'.apply_filters('nhp-opts-page-icon-id', 'icon-themes').'" class="icon32"><br/></div>';
			echo '<h2 id="nhp-opts-heading">'.get_admin_page_title().'</h2>';
			echo (isset($this->args['intro_text']))?$this->args['intro_text']:'';
			
			do_action('nhp-opts-page-before-form');

			echo '<form method="post" action="options.php" enctype="multipart/form-data" id="nhp-opts-form-wrapper">';
				settings_fields($this->args['opt_name'].'_group');
				echo '<div id="nhp-opts-header">';
					echo '<input type="submit" name="submit" value="'.__('Save Changes', 'nhp-opts').'" class="button-primary" />';
					echo '<input type="submit" name="'.$this->args['opt_name'].'[defaults]" value="'.__('Reset to Defaults', 'nhp-opts').'" class="button-secondary" />';
					echo '<div class="clear"></div><!--clearfix-->';
				echo '</div>';
				
					if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'){
						echo '<div id="nhp-opts-save">'.__('<strong>Settings Saved!</strong>', 'nhp-opts').'</div>';
					}
					echo '<div id="nhp-opts-save-warn">'.__('<strong>Settings have changed!, you should save them!</strong>', 'nhp-opts').'</div>';
					echo '<div id="nhp-opts-field-errors">'.__('<strong><span></span> error(s) were found!</strong>', 'nhp-opts').'</div>';
				
				echo '<div class="clear"></div><!--clearfix-->';
				
				echo '<div id="nhp-opts-sidebar">';
					echo '<ul id="nhp-opts-group-menu">';
						foreach($this->sections as $k => $section){
							$icon = (!isset($section['icon']))?'<img src="'.$this->args['theme_url'].'options/img/glyphicons/glyphicons_019_cogwheel.png" /> ':'<img src="'.$section['icon'].'" /> ';
							echo '<li id="'.$k.'_section_group_li" class="nhp-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="'.$k.'_section_group_li_a" class="nhp-opts-group-tab-link-a" data-rel="'.$k.'">'.$icon.$section['title'].'</a>';
							echo '</li>';
						}
						
						echo '<li class="divide">&nbsp;</li>';
						
						if(file_exists($this->args['theme_dir'].'README.html')){
							echo '<li id="read_me_default_section_group_li" class="nhp-opts-group-tab-link-li">';
								echo '<a href="javascript:void(0);" id="read_me_default_section_group_li_a" class="nhp-opts-group-tab-link-a custom-tab" data-rel="read_me_default"><img src="'.$this->args['theme_url'].'options/img/glyphicons/glyphicons_071_book.png" /> '.__('Documentation', 'nhp-opts').'</a>';
							echo '</li>';
						}//if
						
						if($this->args['show_theme_info'] == true){
							echo '<li id="theme_info_default_section_group_li" class="nhp-opts-group-tab-link-li">';
									echo '<a href="javascript:void(0);" id="theme_info_default_section_group_li_a" class="nhp-opts-group-tab-link-a custom-tab" data-rel="theme_info_default"><img src="'.$this->args['theme_url'].'options/img/glyphicons/glyphicons_195_circle_info.png" /> '.__('Theme Information', 'nhp-opts').'</a>';
							echo '</li>';
						}//if
						
						if(isset($this->args['support_url'])){
							echo '<li id="support_link_default_section_group_li" class="nhp-opts-group-tab-link-li">';
								echo '<a href="'.$this->args['support_url'].'" id="support_link_default_section_group_li_a" class="custom-tab support-link" target="_blank"><img src="'.$this->args['theme_url'].'options/img/glyphicons/glyphicons_050_link.png" /> '.__('Support', 'nhp-opts').'</a>';
							echo '</li>';
						}//if
						
						if($this->args['dev_mode'] == true){
							echo '<li id="dev_mode_default_section_group_li" class="nhp-opts-group-tab-link-li">';
									echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="nhp-opts-group-tab-link-a custom-tab" data-rel="dev_mode_default"><img src="'.$this->args['theme_url'].'options/img/glyphicons/glyphicons_195_circle_info.png" /> '.__('Dev Mode Info', 'nhp-opts').'</a>';
							echo '</li>';
						}//if
						
					echo '</ul>';
				echo '</div>';
				
				echo '<div id="nhp-opts-main">';
					foreach($this->sections as $k => $section){
						echo '<div id="'.$k.'_section_group'.'" class="nhp-opts-group-tab">';
							do_settings_sections($k.'_section_group');
						echo '</div>';
					}
					
					if($this->args['show_theme_info'] == true){
						echo '<div id="theme_info_default_section_group'.'" class="nhp-opts-group-tab">';
							echo '<h3>'.$this->args['theme_data']['Title'].'</h3>';
							echo '<div class="nhp-opts-section-desc">';
							echo '<p class="nhp-opts-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'nhp-opts').'<a href="'.$this->args['theme_data']['URI'].'" target="_blank">'.$this->args['theme_data']['URI'].'</a></p>';
							echo '<p class="nhp-opts-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'nhp-opts').$this->args['theme_data']['Author'].'</p>';
							echo '<p class="nhp-opts-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'nhp-opts').$this->args['theme_data']['Version'].'</p>';
							echo '<p class="nhp-opts-theme-data description theme-description">'.$this->args['theme_data']['Description'].'</p>';
							echo '<p class="nhp-opts-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'nhp-opts').implode(', ', $this->args['theme_data']['Tags']).'</p>';
							do_action('nhp-opts-after-theme-info', $this->args['theme_data']);
							echo '</div>';
						echo '</div>';
					}
					
					if(file_exists($this->args['theme_dir'].'README.html')){
						echo '<div id="read_me_default_section_group'.'" class="nhp-opts-group-tab">';
							echo nl2br(file_get_contents($this->args['theme_dir'].'README.html'));
						echo '</div>';
					}//if
					
					if($this->args['dev_mode'] == true){
						echo '<div id="dev_mode_default_section_group'.'" class="nhp-opts-group-tab">';
							echo '<h3>'.__('Dev Mode Info', 'nhp-opts').'</h3>';
							echo '<div class="nhp-opts-section-desc">';
							echo '<textarea class="large-text" rows="24">'.print_r($this, true).'</textarea>';
							echo '</div>';
						echo '</div>';
					}
				
					echo '<div class="clear"></div><!--clearfix-->';
				echo '</div>';
				echo '<div class="clear"></div><!--clearfix-->';
				
				echo '<div id="nhp-opts-footer">';
				
					if(isset($this->args['share_icons'])){
						echo '<div id="nhp-opts-share">';
						foreach($this->args['share_icons'] as $link){
							echo '<a href="'.$link['link'].'" title="'.$link['title'].'" target="_blank"><img src="'.$link['img'].'"/></a>';
						}
						echo '</div>';
					}
					
					echo '<input type="submit" name="submit" value="'.__('Save Changes', 'nhp-opts').'" class="button-primary" />';
					echo '<input type="submit" name="'.$this->args['opt_name'].'[defaults]" value="'.__('Reset to Defaults', 'nhp-opts').'" class="button-secondary" />';
					echo '<div class="clear"></div><!--clearfix-->';
				echo '</div>';
			
			echo '</form>';
			
			do_action('nhp-opts-page-after-form');
			
			echo '<div class="clear"></div><!--clearfix-->';	
		echo '</div><!--wrap-->';

	}//function
	
	
	
	/**
	 * JS to display the errors on the page
	 *
	 * @since NHP_Options 1.0
	*/	
	function _errors_js(){
		
		if(get_transient('nhp-opts-errors')){
				$errors = get_transient('nhp-opts-errors');
				
				foreach($errors as $error){
					$section_errors[$error['section_id']]++;
				}
				
				
				echo '<script type="text/javascript">';
					echo 'jQuery(document).ready(function(){';
						echo 'jQuery("#nhp-opts-field-errors span").html("'.count($errors).'");';
						echo 'jQuery("#nhp-opts-field-errors").show();';
						
						foreach($section_errors as $sectionkey => $section_error){
							echo 'jQuery("#'.$sectionkey.'_section_group_li_a").append("<span class=\"nhp-opts-menu-error\">'.$section_error.'</span>");';
						}
						
						foreach($errors as $error){
							echo 'jQuery("#'.$error['id'].'").attr("style", "border-color: #B94A48;");';
							echo 'jQuery("#'.$error['id'].'").parent("td").append("<span class=\"nhp-opts-th-error\">'.$error['msg'].'</span>");';
						}
					echo '});';
				echo '</script>';
				delete_transient('nhp-opts-errors');
			}
		
	}//function

	
	
	/**
	 * Section HTML OUTPUT.
	 *
	 * @since NHP_Options 1.0
	*/	
	function _section_desc($section){
		
		$id = rtrim($section['id'], '_section');
		
		echo '<div class="nhp-opts-section-desc">'.$this->sections[$id]['desc'].'</div>';
		
	}//function
	
	
	
	
	/**
	 * Field HTML OUTPUT.
	 *
	 * Gets option from options array, then calls the speicfic field type class - allows extending by other devs
	 *
	 * @since NHP_Options 1.0
	*/
	function _field_input($field){
		
		$options = get_option($this->args['opt_name']);
		
		if(isset($field['callback'])){
			do_action('nhp-opts-before-field', $field, $options[$field['id']]);
			call_user_func($field['callback'], $field, $options[$field['id']]);
			do_action('nhp-opts-after-field', $field, $options[$field['id']]);
			return;
		}
		
		$field_class = 'NHP_Options_'.$field['type'];
		
		if(class_exists($field_class)){
			do_action('nhp-opts-before-field', $field, $options[$field['id']]);
			new $field_class($field, $options[$field['id']]);
			do_action('nhp-opts-after-field', $field, $options[$field['id']]);
		}//if
		
	}//function

	
}//class
?>