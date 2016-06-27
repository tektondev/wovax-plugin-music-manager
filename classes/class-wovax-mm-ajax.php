<?php

/**
 * Abstract class for custom shortcode
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
class WOVAX_MM_Ajax {
	
	protected $library;
	
	
	public function __construct( $library ){
		
		$this->library = $library;
		
	}
	
	/**
	 * Get template to use
	 *
	 * @param string $template Existing set template
	 */
	public function get_template( $template ){
		
		switch( $_GET['wovax_mm_ajax'] ){
			
			case 'query':
				$template = plugin_dir_path( dirname( __FILE__ ) ). 'library-query.php';
				break;
			
			case 'search':
				$template = plugin_dir_path( dirname( __FILE__ ) ). 'search.php';
				break;
			
		} // end switch
		
		return $template;
		
	} // end get_template
	
	
	public function the_music_selector_search(){
		
		if ( isset( $_POST['s_term'] ) ){
			
			$s = sanitize_text_field( $_POST['s_term'] );
			
		} else {
			
			$s = '';
			
		} // end if
		
		$query = $this->library->get_search_query( $s );
	   
		 $music_array = $this->library->get_music_from_query( $query );
		
		 uasort( $music_array , array( $this->library , 'sort_search_array' ) );
		 
		 foreach( $music_array as $music ){
			 
			 include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-editor-selector-music-item.php';
			 
		 } // end foreach
		
		die();
		
	} // end the_music_selector_search
	
}
