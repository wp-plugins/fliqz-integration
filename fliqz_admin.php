<?php
class FliqzAdmin {
	public static $plugin_options = array(
		'fliqz_apikey' => 'Fliqz API Key',
		'fliqz_override_playerid' => 'Override Player ID',
		'fliqz_cache_time' => 'Seconds to cache Fliqz results'
	);
	
	public static $plugin_options_select = array(
	);
	
	public static function init() {
		foreach(self::$plugin_options as $option_name => $friendly_name)
			add_option($option_name);
		foreach(self::$plugin_options_select as $option_name => $details)
			add_option($option_name, $details["values"][0]);
		add_action('admin_menu', array(__CLASS__, 'plugin_menu'));
	}

	public static function plugin_menu() {
		add_options_page('Fliqz Options', 'Fliqz', 'manage_options', 'fliqz-options', array(__CLASS__, 'plugin_options'));
	}

	public static function plugin_options() {
		if(!current_user_can('manage_options'))
			wp_die(__('You do not have sufficient permissions to access this page.'));
		
		$settingsUpdated = false;
		if(array_key_exists("fliqz_options_submit_hidden", $_POST) && $_POST["fliqz_options_submit_hidden"] == "Y") {
			foreach(self::$plugin_options as $option_name => $friendly_name)
				if(array_key_exists("option_{$option_name}", $_POST))
					update_option($option_name, $_POST["option_{$option_name}"]);
			
			foreach(self::$plugin_options_select as $option_name => $details)
				if(array_key_exists("option_{$option_name}", $_POST) && in_array($_POST["option_{$option_name}"], $details["values"]))
					update_option($option_name, $_POST["option_{$option_name}"]);
			
			$settingsUpdated = true;
		}
		
		include(FLIQZ_PLUGIN_PATH.'templates/plugin_options.tpl');
	}
}
