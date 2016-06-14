<?php
/**
 * Query response page
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */

class WOVAX_MM_Query {
	
	// @var array Basic query args
	protected $query_args = array(
		'post_type'      => 'music',
		'post_status'    => 'publish',
		'posts_per_page' => 20, 
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	
	
	public function do_query(){
		
		if ( isset( $_GET['query_type'] ) ){
			
			switch( $_GET['query_type'] ){
				
				case 'azindex':
					$this->do_azindex_query();
					break;
				
			} // end switch
			
		} else {
		} // end if
		
	} // end do_query
	
	
	public function do_azindex_query(){
		
		$query_args = array(
			'posts_per_page' => -1,
			'orderby'        => 'title',
		);
		
		if ( $tax_args = $this->get_tax_args() ){
			
			$query_args['tax_query'] = $tax_args;
			
		} // end if
		
		$query = $this->do_post_query( $query_args );
		
	} // end do_azindex_query
	
	
	protected function do_post_query( $args = array() ){
		
		$args = array_merge( $this->query_args , $args );
		
		$query = new WP_Query( $args );
		
		return $query;
		
	} // end do_post_query
	
	
	protected function get_tax_args(){
		
		if ( ! empty( $_GET['category'] ) ){
			
			$tax_query = array(
				  array(
					  'taxonomy' => 'wx_music_category',
					  'field'    => 'term_id',
					  'terms'    => sanitize_text_field( $_GET['category'] ),
				  ),
			);
			
			return $tax_query;
			
		} else {
			
			return false;
			
		}// end if
		
	} // end get_tax_args
	
	
	
	
	
	
	
	/**
	 * Do search and echo results
	 */
	protected function do_search(){
		
		if ( isset( $_GET['s'] ) ) {
			
			$s = sanitize_text_field( $_GET['s'] ); 
			
			$query = $this->get_basic_query( $s );
			$meta_query = $this->get_meta_query( $s );
			// start putting the contents in the new object
			$query->posts = array_merge( $query->posts, $meta_query->posts );
			// we also need to set post count correctly so as to enable the looping
			$query->post_count = count( $query->posts );
			
			if( count( $query->posts ) > 0 ){
				
				$this->the_music( $query );
				
			} else {
				
				$this->the_result_count( 0 );
				
			} // end if
			
		} else {
			
			$this->the_result_count( 0 );
			
		} // end if
		
	} // end do_search
	
	
	/**
	 * Get basic search WP Query
	 *
	 * @param string $s Search term
	 * @return array New instans of WP Query
	 */
	protected function get_basic_query( $s ){
		
		$query_args = $this->query_args;
		$query_args['s'] = $s;
		
		$query = new WP_Query( $query_args );
		
		return $query;
		
	} // end get_basic_query
	
	
	/**
	 * Get Meta search WP Query
	 *
	 * @param string $s Search term
	 * @return array New instans of WP Query
	 */
	protected function get_meta_query( $s ){
		
		$m_args = array(
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
		
		$query_args = $this->query_args;
		$query_args['meta_query'] = $m_args;
		
		$query = new WP_Query( $query_args );
		
		return $query;
		
	} // end get_basic_query
	
	/**
	 * Loop through query and echo music items
	 *
	 * @param array $query Instance of WP Query
	 */
	protected function the_music( $query ){
		
		$results = array();
		
		if ( $query->have_posts() ){
			
			while ( $query->have_posts() ){
			
				$query->the_post();
				
				if ( ! array_key_exists( $query->post->ID , $results ) ) {
				
					$music = new WOVAX_MM_Music();
					
					$music->set_settings( $query->post->ID );
					
					$music->set_post( $query->post );
					
					ob_start();
					
					include plugin_dir_path(  __FILE__ ) . 'inc/music-item.php';
					
					$results[ $query->post->ID ] = ob_get_clean();
				
				} // end if
				
			} // end while
			
		} // end if
		
		wp_reset_postdata();
		
		$this->the_result_count( count( $results ) );
		
		echo implode( '' , $results );
		
	} // end the_music
	
	
	/**
	 * Echo result count section
	 *
	 * @param int $c Count of results
	 */
	protected function the_result_count( $c ){
		
		echo '<div class="wovax-mm-library-result-count">Results (' . $c . ')</div>';
		
	} // end the_result_count
	
	
} // end WOVAX_MM_Search