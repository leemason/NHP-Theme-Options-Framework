<?php

/*
  changelog

  define dir and url before calling class, allows sections array to use framework links for builtin icons much easier
  changed import/export code to just a serialized array
  export options is now a feed url, with secret key validation for security
  allow import through file, or url
  javascript errors now adds class to input fields instead of inline styles
  options panel now loads the last tab viewed whenever accessing the page.
  added support for validation warnings (used by no html and no special chars validation, but extendable the same as errors)
  fixed error where using the upload field type and the wp_editor field type upload functionality by storing the original sentoeditor
  removed php4 construtor to save bytes as wp is now php5.2.4 and above
  moved default option adding to the init hook instead of admin_init for an earlier assignment
  fixed an isset error when setting default values, and reassigned the options array after setting defaults on first activation (function was adding the values, but the options array wasnt updated till after the page loads, so default options existed but were not displayed)
  fixed css issue with farbtastic popup color picker were it was displaying under the radio button set.
  fixed typo on tags select field in theme-options.php

 */

if (!class_exists('NHP_Options')) {

    /* if( file_exists(STYLESHEETPATH.'/options/options.php') ){
      define('NHP_OPTIONS_URL', trailingslashit(get_stylesheet_directory_uri()).'options/');
      }elseif( file_exists(TEMPLATEPATH.'/options/options.php') ){
      define('NHP_OPTIONS_URL', trailingslashit(get_template_directory_uri()).'options/');
      } */

    define('NHP_OPTIONS_URL', trailingslashit('/wp-content/plugins/NHP-Theme-Options-Framework/options/'));

    define('NHP_OPTIONS_DIR', trailingslashit(dirname(__FILE__)));

    class NHP_Options {

        /**
         * Class Constructor. Defines the args for the theme options class
         *
         * @since NHP_Options 1.0
         *
         * @param $array $args Arguments. Class constructor arguments.
         */
        function __construct($sections = array(), $args = array()) {

            $this->framework_url = 'http://leemason.github.com/NHP-Theme-Options-Framework/';
            $this->framework_version = '1.0.3';

            //get this path
            $this->dir = NHP_OPTIONS_DIR;
            //get this frameworks url for images,etc
            $this->url = NHP_OPTIONS_URL;

            //get field classes
            $this->get_fields();

            //get validation classes
            $this->get_validation();

            //get sections
            $this->sections = apply_filters('nhp-opts-sections', $sections);

            $defaults = array();

            $defaults['theme_dir'] = trailingslashit(get_stylesheet_directory());
            $defaults['theme_url'] = trailingslashit(get_stylesheet_directory_uri());

            $defaults['theme_data'] = get_theme_data($defaults['theme_dir'] . 'style.css');

            $defaults['theme_data']['short_name'] = strtolower(preg_replace('/ /', '_', $defaults['theme_data']['Title']));

            $defaults['opt_name'] = $defaults['theme_data']['short_name'];

            $defaults['parent_page'] = 'theme';

            $defaults['menu_title'] = __('Theme Options', 'nhp-opts');
            $defaults['page_title'] = $defaults['theme_data']['Title'] . __(' Theme Options', 'nhp-opts');
            $defaults['page_slug'] = 'nhp_theme_options';
            $defaults['page_cap'] = 'manage_options';

            $defaults['show_theme_info'] = true;
            $defaults['show_import_export'] = true;
            $defaults['dev_mode'] = false;
            $defaults['stylesheet_override'] = false;

            $defaults['footer_credit'] = sprintf(__('<span id="footer-thankyou">Options Panel created using the <a href="%s" target="_blank">NHP Theme Options Framework</a> Version %s</span>', 'nhp-opts'), $this->framework_url, $this->framework_version);


            foreach ($defaults['theme_data'] as $tkey => $tdata) {
                if (is_array($tdata))
                    $tdata = implode(', ', $tdata);
                $helpdata[] = '<p><strong>' . $tkey . '</strong> - ' . $tdata . '</p>';
            }//foreach

            $defaults['help_tabs'][] = array(
                'id' => 'nhp-opts-1',
                'title' => __('Theme Information', 'nhp-opts'),
                'content' => implode($helpdata)
            );

            $defaults['help_sidebar'] = __('', 'nhp-opts');




            $this->args = wp_parse_args($args, $defaults);
            $this->args = apply_filters('nhp-opts-args', $this->args);

            //setup the errors and warnings array for later
            $this->errors = array();
            $this->warnings = array();

            //set option with defaults
            add_action('init', array(&$this, '_set_default_options'));

            //options page
            add_action('admin_menu', array(&$this, '_options_page'));


            //register setting
            add_action('admin_init', array(&$this, '_register_setting'));

            //add the js for the error handling before the form
            add_action('nhp-opts-page-before-form', array(&$this, '_errors_js'), 1);

            //add the js for the warning handling before the form
            add_action('nhp-opts-page-before-form', array(&$this, '_warnings_js'), 2);

            //hook into the wp feeds for downloading the exported settings
            add_action('do_feed_nhpopts', array(&$this, '_download_options'), 1, 1);

            //get the options for use later on
            $this->options = get_option($this->args['opt_name']);
        }

//function

        /**
         * ->get(); This is used to return and option value from the options array
         *
         * @since NHP_Options 1.0.1
         *
         * @param $array $args Arguments. Class constructor arguments.
         */
        function get($opt_name) {
            return $this->options[$opt_name];
        }

//function

        /**
         * ->show(); This is used to echo and option value from the options array
         *
         * @since NHP_Options 1.0.1
         *
         * @param $array $args Arguments. Class constructor arguments.
         */
        function show($opt_name) {
            $option = $this->get($opt_name);
            if (!is_array($option)) {
                echo $option;
            }
        }

//function

        /**
         * Get Fields - requires all the built in classes for field use
         *
         * @since NHP_Options 1.0
         *
         */
        function get_fields() {

            //V1.0.0
            require_once($this->dir . 'fields/text/field_text.php');
            require_once($this->dir . 'fields/textarea/field_textarea.php');
            require_once($this->dir . 'fields/editor/field_editor.php');
            require_once($this->dir . 'fields/checkbox/field_checkbox.php');
            require_once($this->dir . 'fields/multi_checkbox/field_multi_checkbox.php');
            require_once($this->dir . 'fields/select/field_select.php');
            require_once($this->dir . 'fields/multi_select/field_multi_select.php');
            require_once($this->dir . 'fields/radio/field_radio.php');
            require_once($this->dir . 'fields/radio_img/field_radio_img.php');
            require_once($this->dir . 'fields/button_set/field_button_set.php');
            require_once($this->dir . 'fields/upload/field_upload.php');
            require_once($this->dir . 'fields/color/field_color.php');
            require_once($this->dir . 'fields/date/field_date.php');
            require_once($this->dir . 'fields/info/field_info.php');
            require_once($this->dir . 'fields/divide/field_divide.php');

            //V1.0.1
            require_once($this->dir . 'fields/pages_select/field_pages_select.php');
            require_once($this->dir . 'fields/pages_multi_select/field_pages_multi_select.php');
            require_once($this->dir . 'fields/posts_select/field_posts_select.php');
            require_once($this->dir . 'fields/posts_multi_select/field_posts_multi_select.php');
            require_once($this->dir . 'fields/tags_select/field_tags_select.php');
            require_once($this->dir . 'fields/tags_multi_select/field_tags_multi_select.php');
            require_once($this->dir . 'fields/cats_select/field_cats_select.php');
            require_once($this->dir . 'fields/cats_multi_select/field_cats_multi_select.php');
            require_once($this->dir . 'fields/menu_select/field_menu_select.php');
            require_once($this->dir . 'fields/menu_location_select/field_menu_location_select.php');
            require_once($this->dir . 'fields/post_type_select/field_post_type_select.php');
            require_once($this->dir . 'fields/checkbox_hide_below/field_checkbox_hide_below.php');
            require_once($this->dir . 'fields/select_hide_below/field_select_hide_below.php');

            do_action('nhp-opts-get-fields');
        }

//function

        /**
         * Get Validation - requires all the built in classes for validation use
         *
         * @since NHP_Options 1.0
         *
         */
        function get_validation() {

            //V1.0.0
            require_once($this->dir . 'validation/email/validation_email.php');
            require_once($this->dir . 'validation/no_html/validation_no_html.php');
            require_once($this->dir . 'validation/html/validation_html.php');
            require_once($this->dir . 'validation/html_custom/validation_html_custom.php');
            require_once($this->dir . 'validation/url/validation_url.php');
            require_once($this->dir . 'validation/numeric/validation_numeric.php');
            require_once($this->dir . 'validation/js/validation_js.php');

            //V1.0.1
            require_once($this->dir . 'validation/color/validation_color.php');
            require_once($this->dir . 'validation/date/validation_date.php');
            require_once($this->dir . 'validation/comma_numeric/validation_comma_numeric.php');
            require_once($this->dir . 'validation/no_special_chars/validation_no_special_chars.php');
            require_once($this->dir . 'validation/preg_replace/validation_preg_replace.php');
            require_once($this->dir . 'validation/str_replace/validation_str_replace.php');

            do_action('nhp-opts-get-validation');
        }

//function

        /**
         * Get default options into an array suitable for the settings API
         *
         * @since NHP_Options 1.0
         *
         */
        function _default_values() {

            $defaults = array();

            foreach ($this->sections as $k => $section) {

                if (isset($section['fields'])) {

                    foreach ($section['fields'] as $fieldk => $field) {

                        if (!isset($field['std'])) {
                            $field['std'] = '';
                        }

                        $defaults[$field['id']] = $field['std'];
                    }//foreach
                }//if
            }//foreach
            //fix for notice on first page load
            $defaults['last_tab'] = 0;

            return $defaults;
        }

        /**
         * Set default options on admin_init if option doesnt exist (theme activation hook caused problems, so admin_init it is)
         *
         * @since NHP_Options 1.0
         *
         */
        function _set_default_options() {
            if (!get_option($this->args['opt_name'])) {
                add_option($this->args['opt_name'], $this->_default_values());
            }
            $this->options = get_option($this->args['opt_name']);
        }

//function

        /**
         * Class Theme Options Page Function, creates main options page.
         *
         * @since NHP_Options 1.0
         */
        function _options_page() {
            $addpage = 'add_' . $this->args['parent_page'] . '_page';
            $this->page = $addpage(
                    $this->args['page_title'], $this->args['menu_title'], $this->args['page_cap'], $this->args['page_slug'], array(&$this, '_options_page_html')
            );
            add_action('admin_print_styles-' . $this->page, array(&$this, '_enqueue'));
            add_action('load-' . $this->page, array(&$this, '_load_page'));
        }

//function	

        /**
         * enqueue styles/js for theme page
         *
         * @since NHP_Options 1.0
         */
        function _enqueue() {

            wp_register_style(
                    $this->args['theme_data']['short_name'] . '-css', $this->url . 'css/options.css', array('farbtastic'), time(), 'all'
            );

            wp_register_style(
                    'nhp-opts-jquery-ui-css', apply_filters('nhp-opts-ui-theme', $this->url . 'css/jquery-ui-aristo/aristo.css'), '', time(), 'all'
            );


            if (!$this->args['stylesheet_override']) {
                wp_enqueue_style($this->args['theme_data']['short_name'] . '-css');
            }


            wp_enqueue_script(
                    $this->args['theme_data']['short_name'] . '-js', $this->url . 'js/options.js', array('jquery'), time(), true
            );

            do_action('nhp-opts-enqueue');


            foreach ($this->sections as $k => $section) {

                if (isset($section['fields'])) {

                    foreach ($section['fields'] as $fieldk => $field) {

                        if (isset($field['type'])) {

                            $field_class = 'NHP_Options_' . $field['type'];

                            if (class_exists($field_class) && method_exists($field_class, 'enqueue')) {
                                $enqueue = new $field_class();
                                $enqueue->enqueue();
                            }//if
                        }//if type
                    }//foreach
                }//if fields
            }//foreach
        }

//function

        /**
         * Download the options file, or display it
         *
         * @since NHP_Options 1.0.1
         */
        function _download_options() {
            if (!isset($_GET['secret']) || $_GET['secret'] != md5(AUTH_KEY . SECURE_AUTH_KEY)) {
                wp_die('Invalid Secret for options use');
                exit;
            }

            $backup_options = $this->options;
            $backup_options['nhp-opts-backup'] = '1';
            $content = '###' . serialize($backup_options) . '###';


            if (isset($_GET['action']) && $_GET['action'] == 'download_options') {
                header('Content-Description: File Transfer');
                header('Content-type: application/txt');
                header('Content-Disposition: attachment; filename="' . $this->args['theme_data']['short_name'] . '_options_' . date('d-m-Y') . '.txt"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                echo $content;
                exit;
            } else {
                echo $content;
                exit;
            }
        }

        /**
         * show page help
         *
         * @since NHP_Options 1.0
         */
        function _load_page() {

            //do admin head action for this page
            add_action('admin_head', array(&$this, 'admin_head'));

            //do admin footer text hook
            add_filter('admin_footer_text', array(&$this, 'admin_footer_text'));

            $screen = get_current_screen();

            if (is_array($this->args['help_tabs'])) {
                foreach ($this->args['help_tabs'] as $tab) {
                    $screen->add_help_tab($tab);
                }//foreach
            }//if

            if ($this->args['help_sidebar'] != '') {
                $screen->set_help_sidebar($this->args['help_sidebar']);
            }//if

            do_action('nhp-opts-load-page', $screen);
        }

//function

        /**
         * do action nhp-opts-admin-head for theme options page
         *
         * @since NHP_Options 1.0
         */
        function admin_head() {

            do_action('nhp-opts-admin-head', $this);
        }

        function admin_footer_text($footer_text) {
            return $this->args['footer_credit'];
        }

//function

        /**
         * Register Option for use
         *
         * @since NHP_Options 1.0
         */
        function _register_setting() {

            register_setting($this->args['opt_name'] . '_group', $this->args['opt_name'], array(&$this, '_validate_options'));

            foreach ($this->sections as $k => $section) {

                add_settings_section($k . '_section', $section['title'], array(&$this, '_section_desc'), $k . '_section_group');

                if (isset($section['fields'])) {

                    foreach ($section['fields'] as $fieldk => $field) {

                        if (isset($field['title'])) {

                            $th = (isset($field['sub_desc'])) ? $field['title'] . '<span class="description">' . $field['sub_desc'] . '</span>' : $field['title'];
                        } else {
                            $th = '';
                        }

                        add_settings_field($fieldk . '_field', $th, array(&$this, '_field_input'), $k . '_section_group', $k . '_section', $field); // checkbox
                    }//foreach
                }//if(isset($section['fields'])){
            }//foreach

            do_action('nhp-opts-register-settings');
        }

//function

        /**
         * Validate the Options options before insertion
         *
         * @since NHP_Options 1.0
         */
        function _validate_options($plugin_options) {

            set_transient('nhp-opts-saved', '1', 1000);

            if (!empty($plugin_options['import'])) {

                if ($plugin_options['import_code'] != '') {
                    $import = $plugin_options['import_code'];
                } elseif ($plugin_options['import_link'] != '') {
                    $import = wp_remote_retrieve_body(wp_remote_get($plugin_options['import_link']));
                }

                $imported_options = unserialize(trim($import, '###'));
                if (is_array($imported_options) && isset($imported_options['nhp-opts-backup']) && $imported_options['nhp-opts-backup'] == '1') {
                    $imported_options['imported'] = 1;
                    return $imported_options;
                }
            }


            if (!empty($plugin_options['defaults'])) {
                $plugin_options = $this->_default_values();
                return $plugin_options;
            }//if set defaults
            //validate fields (if needed)
            $plugin_options = $this->_validate_values($plugin_options, $this->options);

            if ($this->errors) {
                set_transient('nhp-opts-errors', $this->errors, 1000);
            }//if errors

            if ($this->warnings) {
                set_transient('nhp-opts-warnings', $this->warnings, 1000);
            }//if errors

            do_action('nhp-opts-options-validate', $plugin_options, $this->options);


            unset($plugin_options['defaults']);
            unset($plugin_options['import']);
            unset($plugin_options['import_code']);
            unset($plugin_options['import_link']);

            return $plugin_options;
        }

//function

        /**
         * Validate values from options form (used in settings api validate function)
         * calls the custom validation class for the field so authors can override with custom classes
         *
         * @since NHP_Options 1.0
         */
        function _validate_values($plugin_options, $options) {
            foreach ($this->sections as $k => $section) {

                if (isset($section['fields'])) {

                    foreach ($section['fields'] as $fieldk => $field) {
                        $field['section_id'] = $k;

                        if (!isset($plugin_options[$field['id']]) || $plugin_options[$field['id']] == '') {
                            continue;
                        }

                        //force validate of custom filed types

                        if (isset($field['type']) && !isset($field['validate'])) {
                            if ($field['type'] == 'color') {
                                $field['validate'] = 'color';
                            } elseif ($field['type'] == 'date') {
                                $field['validate'] = 'date';
                            }
                        }//if

                        if (isset($field['validate'])) {
                            $validate = 'NHP_Validation_' . $field['validate'];
                            if (class_exists($validate)) {
                                $validation = new $validate($field, $plugin_options[$field['id']], $options[$field['id']]);
                                $plugin_options[$field['id']] = $validation->value;
                                if (isset($validation->error)) {
                                    $this->errors[] = $validation->error;
                                }
                                if (isset($validation->warning)) {
                                    $this->warnings[] = $validation->warning;
                                }
                                continue;
                            }//if
                        }//if


                        if (isset($field['validate_callback']) && function_exists($field['validate_callback'])) {

                            $callbackvalues = call_user_func($field['validate_callback'], $field, $plugin_options[$field['id']], $options[$field['id']]);
                            $plugin_options[$field['id']] = $callbackvalues['value'];
                            if (isset($callbackvalues['error'])) {
                                $this->errors[] = $callbackvalues['error'];
                            }//if
                            if (isset($callbackvalues['warning'])) {
                                $this->warnings[] = $callbackvalues['warning'];
                            }//if
                        }//if
                    }//foreach
                }//if(isset($section['fields'])){
            }//foreach
            return $plugin_options;
        }

//function

        /**
         * HTML OUTPUT.
         *
         * @since NHP_Options 1.0
         */
        function _options_page_html() {

            echo '<div class="wrap">';
            echo '<div id="' . apply_filters('nhp-opts-page-icon-id', 'icon-themes') . '" class="icon32"><br/></div>';
            echo '<h2 id="nhp-opts-heading">' . get_admin_page_title() . '</h2>';
            echo (isset($this->args['intro_text'])) ? $this->args['intro_text'] : '';

            do_action('nhp-opts-page-before-form');

            echo '<form method="post" action="options.php" enctype="multipart/form-data" id="nhp-opts-form-wrapper">';
            settings_fields($this->args['opt_name'] . '_group');
            echo '<input type="hidden" id="last_tab" name="' . $this->args['opt_name'] . '[last_tab]" value="' . $this->options['last_tab'] . '" />';

            echo '<div id="nhp-opts-header">';
            echo '<input type="submit" name="submit" value="' . __('Save Changes', 'nhp-opts') . '" class="button-primary" />';
            echo '<input type="submit" name="' . $this->args['opt_name'] . '[defaults]" value="' . __('Reset to Defaults', 'nhp-opts') . '" class="button-secondary" />';
            echo '<div class="clear"></div><!--clearfix-->';
            echo '</div>';

            if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('nhp-opts-saved') == '1') {
                if (isset($this->options['imported']) && $this->options['imported'] == 1) {
                    echo '<div id="nhp-opts-imported">' . __('<strong>Settings Imported!</strong>', 'nhp-opts') . '</div>';
                } else {
                    echo '<div id="nhp-opts-save">' . __('<strong>Settings Saved!</strong>', 'nhp-opts') . '</div>';
                }
                delete_transient('nhp-opts-saved');
            }
            echo '<div id="nhp-opts-save-warn">' . __('<strong>Settings have changed!, you should save them!</strong>', 'nhp-opts') . '</div>';
            echo '<div id="nhp-opts-field-errors">' . __('<strong><span></span> error(s) were found!</strong>', 'nhp-opts') . '</div>';

            echo '<div id="nhp-opts-field-warnings">' . __('<strong><span></span> warning(s) were found!</strong>', 'nhp-opts') . '</div>';

            echo '<div class="clear"></div><!--clearfix-->';

            echo '<div id="nhp-opts-sidebar">';
            echo '<ul id="nhp-opts-group-menu">';
            foreach ($this->sections as $k => $section) {
                $icon = (!isset($section['icon'])) ? '<img src="' . $this->url . 'img/glyphicons/glyphicons_019_cogwheel.png" /> ' : '<img src="' . $section['icon'] . '" /> ';
                echo '<li id="' . $k . '_section_group_li" class="nhp-opts-group-tab-link-li">';
                echo '<a href="javascript:void(0);" id="' . $k . '_section_group_li_a" class="nhp-opts-group-tab-link-a" data-rel="' . $k . '">' . $icon . $section['title'] . '</a>';
                echo '</li>';
            }

            echo '<li class="divide">&nbsp;</li>';

            do_action('nhp-opts-after-section-menu-items', $this);

            if ($this->args['show_import_export'] == true) {
                echo '<li id="import_export_default_section_group_li" class="nhp-opts-group-tab-link-li">';
                echo '<a href="javascript:void(0);" id="import_export_default_section_group_li_a" class="nhp-opts-group-tab-link-a" data-rel="import_export_default"><img src="' . $this->url . 'img/glyphicons/glyphicons_082_roundabout.png" /> ' . __('Import / Export', 'nhp-opts') . '</a>';
                echo '</li>';
                echo '<li class="divide">&nbsp;</li>';
            }//if

            if (file_exists($this->args['theme_dir'] . 'README.html')) {
                echo '<li id="read_me_default_section_group_li" class="nhp-opts-group-tab-link-li">';
                echo '<a href="javascript:void(0);" id="read_me_default_section_group_li_a" class="nhp-opts-group-tab-link-a custom-tab" data-rel="read_me_default"><img src="' . $this->url . 'img/glyphicons/glyphicons_071_book.png" /> ' . __('Documentation', 'nhp-opts') . '</a>';
                echo '</li>';
            }//if

            if ($this->args['show_theme_info'] == true) {
                echo '<li id="theme_info_default_section_group_li" class="nhp-opts-group-tab-link-li">';
                echo '<a href="javascript:void(0);" id="theme_info_default_section_group_li_a" class="nhp-opts-group-tab-link-a custom-tab" data-rel="theme_info_default"><img src="' . $this->url . 'img/glyphicons/glyphicons_195_circle_info.png" /> ' . __('Theme Information', 'nhp-opts') . '</a>';
                echo '</li>';
            }//if

            if (isset($this->args['support_url'])) {
                echo '<li id="support_link_default_section_group_li" class="nhp-opts-group-tab-link-li">';
                echo '<a href="' . $this->args['support_url'] . '" id="support_link_default_section_group_li_a" class="custom-tab support-link" target="_blank"><img src="' . $this->url . 'img/glyphicons/glyphicons_050_link.png" /> ' . __('Support', 'nhp-opts') . '</a>';
                echo '</li>';
            }//if

            if ($this->args['dev_mode'] == true) {
                echo '<li id="dev_mode_default_section_group_li" class="nhp-opts-group-tab-link-li">';
                echo '<a href="javascript:void(0);" id="dev_mode_default_section_group_li_a" class="nhp-opts-group-tab-link-a custom-tab" data-rel="dev_mode_default"><img src="' . $this->url . 'img/glyphicons/glyphicons_195_circle_info.png" /> ' . __('Dev Mode Info', 'nhp-opts') . '</a>';
                echo '</li>';
            }//if

            echo '</ul>';
            echo '</div>';

            echo '<div id="nhp-opts-main">';
            foreach ($this->sections as $k => $section) {
                echo '<div id="' . $k . '_section_group' . '" class="nhp-opts-group-tab">';
                do_settings_sections($k . '_section_group');
                echo '</div>';
            }




            if ($this->args['show_import_export'] == true) {
                echo '<div id="import_export_default_section_group' . '" class="nhp-opts-group-tab">';
                echo '<h3>' . __('Import / Export Options', 'nhp-opts') . '</h3>';

                echo '<h4>' . __('Import Options', 'nhp-opts') . '</h4>';

                echo '<p><a href="javascript:void(0);" id="nhp-opts-import-code-button" class="button-secondary">Import from file</a> <a href="javascript:void(0);" id="nhp-opts-import-link-button" class="button-secondary">Import from URL</a></p>';

                echo '<div id="nhp-opts-import-code-wrapper">';

                echo '<div class="nhp-opts-section-desc">';

                echo '<p class="description" id="import-code-description">' . apply_filters('nhp-opts-import-file-description', __('Input your backup file below and hit Import to restore your sites options from a backup.', 'nhp-opts')) . '</p>';

                echo '</div>';

                echo '<textarea id="import-code-value" name="' . $this->args['opt_name'] . '[import_code]" class="large-text" rows="8"></textarea>';

                echo '</div>';


                echo '<div id="nhp-opts-import-link-wrapper">';

                echo '<div class="nhp-opts-section-desc">';

                echo '<p class="description" id="import-link-description">' . apply_filters('nhp-opts-import-link-description', __('Input the URL to another sites options set and hit Import to load the options from that site.', 'nhp-opts')) . '</p>';

                echo '</div>';

                echo '<input type="text" id="import-link-value" name="' . $this->args['opt_name'] . '[import_link]" class="large-text" value="" />';

                echo '</div>';



                echo '<p id="nhp-opts-import-action"><input type="submit" id="nhp-opts-import" name="' . $this->args['opt_name'] . '[import]" class="button-primary" value="' . __('Import', 'nhp-opts') . '"> <span>' . apply_filters('nhp-opts-import-warning', __('WARNING! This will overwrite any existing options, please proceed with caution!', 'nhp-opts')) . '</span></p>';
                echo '<div id="import_divide"></div>';

                echo '<h4>' . __('Export Options', 'nhp-opts') . '</h4>';
                echo '<div class="nhp-opts-section-desc">';
                echo '<p class="description">' . apply_filters('nhp-opts-backup-description', __('Here you can copy/download your themes current option settings. Keep this safe as you can use it as a backup should anything go wrong. Or you can use it to restore your settings on this site (or any other site). You also have the handy option to copy the link to yours sites settings. Which you can then use to duplicate on another site', 'nhp-opts')) . '</p>';
                echo '</div>';

                echo '<p><a href="javascript:void(0);" id="nhp-opts-export-code-copy" class="button-secondary">Copy</a> <a href="' . add_query_arg(array('feed' => 'nhpopts', 'action' => 'download_options', 'secret' => md5(AUTH_KEY . SECURE_AUTH_KEY)), site_url()) . '" id="nhp-opts-export-code-dl" class="button-primary">Download</a> <a href="javascript:void(0);" id="nhp-opts-export-link" class="button-secondary">Copy Link</a></p>';
                $backup_options = $this->options;
                $backup_options['nhp-opts-backup'] = '1';
                $encoded_options = '###' . serialize($backup_options) . '###';
                echo '<textarea class="large-text" id="nhp-opts-export-code" rows="8">';
                print_r($encoded_options);
                echo '</textarea>';
                echo '<input type="text" class="large-text" id="nhp-opts-export-link-value" value="' . add_query_arg(array('feed' => 'nhpopts', 'secret' => md5(AUTH_KEY . SECURE_AUTH_KEY)), site_url()) . '" />';

                echo '</div>';
            }





            if ($this->args['show_theme_info'] == true) {
                echo '<div id="theme_info_default_section_group' . '" class="nhp-opts-group-tab">';
                echo '<h3>' . $this->args['theme_data']['Title'] . '</h3>';
                echo '<div class="nhp-opts-section-desc">';
                echo '<p class="nhp-opts-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'nhp-opts') . '<a href="' . $this->args['theme_data']['URI'] . '" target="_blank">' . $this->args['theme_data']['URI'] . '</a></p>';
                echo '<p class="nhp-opts-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'nhp-opts') . $this->args['theme_data']['Author'] . '</p>';
                echo '<p class="nhp-opts-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'nhp-opts') . $this->args['theme_data']['Version'] . '</p>';
                echo '<p class="nhp-opts-theme-data description theme-description">' . $this->args['theme_data']['Description'] . '</p>';
                echo '<p class="nhp-opts-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'nhp-opts') . implode(', ', $this->args['theme_data']['Tags']) . '</p>';
                do_action('nhp-opts-after-theme-info', $this->args['theme_data']);
                echo '</div>';
                echo '</div>';
            }

            if (file_exists($this->args['theme_dir'] . 'README.html')) {
                echo '<div id="read_me_default_section_group' . '" class="nhp-opts-group-tab">';
                echo nl2br(file_get_contents($this->args['theme_dir'] . 'README.html'));
                echo '</div>';
            }//if

            if ($this->args['dev_mode'] == true) {
                echo '<div id="dev_mode_default_section_group' . '" class="nhp-opts-group-tab">';
                echo '<h3>' . __('Dev Mode Info', 'nhp-opts') . '</h3>';
                echo '<div class="nhp-opts-section-desc">';
                echo '<textarea class="large-text" rows="24">' . print_r($this, true) . '</textarea>';
                echo '</div>';
                echo '</div>';
            }


            do_action('nhp-opts-after-section-items', $this);

            echo '<div class="clear"></div><!--clearfix-->';
            echo '</div>';
            echo '<div class="clear"></div><!--clearfix-->';

            echo '<div id="nhp-opts-footer">';

            if (isset($this->args['share_icons'])) {
                echo '<div id="nhp-opts-share">';
                foreach ($this->args['share_icons'] as $link) {
                    echo '<a href="' . $link['link'] . '" title="' . $link['title'] . '" target="_blank"><img src="' . $link['img'] . '"/></a>';
                }
                echo '</div>';
            }

            echo '<input type="submit" name="submit" value="' . __('Save Changes', 'nhp-opts') . '" class="button-primary" />';
            echo '<input type="submit" name="' . $this->args['opt_name'] . '[defaults]" value="' . __('Reset to Defaults', 'nhp-opts') . '" class="button-secondary" />';
            echo '<div class="clear"></div><!--clearfix-->';
            echo '</div>';

            echo '</form>';

            do_action('nhp-opts-page-after-form');

            echo '<div class="clear"></div><!--clearfix-->';
            echo '</div><!--wrap-->';
        }

//function

        /**
         * JS to display the errors on the page
         *
         * @since NHP_Options 1.0
         */
        function _errors_js() {

            if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('nhp-opts-errors')) {
                $errors = get_transient('nhp-opts-errors');
                $section_errors = array();
                foreach ($errors as $error) {
                    $section_errors[$error['section_id']] = (isset($section_errors[$error['section_id']])) ? $section_errors[$error['section_id']] : 0;
                    $section_errors[$error['section_id']]++;
                }


                echo '<script type="text/javascript">';
                echo 'jQuery(document).ready(function(){';
                echo 'jQuery("#nhp-opts-field-errors span").html("' . count($errors) . '");';
                echo 'jQuery("#nhp-opts-field-errors").show();';

                foreach ($section_errors as $sectionkey => $section_error) {
                    echo 'jQuery("#' . $sectionkey . '_section_group_li_a").append("<span class=\"nhp-opts-menu-error\">' . $section_error . '</span>");';
                }

                foreach ($errors as $error) {
                    echo 'jQuery("#' . $error['id'] . '").addClass("nhp-opts-field-error");';
                    echo 'jQuery("#' . $error['id'] . '").closest("td").append("<span class=\"nhp-opts-th-error\">' . $error['msg'] . '</span>");';
                }
                echo '});';
                echo '</script>';
                delete_transient('nhp-opts-errors');
            }
        }

//function

        /**
         * JS to display the warnings on the page
         *
         * @since NHP_Options 1.0.3
         */
        function _warnings_js() {

            if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' && get_transient('nhp-opts-warnings')) {
                $warnings = get_transient('nhp-opts-warnings');
                $section_warnings = array();
                foreach ($warnings as $warning) {
                    $section_warnings[$warning['section_id']] = (isset($section_warnings[$warning['section_id']])) ? $section_warnings[$warning['section_id']] : 0;
                    $section_warnings[$warning['section_id']]++;
                }


                echo '<script type="text/javascript">';
                echo 'jQuery(document).ready(function(){';
                echo 'jQuery("#nhp-opts-field-warnings span").html("' . count($warnings) . '");';
                echo 'jQuery("#nhp-opts-field-warnings").show();';

                foreach ($section_warnings as $sectionkey => $section_warning) {
                    echo 'jQuery("#' . $sectionkey . '_section_group_li_a").append("<span class=\"nhp-opts-menu-warning\">' . $section_warning . '</span>");';
                }

                foreach ($warnings as $warning) {
                    echo 'jQuery("#' . $warning['id'] . '").addClass("nhp-opts-field-warning");';
                    echo 'jQuery("#' . $warning['id'] . '").closest("td").append("<span class=\"nhp-opts-th-warning\">' . $warning['msg'] . '</span>");';
                }
                echo '});';
                echo '</script>';
                delete_transient('nhp-opts-warnings');
            }
        }

//function

        /**
         * Section HTML OUTPUT.
         *
         * @since NHP_Options 1.0
         */
        function _section_desc($section) {

            $id = rtrim($section['id'], '_section');

            echo '<div class="nhp-opts-section-desc">' . $this->sections[$id]['desc'] . '</div>';
        }

//function

        /**
         * Field HTML OUTPUT.
         *
         * Gets option from options array, then calls the speicfic field type class - allows extending by other devs
         *
         * @since NHP_Options 1.0
         */
        function _field_input($field) {


            if (isset($field['callback']) && function_exists($field['callback'])) {
                $value = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';
                do_action('nhp-opts-before-field', $field, $value);
                call_user_func($field['callback'], $field, $value);
                do_action('nhp-opts-after-field', $field, $value);
                return;
            }

            if (isset($field['type'])) {

                $field_class = 'NHP_Options_' . $field['type'];

                if (class_exists($field_class)) {
                    $value = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';
                    do_action('nhp-opts-before-field', $field, $value);
                    $render = new $field_class($field, $value);
                    $render->render();
                    do_action('nhp-opts-after-field', $field, $value);
                }//if
            }//if $field['type']
        }

//function
    }

    //class
}//if
?>