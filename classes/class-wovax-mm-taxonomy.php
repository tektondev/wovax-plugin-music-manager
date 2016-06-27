<?php
/**
 * Abstract class for a custom taxonomy type to handle basic 
 * methods and properties
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
class WOVAX_MM_Taxonomy {
	
	//@var string Slug for taxonomy
	protected $slug;
	
	//@var array Labels for the taxonomy
	protected $labels;
	
	//@var array Args for registering the taxonomy
	protected $args = array();
	
	//@var array Add to post types
	protected $post_types = array();
	
	
	//@return string|null Slug for registering custom taxonomy
	public function get_slug(){
		
		return $this->slug;
		
	} // end get_slug
	
	
	//@return array|null Labels for registering custom taxonomy
	public function get_labels(){
		
		return $this->labels;
		
	} // end get_labels
	
	
	//@return array Args for registering custom taxonomy
	public function get_args(){
		
		return $this->args;
		
	} // end get_args
	
	
	//@return array Custom post types to register taxonomy
	public function get_post_types(){
		
		return $this->post_types;
		
	} // end get_args
	
	
	/**
	 * Register the taxonomy in WordPress. Get the slug, labels, & args
	 * properties and combine them to register.
	 */
	public function register(){
		
		$args = $this->get_args();
		
		$label = $this->get_labels();
		
		/**
		 * Check if labels are set or is a string. Otherwise capitalize the first letter
		 * of the slug and use that for the label.
		 */ 
		if ( empty( $label ) ){
			
			$args['label'] = ucfirst( $this->get_slug() ); 
			
		} else if ( ! is_array( $label ) ) {
			
			$args['label'] = ucfirst( $label );
			
		} else {
			
			$args['labels'] = $label;
			
		} // end if
		
		register_taxonomy( $this->get_slug() , $this->get_post_types() , $args );
		
	} // end register
	
	/**
	 * Get taxonomy ids and names
	 *
	 * @return array Taxonomy names with ID as key
	 */
	public function get_term_names(){
		
		$term_objs = $this->get_terms();
		
		$terms = array();
		
		foreach( $term_objs as $term ){
			
			$terms[ $term->term_id ] = $term->name;
			
		} // end foreach
		
		return $terms;
		
	} // end get_names
	
	
	/**
	 * Get taxonomy term objects
	 *
	 * @return array Taxonomy Terms
	 */
	public function get_terms( $term_args = false ){
		
		$args = array( 'taxonomy' => $this->get_slug() );
		
		if ( $term_args ){
		
			$args= array_merge( $args , $term_args );
		
		} // end if
	
		$term_objs = get_terms( $args );
		
		return $term_objs;
		
	} // end get_terms
	/**
	 * Get term name from id
	 *
	 * @param int $id Term id
	 * @return string Term name
	 */
	public function get_name( $id ){
		
		$term = get_term( $id , $this->get_slug() );
		
		if ( $term ){
			
			return $term->name;
			
		} else {
			
			return '';
			
		} // end if
		
	} // end get_term_name
	
	
	/**
	 * Get taxonomy terms as select options
	 *
	 * @return string HTML options
	 */
	public function get_select_terms( $include_empty = true ){
		
		$options = '';
		
		$terms = $this->get_term_names();
		
		if ( $include_empty ){
		
			$options .= '<option value="">Select...</option>';
		
		} // end if
		
		foreach( $terms as $term_id => $term_name ){
			
			$options .= '<option value="' . $term_id . '">' . $term_name . '</option>';
			
		} // end foreach
		
		return $options;
		
	} // end get_select_terms
	
}