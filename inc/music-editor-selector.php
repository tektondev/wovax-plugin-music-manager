
<div id="wovax-mm-selector" style="top:-9999rem;position:absolute;">
	<div>
    	<div id="wovax-music-selector-form">
        	<header>
            	<a href="#" class="mm-close-modal"></a>
            </header>
        	<div class="mm-columns-wrap">
                <section class="mm-column">
                    <fieldset>
                    <div class="mm-selector-field">
                        <input type="text" name="s_term" value="" placeholder="Search Term" /><input type="button" class="mm-selector-search" value="GO" />
                    </div>
                    <div class="mm-selector-field mm-category">
                        <label>Filter: </label><select name="music_category">
                        	<?php echo $music_terms;?>
                        </select>
                    </div>
                    </fieldset>
                    <ul id="wovax-music-selector-results" class="mm-selector-results mm-items connected-sortable">
                        <?php echo $music_html;?>
                    </ul>
                    
                </section>
                <section class="mm-column">
                	<header>
                    <div class="mm-title">
                        <input type="text" name="music_title" value="" placeholder="Add Title" />
                    </div>
                    <h3>Selected Music</h3>
                    </header>
                    <ul id="wovax-music-selector-items" class="mm-items connected-sortable">
                    </ul>
                </section>
            </div>
            <footer>
            	<input type="submit" value="Insert Music" class="mm-close-modal" />
                <input type="button" value="Cancel" class="mm-close-modal" />
                <div class="mm-shortcode">
                    <span>Shortcode: </span><span id="wovax-music-selector-shortcode" ></span>
                </div>
            </footer>
        </div>
    </div>
    <script>var wovax_mm_ajax_url = '<?php echo get_home_url();?>'</script>
</div>
<div id="wovax-mm-selector-bg" class="mm-close-modal" ></div>