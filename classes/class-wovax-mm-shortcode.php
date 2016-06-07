<?php

/**
 * Abstract class for custom shortcode
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
abstract class WOVAX_MM_Shortcode {
	
	
	//@var string Slug for shortcode
	public $slug;
	
	
	//@return string|null Slug for registering shortcode
	public function get_slug(){
		
		return $this->slug;
		
	} // end get_slug
	
	
	
	
	//Register shortcode from properties
	public function register(){
		
		add_shortcode( $this->get_slug() , array( $this , 'do_shortcode' ) );
		
	} // end register
	
	
	public function do_shortcode( $atts , $content , $tag ){
		
		$html = '';
		
		if ( method_exists( $this , 'get_shortcode_display' ) ){
			
			$html .= $this->get_shortcode_display( $atts , $content , $tag );
			
		} // end if
		
		return $html;
		
	} // end do_shortcode
	
	
}