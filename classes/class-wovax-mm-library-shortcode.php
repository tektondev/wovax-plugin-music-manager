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
	public $slug = 'musiclibrary';
	
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
	protected function query_music( $query_args = array() ){
		
		$music_posts = array();
		
		$q_args = array(
			'post_type'   => 'music',
			'post_status' => 'publish',
			'orderby'     => 'title', 
			'order'       => 'ASC'
		);
		
		$query_args = array_merge( $q_args , $query_args );
		
		$query = new WP_Query( $query_args );
		
		while ( $query->have_posts() ){
			
			$query->the_post();
			
			$music = new WOVAX_MM_Music();
			
			$music->set_settings( $query->post->ID );
			
			$music->set_post( $query->post );
			
			$music_posts[] = $music;
			
		} // end while
		
		return $music_posts;
		
	}
	
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
		
		$alpha_posts =  $this->get_all_music_alpha();
		
		$alpha = range( 'A', 'Z' );
		
		$az_sections_html = $this->get_az_listing_html( $alpha_posts , $atts );
	
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-az-index.php';
		$html .= ob_get_clean();
		
		return $html;
		
	} // end get_az_index_html
	
	
	/**
	 * List all music with letter headers
	 * 
	 * @param array $alpha_posts Array of posts with first letter of title as key 
	 */
	protected function get_az_listing_html( $alpha_posts , $atts ){
		
		$html = '';
		
		
		foreach( $alpha_posts as $char => $music_posts ){
			
			$music_items_html = '';
			
			foreach( $music_posts as $music ){
				
				ob_start();
				include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-item.php';
				$music_items_html .= ob_get_clean();
				
			} // end foreach
			
			ob_start();
			include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-az-set.php';
			$html .= ob_get_clean();
			
			//$html .= $posts_html;
			
		} // end foreach
		
		return $html;
		
	} // end get_az_listing_html
	
	
	/**
	 * Get HTML for the search section
	 *
	 * @param array $alpha_posts Array of posts with first letter of title as key
	 * @param array $atts Shortcode settings
	 */
	public function get_search_html( $atts  ){
		
		$args = array(
			'posts_per_page' => 10, 
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		
		$music_posts = $this->query_music( $args );
		
		$music_items_html = '';
		
		foreach( $music_posts as $music ){
				
			ob_start();
			include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-item.php';
			$music_items_html .= ob_get_clean();
			
		} // end foreach
		
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-search.php';
		$html = ob_get_clean();
		
		
		return $html;
		
		
	}
	
	
	
	
	
}