<?php
/**
 * Plugin Name: ClassByte
 * Plugin URI: http://dev.classbyte.com/
 * Description: ClassByte plugin is the main central place to connect Wordpress and your Custom ClassByte integrated application together.
 * Version: 1.0
 * Author: Webxity Technologies
 * Author URI: http://webxity.com/
 * License: GPL2
 */

namespace CB;

define('CB_DIR', plugin_dir_path(__FILE__));
define('CB_VIEWS', trailingslashit(CB_DIR . 'views'));

include_once CB_DIR . 'functions.php';
include_once CB_DIR . 'autoload.php';

$cb = new ClassByte();