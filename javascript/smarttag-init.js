jQuery(document).ready(function($) {
	loadFliqz = function() {
		var playerID = $(this).attr("data-fliqz-playerid");
		var guid = $(this).attr("data-fliqz-guid");
		var querystring = {
			width: $(this).attr("data-fliqz-width"),
			height: $(this).attr("data-fliqz-height")
		}
		for(key in querystring) if(typeof(querystring[key]) != 'string') delete querystring[key];
		if(querystring.width || querystring.height)
			querystring = '?'+$.param(querystring);
		else querystring = '';
		var containerID = $(this).attr("id");
		if(typeof(containerID) != 'string') {
			containerID = 'fliqz_'+(new Date).getTime();
			$(this).attr("id", containerID);
		}
		
		var url = "http://services.fliqz.com/smart/20100401/applications/"+playerID+"/assets/"+guid+"/containers/"+containerID+"/smarttag.js"+querystring;
		$.getScript(url);	
	}
	$("div.fliqz-smarttag:not(:has(*))").each(loadFliqz).on("load", loadFliqz);
});
