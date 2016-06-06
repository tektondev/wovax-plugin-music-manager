<?php
/**
 * Class for saving custom post type meta fields
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */

class WOVAX_MM_Save_Post {
	
	// @var WOVAX_MM_Post Instance of WOVAX_MM_Post
	protected $post_type;
	
	// @var string Nonce action used in varification
	protected $nonce_action = 'edit-post';
	
	// @var string Nonce name used in varification
	protected $nonce_name = 'edit_post_nonce';
	
	/**
	 * Set properties on initialization
	 *
	 * @param WOVAX_MM_Post Instance of WOVAX_MM_Post
	 */
	public function __construct( $post_type ){
		
		$this->post_type = $post_type;
		
	} // end __construct
	
	
	/**
	 * Called on save_post action. handle cleaning and saving post custom field data
	 *
	 * @param int $post_id ID of post being saved
	 */
	public function save( $post_id ){
		
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->nonce_name ] ) && wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action . '_' . $post_id ) ) ? 'true' : 'false';
	 
		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			
			return;
			
		} // end if
		
		// Set the settings during save action - also sanitizes values.
		$this->post_type->set_save_settings();
		
		$settings = $this->post_type->get_settings();
		
		foreach( $settings as $key => $value ){
			
			update_post_meta( $post_id, $key, $value );
			
		} // end foreach
		
	} // end save
	
	
} // end WOVAX_MM_Save_Post