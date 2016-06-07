<div id="wovax-mm-subtitle">
	<div class="mm-field">
		<label>Add Subtitle: </label>
        <div class="mm-field-inner">
        	<input type="text" name="_music_subtitle" value="<?php echo $this->get_settings('_music_subtitle'); ?>" placeholder="Enter subtitle here" />
        </div>
    </div>
</div>
<fieldset id="wovax-mm-editor">
	<header>Music Links</header>
    <section>
        <div class="mm-field">
            <label>Link to Music Sheet</label>
            <input type="text" name="_music_sheet" value="<?php echo $this->get_settings('_music_sheet'); ?>" />
        </div>
        <div class="mm-field">
            <label>Link to MP3 file</label>
            <input type="text" name="_music_mp3" value="<?php echo $this->get_settings('_music_mp3'); ?>" />
        </div>
        <div class="mm-field">
            <label>Link to Video</label>
            <input type="text" name="_music_video" value="<?php echo $this->get_settings('_music_video'); ?>" />
        </div>
        <div class="mm-field">
            <label>Page #</label>
            <input type="text" name="_music_page" value="<?php echo $this->get_settings('_music_page'); ?>" />
        </div>
        <?php wp_nonce_field( 'edit-post_' . $post->ID , 'edit_post_nonce' ); ?>
    </section>
</fieldset>