<form id="wovax-mm-library">
	<div class="wovax-mm-category"><label>Filter:</label>
    	<select name="music_category">
        	<option value="">No Filters</option>
        	<?php foreach( $music_terms as $term_id => $term_name ):?>
        	<option value="<?php echo $term_id;?>"><?php echo $term_name;?></option>
            <?php endforeach;?>
        </select>
    </div>
	<nav class="wovax-mm-library-nav">
    	<a href="#" class="active" data-section="az-index-wrap">A-Z Index</a><a data-section="search-wrap" href="#">Search</a>
    </nav>
	<?php echo $display_html;?>
</form>