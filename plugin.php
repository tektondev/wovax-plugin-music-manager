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
			 
			 add_action( 'admin_enqueue_scripts', array( $this , 'add_admin_scripts' ), 11 );
			 
			 // Get save class and create new instance
			 require_once 'classes/class-wovax-mm-save-post.php'; 
		 	 $save_post = new WOVAX_MM_Save_Post( $music );
			 
			 // Save post
			 add_action( 'save_post_music' , array( $save_post , 'save' ) );
			 
		 } else {
			 
			 add_action( 'wp_enqueue_scripts' , array( $this, 'add_public_scripts') );
			 
		 }// end if
		 
		 if ( isset( $_GET['wovax_mm_ajax'] ) ){
			 
			 require_once 'classes/class-wovax-mm-ajax.php';
			 $ajax = new WOVAX_MM_Ajax();
			 
			 add_filter( 'template_include', array( $ajax , 'get_template' ) , 99 );
			 
		 } // end if 
		 
	 } // end init
	 
	 
	 /**
	  * Add public scripts
	  */
	 public function add_public_scripts(){
		 
		 wp_enqueue_style( 'wovax_mm_public_style' , plugin_dir_url( __FILE__ ) . 'css/public-style.css', array() , WOVAX_Music_Manager::$version );
		 wp_enqueue_style( 'font_awesome' , plugin_dir_url( __FILE__ ) . 'font-awesome/css/font-awesome.min.css', array() , WOVAX_Music_Manager::$version );
		 
		 wp_enqueue_script( 'wovax_mm_public_script' , plugin_dir_url( __FILE__ ) . 'js/public-script.js', array() , WOVAX_Music_Manager::$version , true );
		 
	 } // end add_public_scripts
	 
	 
	 /**
	  * Add admin scripts
	  */
	 public function add_admin_scripts(){
		 
		 wp_enqueue_style( 'wovax_mm_admin_style' , plugin_dir_url( __FILE__ ) . 'css/admin-style.css', array() , WOVAX_Music_Manager::$version );
		 
	 } // end add_public_scripts
	 
	
} // end WOVAX_Music_Manager

$wovax_mm = WOVAX_Music_Manager::get_instance();