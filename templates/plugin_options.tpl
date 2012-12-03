<div id="wrap">
	<h2>Fliqz Options</h2>
<?php
if($settingsUpdated) {
?>
	<p>Settings have been updated.</p>
<?php
}
?>
	<form name="fliqz_plugin_options" method="POST">
		<input type="hidden" name="fliqz_options_submit_hidden" value="Y" />
<?php
foreach(self::$plugin_options as $option_name => $friendly_name) {
?>
		<p>
			<label for="option_<?php echo $option_name; ?>"><?php echo $friendly_name; ?></label>
			<input id="option_<?php echo $option_name; ?>" name="option_<?php echo $option_name; ?>" type="text" value="<?php echo get_option($option_name); ?>" />
		</p>
<?php
}
foreach(self::$plugin_options_select as $option_name => $details) {
	$current_value = get_option($option_name);
?>
		<p>
			<label for="option_<?php echo $option_name; ?>"><?php echo $details["name"]; ?></label>
			<select id="option_<?php echo $option_name; ?>" name="option_<?php echo $option_name; ?>">
<?php
	foreach($details["values"] as $value) {
?>
				<option value="<?php echo $value; ?>"<?php if($value == $current_value) {?>selected<?php } ?>><?php echo $value; ?></option>
<?php
	}
?>
			</select>
		</p>
<?php
}
?>
		<input type="submit" class="button-primary" />
	</form>
</div>
