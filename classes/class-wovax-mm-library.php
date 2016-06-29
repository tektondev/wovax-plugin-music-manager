<?php
class WOVAX_MM_Library {
	
	/**
	 * Do query by set of ids
	 *
	 * @param array $ids Array of music ids
	 * @return WP_Query
	 */
	public function get_id_query( $ids ){
		
		$args = array(
			'posts_per_page' => -1,
			'orderby'        => 'post__in',
			'post_type'      => 'music',
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'post__in'       => $ids,
		);
		
		$query = new WP_Query( $args );
		
		return $query;
		
	} // end get_id_query
	
	
	/**
	 * Get search query from term
	 *
	 * @param string $s Search term
	 * @return WP_Query 
	 */
	public function get_search_query( $s ){
		
		$query_args = $this->get_search_query_args( $s );
		
		//if ( isset( $_GET['wovax_mm_ajax'] ) && is_user_logged_in() ) var_dump( $query_args );
		
		$meta_args = $this->get_search_query_args( $s , true );
		
		$query = new WP_Query( $query_args );
		
		$meta_query = new WP_Query( $meta_args );
		
		// start putting the contents in the new object
		$query->posts = array_merge(  $meta_query->posts , $query->posts );
		// we also need to set post count correctly so as to enable the looping
		$query->post_count = count( $query->posts );
		
		usort($query->posts, function( $a , $b) {
			
   			return strnatcmp($a->post_title , $b->post_title);
			
		}); // sort titles natrually
		
		return $query;
		
	} // end get_search_query
	
	
	/**
	 * Get az-index query
	 * 
	 * @return array WP_Query
	 */
	public function get_az_query(){
		
		$query = new WP_Query($this->get_az_query_args() );
		
		usort($query->posts, function( $a , $b) {
			
   			return strnatcmp($a->post_title , $b->post_title);
			
		}); // sort titles natrually
		
		return $query;
		
	} // end get_az_query
	
	
	/**
	 * Get search query args
	 * @param string $s Search term
	 * @param bool $is_meta Do meta search
	 * @return array WP Query args
	 */
	public function get_search_query_args( $s , $is_meta = false ){ 
		
		$args = array(
			'posts_per_page' => 20,
			'orderby'        => 'title',
			'post_type'      => 'music',
			'post_status'    => 'publish',
			'order'          => 'ASC',
		);
		
		if ( $tax_args = $this->get_tax_args() ){
			
			$args['tax_query'] = $tax_args;
			
		} // end if
		
		if ( $s ){
			
			if ( $is_meta ){
				
				$args['meta_query'] = array(
					'relation' => 'OR',
					array(
						'key'     => '_music_subtitle',
						'value'   => $s,
						'compare' => 'LIKE',
					),
					array(
						'key'     => '_music_page',
						'value'   => $s,
						'compare' => 'LIKE',
					),
				);
				
			} else {
				
				$args['s'] = $s;
				
			} // end if 
			
		} // end if
	
		return $args;
		
	} // end get_search_query_args
	
	
	/**
	 * Get AZ query args
	 * @return array WP Query args
	 */
	public function get_az_query_args(){
		
		$query_args = array(
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'post_type'      => 'music',
			'post_status'    => 'publish',
			'order'          => 'ASC',
		);
		
		if ( $tax_args = $this->get_tax_args() ){
			
			$query_args['tax_query'] = $tax_args;
			
		} // end if
		
		return $query_args;
		
	} // end get_az_query_args
	
	
	/**
	 * Convert WP_Query object to assoc array with
	 * with WOVAX_MM_Music instances
	 * 
	 * @param array $query WP_Query objects
	 * @return array Instances of WOVAX_MM_Music
	 */
	public function get_music_from_query( $query ){
		
		$music_posts = array();
		
		while ( $query->have_posts() ){
			
			$query->the_post();
			
			if ( array_key_exists( 'post-' . $query->post->ID , $music_posts ) ) {
				
				continue;
				
			} // end if
			
			$music = new WOVAX_MM_Music();
			
			$music->set_settings( $query->post->ID );
			
			$music->set_post( $query->post );
			
			$music_posts['post-' . $query->post->ID ] = $music;
			
		} // end while
		
		wp_reset_postdata();
		
		//( $music_posts );
		
		return $music_posts;
		
	} // end get_music_from_query
	
	
	/**
	 * Check if taxonomy is set
	 * @return array Taxonomy query array or false if not set
	 */
	public function get_tax_args(){
		
		$music_cat = false;
		
		if ( ! empty( $_GET[ 'music_category' ] ) ){
			
			$music_cat = sanitize_text_field( $_GET[ 'music_category' ] );
			
		} else if ( ! empty( $_POST[ 'music_category' ] ) ){
			
			$music_cat = sanitize_text_field( $_POST[ 'music_category' ] );
			
		} // end if
		
		if ( $music_cat ){
			
			$tax_query = array(
				  array(
					  'taxonomy' => 'wx_music_category',
					  'field'    => 'term_id',
					  'terms'    => $music_cat,
				  ),
			);
			
			return $tax_query;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end get_tax_args
	
	
	/**
	 * Get music items html
	 * 
	 * @param array $music Array of WOVAX_MM_Music instances
	 * @return string HTML for music items
	 */
	public function get_music_items_html( $music_array , $include_sorry = true ){
		
		$items_html = '';
		
		if ( ! empty( $music_array ) ){
		
			foreach( $music_array as $music ){
				
				ob_start();
				include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-item.php';
				$items_html .= ob_get_clean();
				
			} // end foreach
		
		} else if ( $include_sorry ) {
			
			$items_html = '<div class="wovax-mm-no-results">Sorry, we couldn\'t find any items that match your search. Please try again.</div>';
			
		} // end if
		
		return $items_html;
		
	} // end get_music_items_html
	
	
} // end WOVAX_MM_Library