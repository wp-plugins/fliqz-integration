<?php
/**
 * @package Fliqz
 * @version 0.2
 */
/*
Plugin Name: Fliqz
Description: Provides simplified access to the Fliqz RESTful search services.
Author: Media Devils Inc.
Version: 0.2
Author URI: http://mediadevils.com
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define('FLIQZ_PLUGIN_PATH', plugin_dir_path(__FILE__));
if(file_exists(dirname(__FILE__)."/api/Fliqz.php"))
	require_once("api/Fliqz.php");
elseif(file_exists(dirname(__FILE__)."/fliqz.phar"))
	require_once("fliqz.phar");
else {
	deactivate_plugins(FLIQZ_PLUGIN_PATH.'fliqz.php');
	return;
}

require_once(FLIQZ_PLUGIN_PATH.'fliqz_tag.php');
add_action('init', array('FliqzTag', 'init'));

require_once(FLIQZ_PLUGIN_PATH.'fliqz_admin.php');
add_action('init', array('FliqzAdmin', 'init'));

require_once(FLIQZ_PLUGIN_PATH.'fliqz_embed.php');
