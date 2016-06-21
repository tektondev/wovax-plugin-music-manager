<div class="wovax-mm-music-subheader">
<h4 class="wovax-mm-music-subtitle"><?php echo $this->get_settings('_music_subtitle');?></h4>
<ul>
    <?php if( $this->get_settings( '_music_sheet' ) ):?><li class="wovax-mm-item-sheet">
    	<a href="<?php echo $this->get_settings( '_music_sheet' );?>" target="_blank"><i class="fa fa-music" aria-hidden="true"></i></a>
        <span>Sheet</span>
	</li><?php endif;?>
    <?php if( $this->get_settings( '_music_mp3' ) ):?><li class="wovax-mm-item-mp3">
    	<a href="<?php echo $this->get_settings( '_music_mp3' );?>" target="_blank"><i class="fa fa-headphones" aria-hidden="true"></i></a>
        <span>MP3</span>
    </li><?php endif;?>
    <?php if( $this->get_settings( '_music_video' ) ):?><li class="wovax-mm-item-video">
    	<a href="<?php echo $this->get_settings( '_music_video' );?>" class="wovax-mm-modal-item"><i class="fa fa-video-camera" aria-hidden="true"></i></a>
        <span>Video</span>
        <textarea class="wovax-mm-modal-item-content">
			<?php global $wp_embed; echo $wp_embed->run_shortcode('[embed]' . $this->get_settings( '_music_video' ) . '[/embed]'); ?>
        </textarea>
    </li><?php endif;?>
	<?php if( $this->get_settings( '_music_page' ) ):?><li class="wovax-mm-item-page-n">
    	Page No. <?php echo $this->get_settings( '_music_page' );?>
    </li><?php endif;?>
</ul>
<a href="/music-library/"><span>&#8592;</span> Music Archive</a>
</div>