<?php
/*
Plugin Name: Wovax Music Manager
Plugin URI: https://www.wovax.com/
Description: This is the Wovax plugin for adding and categorizing music.
Version: 0.0.1
Author: Wovax, LLC.
Author URI: https://www.wovax.com/
*/

class WOVAX_Music_Manager {
	
	//@var string Version of Wovax Music Manager
	public static $version = '0.0.1';
	
	//@var object|null Instance of Wovax Music Manager
	public static $instance;
	

	/**
	 * Get the current instance or set it and return
	 * @return object current instance of WOVAX_Music_Manager
	 */
	 public static function get_instance(){
		 
		 if ( null == self::$instance ) {
			 
            self::$instance = new self;
			self::$instance->init();
			
        } // end if
 
        return self::$instance;
		 
	 } // end get_instance
	 
	 
	 /**
	  * Method called when plugin is initialized for hooks & filters
	  */
	 public function init(){
		 
		 require_once 'classes/class-wovax-mm-music.php'; 
		 $music = new WOVAX_MM_Music();
		 
		 require_once 'classes/class-wovax-mm-library-shortcode.php';
		 $library = new WOVAX_MM_Library_Shortcode();
		 
		 // Register post type
		 add_action( 'init' , array( $music , 'register' ) );
		 
		 // Add Shortcode
		 add_action( 'init' , array( $library , 'register' ) );
		 
		 // Add edit form to edit post page for music post type
		 add_action( 'edit_form_after_title' , array( $music , 'the_editor' ) );
		 
		 if ( is_admin() ){
			 
			 // Add admin css to Edit Music page
			 add_action( 'admin_print_scripts-post-new.php', array( $music , 'add_edit_post_scripts' ), 11 );
			 add_action( 'admin_print_scripts-post.php', array( $music , 'add_edit_post_scripts' ), 11 );
			 
			 // Get save class and create new instance
			 require_once 'classes/class-wovax-mm-save-post.php'; 
		 	 $save_post = new WOVAX_MM_Save_Post( $music );
			 
			 // Save post
			 add_action( 'save_post_music' , array( $save_post , 'save' ) );
			 
		 } // end if
		 
	 } // end init
	 
	
} // end WOVAX_Music_Manager

$wovax_mm = WOVAX_Music_Manager::get_instance();