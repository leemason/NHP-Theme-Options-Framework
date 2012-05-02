<?php
/*
 * 
 * Require the framework class before doing anything else, so we can use the defined urls and dirs
 *
 */
require_once( dirname( __FILE__ ) . '/options/options.php' );

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', 'nhp-opts'),
				'desc' => __('<p class="description">This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.</p>', 'nhp-opts'),
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function
//add_filter('nhp-opts-sections-twenty_eleven', 'add_another_section');


/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}//function
//add_filter('nhp-opts-args-twenty_eleven', 'change_framework_args');









/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
$args = array();

//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = true;

//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
$args['intro_text'] = __('<p>This is the HTML which can be displayed before the form, it isnt required, but more info is always better. Anything goes in terms of markup here, any HTML.</p>', 'nhp-opts');

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/lee__mason',
										'title' => 'Folow me on Twitter', 
										'img' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_322_twitter.png'
										);
$args['share_icons']['linked_in'] = array(
										'link' => 'http://uk.linkedin.com/pub/lee-mason/38/618/bab',
										'title' => 'Find me on LinkedIn', 
										'img' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_337_linked_in.png'
										);

//Choose to disable the import/export feature
//$args['show_import_export'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = 'twenty_eleven';

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = __('Theme Options', 'nhp-opts');

//Custom Page Title for options page - default is "Options"
$args['page_title'] = __('Twenty Eleven Theme Options', 'nhp-opts');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "nhp_theme_options"
$args['page_slug'] = 'theme_options';

//Custom page capability - default is set to "manage_options"
//$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
//$args['page_type'] = 'submenu';

//parent menu - default is set to "themes.php" (Appearance)
//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
//$args['page_parent'] = 'themes.php';

//custom page location - default 100 - must be unique or will override other items
//$args['page_position'] = 10;

//Custom page icon class (used to override the page icon next to heading)
//$args['page_icon'] = 'icon-themes';
		
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
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_062_attach.png'
				//Lets leave this as a blank section, no options just some intro text set above.
				//'fields' => array()
				);

				
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_107_text_resize.png',
				'title' => __('Text Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed2</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '1', //must be unique
						'type' => 'text', //builtin fields include:
										  //text|textarea|editor|checkbox|multi_checkbox|radio|radio_img|button_set|select|multi_select|color|date|divide|info|upload
						'title' => __('Text Option', 'nhp-opts'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						//'validate' => '', //builtin validation includes: email|html|html_custom|no_html|js|numeric|url
						//'msg' => 'custom error message', //override the default validation error message for specific fields
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
						'msg' => 'custom error message',
						'std' => 'test@test.com'
						),
					array(
						'id' => 'multi_text',
						'type' => 'multi_text',
						'title' => __('Multi Text Option', 'nhp-opts'),
						'sub_desc' => __('This is a little space under the Field Title in the Options table, additonal info is good in here.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts')
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
						'id' => 'comma_numeric',
						'type' => 'text',
						'title' => __('Text Option - Comma Numeric Validated', 'nhp-opts'),
						'sub_desc' => __('This must be a comma seperated string of numerical values.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'comma_numeric',
						'std' => '0',
						'class' => 'small-text'
						),
					array(
						'id' => 'no_special_chars',
						'type' => 'text',
						'title' => __('Text Option - No Special Chars Validated', 'nhp-opts'),
						'sub_desc' => __('This must be a alpha numeric only.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'no_special_chars',
						'std' => '0'
						),
					array(
						'id' => 'str_replace',
						'type' => 'text',
						'title' => __('Text Option - Str Replace Validated', 'nhp-opts'),
						'sub_desc' => __('You decide.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'str_replace',
						'str' => array('search' => ' ', 'replacement' => 'thisisaspace'),
						'std' => '0'
						),
					array(
						'id' => 'preg_replace',
						'type' => 'text',
						'title' => __('Text Option - Preg Replace Validated', 'nhp-opts'),
						'sub_desc' => __('You decide.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate' => 'preg_replace',
						'preg' => array('pattern' => '/[^a-zA-Z_ -]/s', 'replacement' => 'no numbers'),
						'std' => '0'
						),
					array(
						'id' => 'custom_validate',
						'type' => 'text',
						'title' => __('Text Option - Custom Callback Validated', 'nhp-opts'),
						'sub_desc' => __('You decide.', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'validate_callback' => 'validate_callback_function',
						'std' => '0'
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
						'allowed_html' => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
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
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_150_check.png',
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
						),
					array(
						'id' => 'radio_img',
						'type' => 'radio_img',
						'title' => __('Radio Image Option For Layout', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This uses some of the built in images, you can use them for layout options.', 'nhp-opts'),
						'options' => array(
										'1' => array('title' => '1 Column', 'img' => NHP_OPTIONS_URL.'img/1col.png'),
										'2' => array('title' => '2 Column Left', 'img' => NHP_OPTIONS_URL.'img/2cl.png'),
										'3' => array('title' => '2 Column Right', 'img' => NHP_OPTIONS_URL.'img/2cr.png')
											),//Must provide key => value(array:title|img) pairs for radio options
						'std' => '2'
						)																		
					)
				);
$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_157_show_lines.png',
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
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_023_cogwheels.png',
				'title' => __('Custom Fields', 'nhp-opts'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'nhp-opts'),
				'fields' => array(
					array(
						'id' => '16',
						'type' => 'color',
						'title' => __('Color Option', 'nhp-opts'), 
						'sub_desc' => __('Only color validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => '#FFFFFF'
						),
					array(
						'id' => 'color_gradient',
						'type' => 'color_gradient',
						'title' => __('Color Gradient Option', 'nhp-opts'), 
						'sub_desc' => __('Only color validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This is the description field, again good for additional info.', 'nhp-opts'),
						'std' => array('from' => '#000000', 'to' => '#FFFFFF')
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
						),
					array(
						'id' => 'pages_select',
						'type' => 'pages_select',
						'title' => __('Pages Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites pages.', 'nhp-opts'),
						'args' => array()//uses get_pages
						),
					array(
						'id' => 'pages_multi_select',
						'type' => 'pages_multi_select',
						'title' => __('Pages Multiple Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites pages.', 'nhp-opts'),
						'args' => array('number' => '5')//uses get_pages
						),
					array(
						'id' => 'posts_select',
						'type' => 'posts_select',
						'title' => __('Posts Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites posts.', 'nhp-opts'),
						'args' => array('numberposts' => '10')//uses get_posts
						),
					array(
						'id' => 'posts_multi_select',
						'type' => 'posts_multi_select',
						'title' => __('Posts Multiple Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites posts.', 'nhp-opts'),
						'args' => array('numberposts' => '10')//uses get_posts
						),
					array(
						'id' => 'tags_select',
						'type' => 'tags_select',
						'title' => __('Tags Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites tags.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_tags
						),
					array(
						'id' => 'tags_multi_select',
						'type' => 'tags_multi_select',
						'title' => __('Tags Multiple Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites tags.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_tags
						),
					array(
						'id' => 'cats_select',
						'type' => 'cats_select',
						'title' => __('Cats Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites cats.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_categories
						),
					array(
						'id' => 'cats_multi_select',
						'type' => 'cats_multi_select',
						'title' => __('Cats Multiple Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a Multi Select menu of all the sites cats.', 'nhp-opts'),
						'args' => array('number' => '10')//uses get_categories
						),
					array(
						'id' => 'menu_select',
						'type' => 'menu_select',
						'title' => __('Menu Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the sites menus.', 'nhp-opts'),
						//'args' => array()//uses wp_get_nav_menus
						),
					array(
						'id' => 'select_hide_below',
						'type' => 'select_hide_below',
						'title' => __('Select Hide Below Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field requires certain options to be checked before the below field will be shown.', 'nhp-opts'),
						'options' => array(
									'1' => array('name' => 'Opt 1 field below allowed', 'allow' => 'true'),
									'2' => array('name' => 'Opt 2 field below hidden', 'allow' => 'false'),
									'3' => array('name' => 'Opt 3 field below allowed', 'allow' => 'true')
									),//Must provide key => value(array) pairs for select options
						'std' => '2'
						),
					array(
						'id' => 'menu_location_select',
						'type' => 'menu_location_select',
						'title' => __('Menu Location Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all the themes menu locations.', 'nhp-opts')
						),
					array(
						'id' => 'checkbox_hide_below',
						'type' => 'checkbox_hide_below',
						'title' => __('Checkbox to hide below', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a checkbox which will allow the user to use the next setting.', 'nhp-opts'),
						),
						array(
						'id' => 'post_type_select',
						'type' => 'post_type_select',
						'title' => __('Post Type Select Option', 'nhp-opts'), 
						'sub_desc' => __('No validation can be done on this field type', 'nhp-opts'),
						'desc' => __('This field creates a drop down menu of all registered post types.', 'nhp-opts'),
						//'args' => array()//uses get_post_types
						),
					array(
						'id' => 'custom_callback',
						//'type' => 'nothing',//doesnt need to be called for callback fields
						'title' => __('Custom Field Callback', 'nhp-opts'), 
						'sub_desc' => __('This is a completely unique field type', 'nhp-opts'),
						'desc' => __('This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'nhp-opts'),
						'callback' => 'my_custom_field'
						)								
					)
				);

$sections[] = array(
				'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_093_crop.png',
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
				
				
	$tabs = array();
	
	$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()) .'style.css');
	$theme_info = '<div class="nhp-opts-section-desc">';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'nhp-opts').'<a href="'.$theme_data['URI'].'" target="_blank">'.$theme_data['URI'].'</a></p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'nhp-opts').$theme_data['Author'].'</p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'nhp-opts').$theme_data['Version'].'</p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-description">'.$theme_data['Description'].'</p>';
	$theme_info .= '<p class="nhp-opts-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'nhp-opts').implode(', ', $theme_data['Tags']).'</p>';
	$theme_info .= '</div>';
	
	
	
	$tabs['theme_info'] = array(
					'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_195_circle_info.png',
					'title' => __('Theme Information', 'nhp-opts'),
					'content' => $theme_info
					);
	
	if(file_exists(trailingslashit(get_stylesheet_directory()).'README.html')){
		$tabs['theme_docs'] = array(
						'icon' => NHP_OPTIONS_URL.'img/glyphicons/glyphicons_071_book.png',
						'title' => __('Documentation', 'nhp-opts'),
						'content' => nl2br(file_get_contents(trailingslashit(get_stylesheet_directory()).'README.html'))
						);
	}//if

	global $NHP_Options;
	$NHP_Options = new NHP_Options($sections, $args, $tabs);

}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	/*
	do your validation
	
	if(something){
		$value = $value;
	}elseif(somthing else){
		$error = true;
		$value = $existing_value;
		$field['msg'] = 'your custom error message';
	}
	*/
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function
?>