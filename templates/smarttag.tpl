<?php
if(!isset($script)) $script = true;
if($script) wp_enqueue_script('fliqz-smarttag');
?>
<div class="fliqz-smarttag" data-fliqz-playerid="<?php echo $playerID; ?>" data-fliqz-guid="<?php echo $guid; ?>"<?php if($width) { ?> data-fliqz-width="<?php echo $width; ?>"<?php } if($height) { ?> data-fliqz-height="<?php echo $height; ?>"<?php } ?>>&nbsp;</div>
