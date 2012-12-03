<?php
class FliqzTag {
	protected static $api;

	public static function init() {
		if(!$apikey = get_option('fliqz_apikey')) return false;
		self::$api = new Fliqz($apikey);
		
		add_action('wp_enqueue_scripts', array('FliqzTag', 'enqueue_frontend'));
		add_action('admin_enqueue_scripts', array('FliqzTag', 'enqueue_frontend'));
	}
	
	public static function video($guid) {
		$transientID = 'fliqzasset-'.md5($guid);
		if(false === ($asset = get_transient($transientID))) {
			$asset = self::$api->getAsset($guid);
		}
		if($asset) {
			$playerID = get_option('fliqz_override_playerid')?:$asset->playerID;
			$guid = $asset->id;
			include(FLIQZ_PLUGIN_PATH.'templates/smarttag.tpl');
		}
	}
	
	public static function enqueue_frontend() {
		wp_register_script("fliqz-smarttag", plugins_url(basename(dirname(__FILE__)).'/javascript/smarttag-init.js', dirname(__FILE__)), array('jquery'));
	}
}
