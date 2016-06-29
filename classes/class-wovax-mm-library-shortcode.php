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
	//protected $query;
	
	//@var WOVAX_MM_Library Instance
	protected $library;
	
	public function __construct( $music_category , $library ){
		
		$this->music_category = $music_category;
		
		//$this->query = $query;
		
		$this->library = $library;
		
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
	 * Get HTML for the A-Z index header section
	 *
	 * @param array $alpha_posts Array of posts with first letter of title as key
	 */
	protected function get_az_index_html( $atts ){
		
		$alpha = range( 'A', 'Z' );
		
		$query = $this->library->get_az_query();
		
		$music_array = $this->library->get_music_from_query( $query );
		
		$alpha_music = $this->get_music_alpha( $music_array );
		
		$az_sections_html = $this->get_music_sets_html( $alpha_music );
	
		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-library-az-index.php';
		$html .= ob_get_clean();
		
		return $html;
		
	} // end get_az_index_html
	
	
	/**
	 * Get music sets html
	 * 
	 * @param array $alpha_music Array of WOVAX_MM_Music instances by alpha
	 * @return string HTML for music items
	 */
	protected function get_music_sets_html( $alpha_music ){
		
		$html = '';
		
		foreach( $alpha_music as $char => $music_posts ){
			
			$music_items_html = $this->library->get_music_items_html( $music_posts );
			
			$title = $char;
			
			ob_start();
			include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-set-header.php';
			$music_items_header = ob_get_clean();
			
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
		
		$query = $this->library->get_search_query( $s );
		
		//if ( is_user_logged_in() ){
			
			//var_dump( $query );
			
		//}
		
		$music_array = $this->library->get_music_from_query( $query );
		
		return $this->library->get_music_items_html( $music_array );
		
	} 
	
	
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
		
		$query = $this->library->get_az_query();  
		
		$music_array = $this->library->get_music_from_query( $query );
		
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