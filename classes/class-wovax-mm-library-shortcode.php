<?php

/**
 * Abstract class for custom shortcode
 * 
 * @author Wovac LLc, Danial Bleile
 * @uses WOVAX_MM_Shortcode | class-wovax-mm-shortcode.php
 * @version 0.0.1
 */
 
require_once 'class-wovax-mm-shortcode.php';
 
class WOVAX_MM_Library_Shortcode extends WOVAX_MM_Shortcode{
	
	//@var string Slug for shortcode
	protected $slug = 'musiclibrary';
	
	//@var WOVAX_MM_Music_Category Instance
	protected $music_category;
	
	//@var WOVAX_MM_Query Instance
	protected $query;
	
	public function __construct( $music_category , $query ){
		
		$this->music_category = $music_category;
		
		$this->query = $query;
		
	} // end __construct
	
	/**
	 * Get HTML for shortcode display
	 * 
	 * @param array $atts Array of settings passed with the shortcode
	 * @return string HTML output of the shortcode
	 */
	public function get_shortcode_display( $atts ){
		
		$display_html = '';
		
		if ( $atts['display'] == 'paged' ){
				
			// get paged version
			
		} else {
			
			$display_html .= $this->get_az_index_html( $atts );
			
		} // end if
		
		$display_html .= $this->get_search_html( $atts );
		
		$music_terms = $this->music_category->get_term_names();
		
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-wrapper.php';
		$html = ob_get_clean();
		
		return $html;
		
	} // end get_shortcode_display
	
	
	/**
	 * Query music post types
	 * 
	 * @param array $query_args Additional query args
	 * @return array Set of WP Post objects
	 */
	/*protected function query_music( $query_args = array() ){
		
		$music_posts = array();
		
		$q_args = array(
			'post_type'   => 'music',
			'post_status' => 'publish',
			'orderby'     => 'title', 
			'order'       => 'ASC'
		);
		
		$query_args = array_merge( $q_args , $query_args );
		
		$query = new WP_Query( $query_args );
		
		$music_posts = $this->get_query_alpha( $query );
		
		return $music_posts;
		
	}*/
	
	/**
	 * Get all the music posts and organize them into
	 * an array by their first letter
	 * @return array Array of $posts by letter
	 */
	public function get_all_music_alpha(){
		
		$alpha = range( 'A', 'Z' );
		
		$alpha_posts = array();
		
		$music_posts = $this->query_music( array( 'posts_per_page' => -1 ) );
		
		foreach( $music_posts as $music ){
		
			$f_char = strtoupper( substr( $music->get_title() , 0, 1) );
			
			$alpha_posts[$f_char][] = $music; 
			
		} // end foreach
		
		return $alpha_posts;
		
	} // end get_all_music_alpha
	
	
	/**
	 * Get HTML for the A-Z index header section
	 *
	 * @param array $alpha_posts Array of posts with first letter of title as key
	 */
	protected function get_az_index_html( $atts ){
		
		$alpha = range( 'A', 'Z' );
		
		$query = new WP_Query($this->get_az_query_args() );
		
		usort($query->posts, function( $a , $b) {
			
   			return strnatcmp($a->post_title , $b->post_title);
			
		}); // sort titles natrually
		
		$music_array = $this->get_music_from_query( $query );
		
		$alpha_music = $this->get_music_alpha( $music_array );
		
		$az_sections_html = $this->get_music_sets_html( $alpha_music );
	
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-az-index.php';
		$html .= ob_get_clean();
		
		return $html;
		
	} // end get_az_index_html
	
	
	/**
	 * Get music items html
	 * 
	 * @param array $music Array of WOVAX_MM_Music instances
	 * @return string HTML for music items
	 */
	protected function get_music_items_html( $music_array ){
		
		$items_html = '';
		
		if ( ! empty( $music_array ) ){
		
		foreach( $music_array as $music ){
			
			ob_start();
			include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-item.php';
			$items_html .= ob_get_clean();
			
		} // end foreach
		
		} else {
			
			$items_html = '<div class="wovax-mm-no-results">Sorry, we couldn\'t find any items that match your search. Please try again.</div>';
			
		} // end if
		
		return $items_html;
		
	} // end get_music_items_html
	
	
	/**
	 * Get music sets html
	 * 
	 * @param array $alpha_music Array of WOVAX_MM_Music instances by alpha
	 * @return string HTML for music items
	 */
	protected function get_music_sets_html( $alpha_music ){
		
		$html = '';
		
		foreach( $alpha_music as $char => $music_posts ){
			
			$music_items_html = $this->get_music_items_html( $music_posts );
			
			ob_start();
			include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-az-set.php';
			$html .= ob_get_clean();
			
			//$html .= $posts_html;
			
		} // end foreach
		
		return $html;
		
	} // end get_music_sets_html
	
	
	/**
	 * Get HTML for the search section
	 *
	 * @param array $alpha_posts Array of posts with first letter of title as key
	 * @param array $atts Shortcode settings
	 */
	public function get_search_html( $atts  ){
		
		$music_items_html = $this->get_search_items_html();
		
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-search.php';
		$html = ob_get_clean();
		
		return $html;
		
	}
	
	
	/**
	 * Get html for search items
	 */
	public function get_search_items_html( $atts = array() ){
		
		if ( ! empty( $_GET['music_term']  ) ){
			
			$s = sanitize_text_field( $_GET['music_term'] );
			
		} else {
			
			$s = false;
			
		} // end if
		
		$query_args = $this->get_search_query_args( $s );
		
		$meta_args = $this->get_search_query_args( $s , true );
		
		$query = new WP_Query( $query_args );
		
		$meta_query = new WP_Query( $meta_args );
		
		// start putting the contents in the new object
		$query->posts = array_merge(  $meta_query->posts , $query->posts );
		// we also need to set post count correctly so as to enable the looping
		$query->post_count = count( $query->posts );
		
		$music_array = $this->get_music_from_query( $query );
		
		uasort( $music_array , array( $this , 'sort_search_array' ) );
		
		return $this->get_music_items_html( $music_array );
		
	} 
	
	public function sort_search_array( $a , $b ){
		
		return strcmp( $a->get_title() , $b->get_title() );
		
	} // end sort_search_array
	
	
	/**
	 * Handle request from library AJAX
	 */
	public function do_request(){
		
		if ( isset( $_GET['query_type'] ) ){
			
			switch( $_GET['query_type'] ){
				
				case 'azindex':
					$this->do_azindex_request();
					break;
				case 'search':
					$this->do_search_request();
					break;
			} // end switch
			
		} else {
		} // end if
		
	} // end do_request
	
	
	/**
	 * Do az index request from Ajax
	 */
	public function do_azindex_request(){
		
		$query = new WP_Query( $this->get_az_query_args() );
		
		$music_array = $this->get_music_from_query( $query );
		
		$alpha_music = $this->get_music_alpha( $music_array );
		
		$az_sections_html = $this->get_music_sets_html( $alpha_music );
		
		echo $this->get_tax_results_text();
		
		echo $az_sections_html;
		
	} // end do_azindex_query
	
	/**
	 * Do az index request from Ajax
	 */
	public function do_search_request(){
		
		echo $this->get_tax_results_text();
		
		echo $this->get_search_items_html();
		
	} // end do_azindex_query
	
	
	/**
	 * Get AZ query args
	 */
	protected function get_az_query_args(){
		
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
	 * Get search query args
	 */
	protected function get_search_query_args( $s , $is_meta = false ){
		
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
		
	} // end get_az_query_args
	
	
	/**
	 * Get Meta search WP Query
	 *
	 * @param string $s Search term
	 * @return array New instans of WP Query
	 */
	protected function get_meta_query( $s , $args ){
		
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
		
		$args['meta_query'] = $m_args;
		
		$query = new WP_Query( $query_args );
		
		return $query;
		
	} // end get_basic_query
	
	
	/**
	 * Convert WP_Query object to assoc array with
	 * Alpha keys
	 * 
	 * @param array $query WP_Query objects
	 * @return array Query objects with alpha keys
	 */
	protected function get_music_alpha( $music_array ){
		
		//( $music_array );
		
		$alpha_music = array();
		
		foreach( $music_array as $index => $music ){
		
			$f_char = strtoupper( substr( $music->get_title() , 0, 1) );
			
			$alpha_music[$f_char][] = $music; 
			
		} // end foreach
		
		return $alpha_music;
		
	} // end 
	
	
	/**
	 * Convert WP_Query object to assoc array with
	 * with WOVAX_MM_Music instances
	 * 
	 * @param array $query WP_Query objects
	 * @return array Instances of WOVAX_MM_Music
	 */
	protected function get_music_from_query( $query ){
		
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
	
	/**
	 * Get Tax text
	 */
	protected function get_tax_results_text(){
		
		$html = '';
		
		if ( ! empty( $_GET[ 'music_category' ] ) ){
			
			$html .= '<div class="wovax-mm-tax-mess">Results filtered to include only <span>' . $this->music_category->get_name( $_GET[ 'music_category' ] ) . '</span>. For more resutls try removing your filters above.</div>';
			
		} //
		
		return $html;
		
	} // end get_tax_results_text
	
	
	
	
	
}