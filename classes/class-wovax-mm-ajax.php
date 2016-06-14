<?php

/**
 * Abstract class for custom shortcode
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
class WOVAX_MM_Ajax {
	
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
	
}
