# NHP Theme Options V1.0.5 #

Theme options framework which uses the  [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API"), Custom Error/Validation Handling, Custom Field/Validation Types (which are easily extendable), and import/export functionality.

## Simple Usage ##


Simply require the *theme-options.php* file in your themes **functions.php** file, like so:

```php
require( dirname( __FILE__ ) . '/theme-options.php' );
```

Then change the settings as written in the **theme-options.php** file.

## Features ##

* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI")
* Multple layout field types: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI")
* Fields can be over-ridden with a callback function, for custom field types
* Easily extendable by creating Field Classes (more info in the [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI"))
* Built in Validation Classes: [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki title="View WIKI")
* Easily extendable by creating Validation Classes (more in the [View WIKI](/leemason/NHP-Theme-Options-Framework/wiki "View WIKI"))
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Custom Validation warning handling, including warning counts for each section, and custom styling for warning fields
* Multiple Hook Points for customisation
* Import / Export Functionality - including cross site importing of settings
* Easily add page help through the class
* Much more