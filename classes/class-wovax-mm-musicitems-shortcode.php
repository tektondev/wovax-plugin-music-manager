<?php

/**
 * Abstract class for custom shortcode
 * 
 * @author Wovac LLc, Danial Bleile
 * @uses WOVAX_MM_Shortcode | class-wovax-mm-shortcode.php
 * @version 0.0.1
 */
 
require_once 'class-wovax-mm-shortcode.php';
 
class WOVAX_MM_Musicitems_Shortcode extends WOVAX_MM_Shortcode{
	
	//@var string Slug for shortcode
	protected $slug = 'musicitems';
	
	protected $library;

	
	public function __construct( $library ){
		
		$this->library = $library;
		
		
	} // end __construct
	
	/**
	 * Get HTML for shortcode display
	 * 
	 * @param array $atts Array of settings passed with the shortcode
	 * @return string HTML output of the shortcode
	 */
	public function get_shortcode_display( $atts ){
		
		$html = '';
		
		if ( ! empty( $atts['ids'] ) ){
			
			$ids = explode( ',' , $atts['ids'] );
			
			$query = $this->library->get_id_query( $ids );
			
			$music_array = $this->library->get_music_from_query( $query );
			
			
			ob_start();
			
			foreach( $music_array as $music ){
				
				include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-item.php';
				
			} // end foreach
			
			$html .= '<div class="wovax-mm-musicitems-list">' . ob_get_clean() . '</div>';
			
		} // end if
		
		return $html;
		
	} // end get_shortcode_display
	
}