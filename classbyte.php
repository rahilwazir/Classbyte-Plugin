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

// Directory
define('CB_DIR', plugin_dir_path(__FILE__));
define('CB_COOKIE_FILE', 'cb-api-cookie.txt');
define('CB_COOKIE_FILE_PATH', CB_DIR . 'classes/CB/' . CB_COOKIE_FILE);
define('CB_VIEWS', trailingslashit(CB_DIR . 'views'));
define('CB_TEMPLATES', trailingslashit(CB_DIR . 'cb_templates'));

// URLS
define('CB_URL', trailingslashit(plugins_url('', __FILE__)));
define('ASSETS_URL', trailingslashit(CB_URL . 'assets'));

include_once CB_DIR . 'autoload.php';
include_once CB_DIR . 'functions.php';
include_once CB_DIR . 'hooks.php';

$cb = new ClassByte();

register_activation_hook(__FILE__, array(__NAMESPACE__ . '\ClassByte', 'activation'));
register_deactivation_hook(__FILE__, array(__NAMESPACE__ . '\ClassByte', 'deactivation'));
register_uninstall_hook(__FILE__, array(__NAMESPACE__ . '\ClassByte', 'uninstall' ) );