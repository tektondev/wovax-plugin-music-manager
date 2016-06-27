<?php
class WOVAX_MM_Library {
	
	
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
	
	
	public function get_search_query( $s ){
		
		$query_args = $this->get_search_query_args( $s );
		
		$meta_args = $this->get_search_query_args( $s , true );
		
		$query = new WP_Query( $query_args );
		
		$meta_query = new WP_Query( $meta_args );
		
		// start putting the contents in the new object
		$query->posts = array_merge(  $meta_query->posts , $query->posts );
		// we also need to set post count correctly so as to enable the looping
		$query->post_count = count( $query->posts );
		
		return $query;
		
	} // end get_search_query
	
	
	/**
	 * Get search query args
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
		
		if ( ! empty( $_GET[ 'music_category' ] ) ){
			
			$tax_query = array(
				  array(
					  'taxonomy' => 'wx_music_category',
					  'field'    => 'term_id',
					  'terms'    => sanitize_text_field( $_GET[ 'music_category' ] ),
				  ),
			);
			
			return $tax_query;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end get_tax_args
	
	
	public function sort_search_array( $a , $b ){
		
		return strcmp( $a->get_title() , $b->get_title() );
		
	} // end sort_search_array
	
	
}