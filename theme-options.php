<?php
require( dirname( __FILE__ ) . '/options/options.php' );
$args = array();
/*
 *
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = true;

//Add HTML before the form
$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'nhp-opts');

//Set the support URL - if not set the link/tab isnt shown in the form
$args['support_url'] = 'http://no-half-pixels.com';

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/lee__mason',
										'title' => 'Folow me on Twitter', 
										'img' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_322_twitter.png'
										);
$args['share_icons']['linked_in'] = array(
										'link' => 'http://uk.linkedin.com/pub/lee-mason/38/618/bab',
										'title' => 'Find me on LinkedIn', 
										'img' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_337_linked_in.png'
										);

//Set this to false to stop the Theme Information tab from being displayed - default functionality is to allow
//$args['show_theme_info'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
//$args['opt_name'] = $defaults['theme_data']['short_name'];

//Custom menu title for options page - default is "Theme Options"
//$args['menu_title'] = __('Theme Options', 'nhp-opts');

//Custom Page Title for options page - default is "Theme name Theme Options"
//$args['page_title'] = $defaults['theme_data']['Title'].__(' Theme Options', 'nhp-opts');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
//$args['page_slug'] = 'nhp_theme_options';

//Custom page capability - default is set to "manage_options"
//$args['page_cap'] = 'manage_options';
		
//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition		
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-1',
							'title' => __('Theme Information 1', 'nhp-opts'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'nhp-opts')
							);
$args['help_tabs'][] = array(
							'id' => 'nhp-opts-2',
							'title' => __('Theme Information 2', 'nhp-opts'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'nhp-opts')
							);

//Set the Help Sidebar for the options page - no sidebar by default										
$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'nhp-opts');



$sections = array();

$sections[] = array(
				'title' => __('Getting Started', 'nhp-opts'),
				'desc' => __('<p class="description">This is the description field for the Section. HTML is allowed</p>', 'nhp-opts'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
				
$sections[] = array(
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_107_text_resize.png',
				'title' => __('Text Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '1', //must be unique
						'type' => 'text', //builtin fields include:
										  //text|textarea|editor|checkbox|multi_checkbox|radio|radio_img|button_set|select|multi_select|color|date|divide|info|upload
						'title' => __('Text Option', 'nhp-opts'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						//'validate' => '', //builtin validation includes: email|html|html_custom|no_html|js|numeric|url
						//'std' => '', //This is a default value, used to set the options on theme activation, and if the user hits the Reset to defaults Button
						//'class' => '' //Set custom classes for elements if you want to do something a little different - default is "regular-text"
						),
					array(
						'id' => '2',
						'type' => 'text',
						'title' => __('Text Option - Email Validated', 'nhp-opts'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'email',
						'std' => 'test@test.com'
						),
					array(
						'id' => '3',
						'type' => 'text',
						'title' => __('Text Option - URL Validated', 'nhp-opts'),
						'sub_desc' => __('This must be a URL.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'url',
						'std' => 'http://no-half-pixels.com'
						),
					array(
						'id' => '4',
						'type' => 'text',
						'title' => __('Text Option - Numeric Validated', 'nhp-opts'),
						'sub_desc' => __('This must be numeric.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => '5',
						'type' => 'textarea',
						'title' => __('Textarea Option - No HTML Validated', 'nhp-opts'), 
						'sub_desc' => __('All HTML will be stripped', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'no_html',
						'std' => 'No HTML is allowed in here.'
						),
					array(
						'id' => '6',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated', 'nhp-opts'), 
						'sub_desc' => __('HTML Allowed (wp_kses)', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
						'std' => 'HTML is allowed in here.'
						),
					array(
						'id' => '7',
						'type' => 'textarea',
						'title' => __('Textarea Option - HTML Validated Custom', 'nhp-opts'), 
						'sub_desc' => __('Custom HTML Allowed (wp_kses)', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'html_custom',
						'std' => 'Some HTML is allowed in here.',
						'allowed_html' => array() //see http://codex.wordpress.org/Function_Reference/wp_kses
						),
					array(
						'id' => '8',
						'type' => 'textarea',
						'title' => __('Textarea Option - JS Validated', 'nhp-opts'), 
						'sub_desc' => __('JS will be escaped', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'js'
						),
					array(
						'id' => '9',
						'type' => 'editor',
						'title' => __('Editor Option', 'nhp-opts'), 
						'sub_desc' => __('Can also use the validation methods if required', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => 'OOOOOOhhhh, rich editing.'
						)
					)
				);
$sections[] = array(
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_150_check.png',
				'title' => __('Radio/Checkbox Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '10',
						'type' => 'checkbox',
						'title' => __('Checkbox Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => '1'// 1 = on | 0 = off
						),
					array(
						'id' => '11',
						'type' => 'multi_checkbox',
						'title' => __('Multi Checkbox Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for multi checkbox options
						'std' => array('1' => '1', '2' => '0', '3' => '0')//See how std has changed? you also dont need to specify opts that are 0.
						),
					array(
						'id' => '12',
						'type' => 'radio',
						'title' => __('Radio Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),
					array(
						'id' => '13',
						'type' => 'radio_img',
						'title' => __('Radio Image Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array(
										'1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
										'2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
										'3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
										'4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						)									
					)
				);
$sections[] = array(
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_157_show_lines.png',
				'title' => __('Select Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '14',
						'type' => 'select',
						'title' => __('Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for select options
						'std' => '2'
						),
					array(
						'id' => '15',
						'type' => 'multi_select',
						'title' => __('Multi Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => array('2','3')
						)									
					)
				);
$sections[] = array(
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_023_cogwheels.png',
				'title' => __('Custom Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '16',
						'type' => 'color',
						'title' => __('Color Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => '#FFFFFF'
						),
					array(
						'id' => '17',
						'type' => 'date',
						'title' => __('Date Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => '18',
						'type' => 'button_set',
						'title' => __('Button Set Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'options' => array('1' => 'Opt 1','2' => 'Opt 2','3' => 'Opt 3'),//Must provide key => value pairs for radio options
						'std' => '2'
						),
					array(
						'id' => '19',
						'type' => 'upload',
						'title' => __('Upload Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						)									
					)
				);
$sections[] = array(
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_093_crop.png',
				'title' => __('Non Value Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '20',
						'type' => 'text',
						'title' => __('Text Field', 'nhp-opts'), 
						'sub_desc' => __('Additional Info', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => '21',
						'type' => 'divide'
						),
					array(
						'id' => '22',
						'type' => 'text',
						'title' => __('Text Field', 'nhp-opts'), 
						'sub_desc' => __('Additional Info', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						),
					array(
						'id' => '23',
						'type' => 'info',
						'desc' => __('<p class="description">This is the info field, if you want to break sections up.</p>', 'nhp-opts')
						),
					array(
						'id' => '24',
						'type' => 'text',
						'title' => __('Text Field', 'nhp-opts'), 
						'sub_desc' => __('Additional Info', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
						)				
					)
				);

new NHP_Options($sections, $args);







/*
 *
 *
 *
 * Okay lets look at extending this framework
 *
 *
 *
 */
 
//apply_filters('nhp-opts-page-icon-id', 'icon-themes') - filter div id for page icon
//do_action('nhp-opts-enqueue'); - load page speicfic js and css - use this in custom field classes to register custom js/css
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
?>