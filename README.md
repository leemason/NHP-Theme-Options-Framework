# NHP Theme Options #

Theme options framework which uses the  [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API"), Custom Error/Validation Handling, Custom Field/Validation Types (which are easily extendable), and import/export functionality.

## Simple Usage ##

Simply require the *theme-options.php* file in your themes **functions.php** file, like so:

```php
require( dirname( __FILE__ ) . '/theme-options.php' );
```

Then change the settings as written in the **theme-options.php** file.

## Features ##

* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types: **text | textarea | tinymce | checkbox | multi_checkbox | radio | radio_img | select | multi_select | color | date | upload**
* Multple layout field types: **divider | info**
* Fields can be over-ridden with a callback function, for custom field types
* Easily extendable by creating Field Classes (more info in the [WIKI](https://github.com/leemason/NHP-Theme-Options-Framework/wiki "WIKI"))
* Built in Validation Classes: **email | url | numeric | html | html_custom | no_html | js**
* Easily extendable by creating Validation Classes (more in the [WIKI](https://github.com/leemason/NHP-Theme-Options-Framework/wiki "WIKI"))
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Multiple Hook Points for customisation
* Import / Export Functionality
* Easily add page help through the class
* Much more