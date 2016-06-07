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
		
		$html = '<section id="wovax-mm-library">';
		
			$html .= 'library here';
		
		$html .= '</section>';
		
		return $html;
		
	} // end get_shortcode_display
	
	
	/**
	 * Query music post types
	 * 
	 * @param array $query_args Additional query args
	 * @return array Set of WP Post objects
	 */
	protected function query_music( $query_args = array() ){
		
		$q_args = array(
			'post_type' => 'music',
			'post_status' => 'publish',
		);
		
		$query_args = array_merge( $q_args , $query_args );
		
		$query = 
		
	}
	
	
	
	
	
}