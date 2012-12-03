=== Fliqz Integration ===
Contributors: justin.mediadevils
Donate link: http://mediadevils.com
Tags: fliqz, vbrick, video
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 0.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Fliqz Integration plugin provides you with basic integration with the vBrick (formerly Fliqz) video hosting service.

== Description ==

The Fliqz Integration plugin provides you with basic integration with the vBrick (formerly Fliqz) video hosting service.
While this plugin does provide full access to the Fliqz search API, its main purpose is to enable the embedding of search
results and videos into your WordPress pages.

This plugin will require that your Fliqz service account be of the Silver package or higher, in order to access the
Fliqz API.

== Installation ==

1. Upload the fliqz plugin directory to your `/wp-content/plugins/` directory.
2. Activate the `Fliqz` and `Fliqz Embed` plugins through your 'Plugins' menu.
3. Configure your Fliqz API Key (Application ID) in the Fliqz Settings interface.

== Basic Usage ==

= Display an Asset =

`[fliqzasset id="a8bbdd79-29f3-4522-a0a6-233522aa6ff5"]`

= Display Information About an Asset = 

`[fliqzasset id="a8bbdd79-29f3-4522-a0a6-233522aa6ff5"]
	[video] # Displays the video
	[title] # Displays the video's title
[/fliqzasset]`

= Display Search Results =

`[fliqzsearch query="test"]
	* [title]
[/fliqzsearch]`

= Available Asset Parameters =

* `id` - Asset GUID
* `width` - Width in pixels
* `height` - Height in pixels
* `script` - Whether to include the smarttag script

= Available Search Parameters =

* `query` - Search query
* `fields` - Fields to search in ('all', 'keywords', or 'titledescription')
* `order` - Sort order ('title', 'date', 'rating', 'use', or 'share')
* `page` - Page to display
* `pagesize` - Number of items per page

** Asset parameters can be specified in search to customize asset display **

= Available Attributes =

* `[video]` - Display the video
* `[thumbnail]` - Default thumbnail URL
* `[title]` - Title
* `[description]` - Description
* `[id]` - Asset GUID
* `[date]` - Publication Date
* `[playerid]` - Default Player ID
* `[state]` - Publication State
* `[approved]` - Approval State
* `[medium]` - Asset Type
* `[expression]` - Display Type
* `[duration]` - Length in seconds
* `[width]` - Width in pixels
* `[height]` - Height in pixels
* `[keywords]` - Keywords`

== Changelog ==

= 0.2 =

Made plugin's function independent of its directory.
Added confirmation message that plugin settings were saved.

= 0.1 =

Initial release

== Frequently Asked Questions ==

None yet. Please send any questions to support [at] mediadevils [dot] com.

== Upgrade Notice ==

No upgrades yet available.

== Screenshots ==

No screenshots yet available.
