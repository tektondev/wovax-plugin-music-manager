<li>
	<div class="mm-title"><?php echo$music->get_title();?></div>
    <div class="mm-title"><?php echo $music->get_settings( '_music_subtitle' );?></div>
    <input type="hidden" value="<?php echo $music->get_id();?>" />
    <a href="#" class="mm-add-music"></a>
    <a href="#" class="mm-remove-music"></a>
</li>