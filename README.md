# NHP Theme Options V1.0.6 #

Theme options framework which uses the  [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API"), Custom Error/Validation Handling, Custom Field/Validation Types (which are easily extendable), and import/export functionality.

## Donate to the Framework ##

[![Donate to the framework](https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif "Donate to the framework")](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GWS9DVBAWP4L4)

## Simple Usage ##


Simply include the ```nhp-options.php``` file in your themes ```functions.php``` file, like so:

```php
get_template_part('nhp', 'options');
```

Then change the settings as written in the ```nhp-options.php``` file.

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