<?php
namespace CB;

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

include_once 'classes/CB/PostsPages.php';

global $wpdb;

// Remove all the form settings
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cb_%'" );

// Delete all posts/pages
PostsPages::deleteAll();