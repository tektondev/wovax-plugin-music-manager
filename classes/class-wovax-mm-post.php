<?php

/**
 * Abstract class for a custom post type to handle basic 
 * post methods and properties
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
 
abstract class WOVAX_MM_Post {
	
	//@var string Slug for post type
	protected $slug;
	
	//@var array Labels for the post type
	protected $labels;
	
	//@var array Args for registering the post type
	protected $args = array();
	
	//@var array Custom fields/post_meta used for post type & default ie. _field_name => default value
	protected $fields = array();
	
	//@var array Set custom fields for the post type based on $fields
	protected $settings = array();
	
	
	//@return string|null Slug for registering custom post type
	public function get_slug(){
		
		return $this->slug;
		
	} // end get_slug
	
	
	//@return array|null Labels for registering custom post type
	public function get_labels(){
		
		return $this->labels;
		
	} // end get_labels
	
	
	//@return array Args for registering custom post type
	public function get_args(){
		
		return $this->args;
		
	} // end get_args
	
	
	//@return array Fields for custom post type
	public function get_fields(){
		
		return $this->fields;
		
	} // end get_field
	
	
	/**
	 * Get all settigns or a specific one
	 * 
	 * @param string|null Specific setting to query
	 * @return mixed All settings or specific value
	 */
	public function get_settings( $setting = false ){
		
		// If setting is given
		if ( $setting ){
			
			// Check if is set
			if ( array_key_exists( $setting , $this->settings ) ){
				
				// Return setting
				return $this->settings[ $setting ];
				
			} else {
				
				// If not set return empty string
				return '';
				
			} // end if
		
		// If no setting given
		} else {
			
			// Return all settings
			return $this->settings;
			
		} // end if
		
	} // end  
	
	
	/**
	 * Set custom post type settings from $fields
	 * 
	 * @param int WP Post ID
	 * @param array Defaults to use if not set
	 */
	public function set_settings( $post_id , $defautls = array() ){
		
		$fields = $this->get_fields();
		
		// Loop through each field and check for value
		foreach( $fields as $key => $default_value ){
			
			$setting = get_post_meta( $post_id , $key , true );
			
			// if not empty - "" returned by get_post_meta
			if ( ( '' !== $setting ) ){ 
				
				// and to settings property
				$this->settings[ $key ] = $setting; 
			
			// if has default give
			} else if ( array_key_exists( $key , $defautls ) ){ 
				
				// and to settings property
				$this->settings[ $key ] = $defaults[ $key ];
				
			} else {
				
				// set to default
				$this->settings[ $key ] = $default_value;
				
			} // end if
			
		} // end foreach
		
	}  // end set_settings
	
	
	/**
	 * Set post type settings from $_POST and call clean method
	 * to sanitize values
	 */
	public function set_save_settings(){
		
		// Check if sanity function exits, otherwise do nothing
		if ( method_exists( $this , 'get_clean_settings' ) ){
		
			$fields = $this->get_fields();
			
			$settings = array();
			
			// Loop through each field and check for value
			foreach( $fields as $key => $default_value ){
				
				if ( isset( $_POST[ $key ] ) ){
					
					$settings[ $key ] = $_POST[ $key ];
					
				} // end if
				
			} // end foreach
			
			$this->settings = $this->get_clean_settings( $settings ); 
			
		} // end if
		
	} // end set_save_settings
	
	
	/**
	 * Register the post type in WordPress. Get the slug, labels, & args
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
		
		register_post_type( $this->get_slug() , $args );
		
	} // end register
	
	
	/**
	 * Check if current post type matches slug and call
	 * the edit_form method if it does.
	 * 
	 * @param object WP Post object
	 */
	public function the_editor( $post ){
		
		if ( ( $this->get_slug() == $post->post_type ) && method_exists( $this , 'editor_form' ) ){
			
			$this->editor_form( $post );
			
		} // end if
		
	} // end the_editor	
	
	
	/**
	 * Check post type and call enqueue function
	 * if it exists
	 */
	public function add_edit_post_scripts(){
		
		global $post_type;
		 
		 if( ( $this->get_slug() == $post_type ) && method_exists( $this , 'edit_post_scripts') ) {
			 
			 $this->edit_post_scripts();
	
		 } // end if
		
	} // end add_edit_post_scripts
	
}