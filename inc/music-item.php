<article class="wovax-mm-item">
	<div class="wovax-mm-item-titles">
		<div class="wovax-mm-title"><a href="<?php echo $music->get_link();?>"><?php echo $music->get_title();?></a></div>
        <div class="wovax-mm-subtitle"><?php echo $music->get_settings('_music_subtitle');?></div>
    </div>
    <ul>
    	<li><?php if( $music->get_settings( '_music_sheet' ) ):?><a href="<?php echo $music->get_settings( '_music_sheet' );?>"><i class="fa fa-music" aria-hidden="true"></i></a><?php endif;?></li>
        <li><?php if( $music->get_settings( '_music_mp3' ) ):?><a href="<?php echo $music->get_settings( '_music_mp3' );?>"><i class="fa fa-headphones" aria-hidden="true"></i></a><?php endif;?></li>
        <li><?php if( $music->get_settings( '_music_video' ) ):?><a href="<?php echo $music->get_settings( '_music_video' );?>"><i class="fa fa-video-camera" aria-hidden="true"></i></a><?php endif;?></li>
        <li><?php if( $music->get_settings( '_music_page' ) ):?><?php echo $music->get_settings( '_music_page' );?><?php endif;?></li>
    </ul>
</article>