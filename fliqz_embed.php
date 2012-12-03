<?php
/**
 * @package Fliqz
 * @version 0.2
 */
/*
Plugin Name: Fliqz Embed
Description: Provides Fliqz asset embedding in the WYSIWYG editor
Author: Media Devils Inc.
Version: 0.2
Author URI: http://mediadevils.com
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

register_activation_hook(__FILE__, array('FliqzEmbed', 'dependencies'));

if(FliqzEmbed::dependencies() && is_plugin_active(basename(dirname(__FILE__))."/fliqz_embed.php"))
	add_action('init', array('FliqzEmbed', 'init'));

class FliqzEmbed {
	public static $api;
	public static $replacements = array(
		"[video]" => "#video", // Display the smarttag
		"[thumbnail]" => "#thumbnail", // Display the default thumbnail
	
		// Core asset attributes
		"[title]" => "title",
		"[description]" => "description",
		"[id]" => "id",
		"[date]" => "date",
		"[playerid]" => "playerID",
		
		// Content values start with @
		"[state]" => "@state",
		"[approved]" => "@approved",
		"[medium]" => "@medium",
		"[expression]" => "@expression",
		"[duration]" => "@duration",
		"[height]" => "@height",
		"[width]" => "@width",
		"[keywords]" => "@keywords"
	);
	
	public static $query = array(
		"videoid" => null,
		"query" => null
	);
	
	public static function dependencies() {
		if(!is_plugin_active(basename(dirname(__FILE__))."/fliqz.php")) {
			deactivate_plugins(__FILE__);
			return false;
		}
		
		return true;
	}

	public static function init() {
		if(!$apikey = get_option('fliqz_apikey')) return false;
		self::$api = new Fliqz($apikey);
		
		add_shortcode('fliqzasset', array('FliqzEmbed', 'shortcode_fliqzasset'));
		add_shortcode('fliqzsearch', array('FliqzEmbed', 'shortcode_fliqzsearch'));
		
		add_action("parse_request", array('FliqzEmbed', "process_request"));
		add_filter("query_vars", array('FliqzEmbed', "add_query_vars"));
	}
	
	public static function shortcode_fliqzasset($attributes, $content = '', $tag = 'fliqzasset') {
		$attributes = shortcode_atts(array(
			'id' => null,
			'width' => null,
			'height' => null,
			'script' => 'true'
		), $attributes);
		
		if(is_null($attributes["id"])) { // Try to get the asset id from the request variables, if present
			if(array_key_exists("videoid", self::$query) && strlen(self::$query["videoid"]))
				$attributes["id"] = self::$query["videoid"];
			else
				return "<!-- Missing Fliqz GUID -->";
		}
		
		$transientID = 'fliqzasset-'.md5($attributes["id"]);
		if(false === ($asset = get_transient($transientID))) {
			$asset = self::$api->getAsset($attributes["id"]);
			set_transient($transientID, $asset, get_option('fliqz_cache_time')?:600);
		}
		
		if(!$asset)
			return "<!-- Fliqz GUID does not exist -->";
		
		if($content && strlen($content)) {
			$content = self::process_replacements($asset, $content, $attributes);
		} else {
			$playerID = get_option('fliqz_override_playerid')?:$asset->playerID;
			$guid = $asset->id;
			$width = $attributes["width"];
			$height = $attributes["height"];
			$script = $attributes["script"] == "true";
			$content = self::display_smarttag($playerID, $guid, $width, $height, $script);
		}
		
		return $content;
	}
	
	public static function shortcode_fliqzsearch($attributes, $content = '', $tag = 'fliqzsearch') {
		$standard = array(
			'query' => null,
			'fields' => null,
			'order' => null,
			'page' => null,
			'pagesize' => null,
			'width' => null,
			'height' => null,
			'script' => null
		);
		
		if(is_array($attributes))
			$categories = array_diff_key($attributes, $standard);
		else
			$categories = null;
	
		$attributes = shortcode_atts($standard, $attributes);
		
		if(is_null($attributes["query"])) {
			if(array_key_exists("query", self::$query) && strlen(self::$query["query"]))
				$attributes["query"] = self::$query["query"];
		}
		
		$transientID = 'fliqzsearch-'.md5(serialize(array(
			$attributes["query"],
			$attributes["fields"],
			$categories,
			$attributes["order"],
			$attributes["page"],
			$attributes["pagesize"]
		)));
		if(false === ($results = get_transient($transientID))) {
			$results = self::$api->getAssets(
				$attributes["query"],
				$attributes["fields"],
				$categories,
				$attributes["order"],
				$attributes["page"],
				$attributes["pagesize"]
			);
			$results->load();
			set_transient($transientID, $results, get_option('fliqz_cache_time')?:120);
		}
		
		if($results) {
			$assetformat = $content;
			$content = '';
			if(is_array($results->assets) && !empty($results->assets))
				foreach($results->assets as $asset) {
					$content .= self::process_replacements($asset, $assetformat, $attributes);
				}
		} else
			$content = "No results";
		
		return $content;
	}
	
	public static function process_replacements($asset, $content, $attributes) {
		foreach(self::$replacements as $search => $replace) {
			if($replace == "#video") {
				$playerID = get_option('fliqz_override_playerid')?:$asset->playerID;
				$guid = $asset->id;
				$width = $attributes["width"];
				$height = $attributes["height"];
				$script = $attributes["script"] == "true";
				$content = str_replace($search, self::display_smarttag($playerID, $guid, $width, $height, $script), $content);
			} elseif($replace == "#thumbnail") {
				if(is_a($asset->content->thumbnail, "FliqzThumbnail"))
					$content = str_replace($search, $asset->content->thumbnail->url, $content);
				else
					$content = str_replace($search, "", $content);
			} elseif(substr($replace, 0, 1) == "@") {
				$replace = substr($replace, 1);
				$content = str_replace($search, $asset->content->$replace, $content);
			} else
				$content = str_replace($search, $asset->$replace, $content);
		}
		
		return $content;
	}
	
	public static function display_smarttag($playerID, $guid, $width = null, $height = null, $script = true) {
		ob_start();
		include(FLIQZ_PLUGIN_PATH.'templates/smarttag.tpl');
		return ob_get_clean();
	}
	
	public static function process_request($wp) {
		foreach(self::$query as $key => &$value)
			if(array_key_exists($key, $wp->query_vars))
				$value = $wp->query_vars[$key];
	}
	
	public static function add_query_vars($vars) {
		foreach(array_keys(self::$query) as $key)
			if(!array_key_exists($key, $vars))
				$vars[] = $key;
		return $vars;
	}
}
