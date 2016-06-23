<div id="wovax-mm-selector">
	<div>
    	<div id="wovax-music-selector-form">
        	<div class="mm-columns-wrap">
                <section class="mm-column">
                    <fieldset>
                    <div class="mm-selector-field">
                        <input type="text" name="s_term" value="" placeholder="Search Term" /><input type="submit" value="GO" />
                    </div>
                    </fieldset>
                    <ul id="wovax-music-selector-results" class="mm-selector-results mm-items connected-sortable">
                        <?php echo $music_html;?>
                    </ul>
                    
                </section>
                <section class="mm-column">
                    <h3>Selected Music</h3>
                    <ul id="wovax-music-selector-items" class="mm-items connected-sortable">
                    </ul>
                </section>
            </div>
            <footer>
            	<input type="submit" value="Insert Music" />
                <input type="button" value="Cancel" />
                <div class="mm-shortcode">
                    <span>Shortcode: </span><span id="wovax-music-selector-shortcode" ></span>
                </div>
            </footer>
        </div>
    </div>
    <script>var wovax_mm_ajax_url = '<?php echo get_home_url();?>'</script>
</div>