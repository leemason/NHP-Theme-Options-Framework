<?php

/*
  Plugin Name: NHP Theme Options Framework
  Plugin URI: http://leemason.github.com/NHP-Theme-Options-Framework/
  Description: A simple, easily extendable theme options class (well its actually a whole bunch of classes, but hey lets not confuse things, were making things simpler here). Uses the built in Settings API for WordPress. And uses Custom error handling for validation classes. This allows each tab to count its errors and display warnings for the user.
  Version: 1.0.3
  Author: Lee Mason
  Author URI: http://leemason.github.com/
  License: GPL
  Copyright: Lee Mason
 */


if (!class_exists('NHP_Options')) {
    require_once( dirname(__FILE__) . '/options/options.php' );
}