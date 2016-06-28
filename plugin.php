<?php
/*
Plugin Name: Wovax Music Manager
Plugin URI: https://www.wovax.com/
Description: This is the Wovax plugin for adding and categorizing music.
Version: 0.0.3
Author: Wovax, LLC.
Author URI: https://www.wovax.com/
*/

class WOVAX_Music_Manager {
	
	// @var string Version of Wovax Music Manager
	public static $version = '0.0.3';
	
	// @var object|null Instance of Wovax Music Manager
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
	  * 
	 
	 
	 /**
	  * Method called when plugin is initialized for hooks & filters
	  */
	 public function init(){
		 
		 register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
		 register_activation_hook( __FILE__, array( $this , 'wovax_mm_flush_rewrites') );
		 
		 require_once 'classes/class-wovax-mm-library.php';
		 $music_library = new WOVAX_MM_Library(); 
		 
		 require_once 'classes/class-wovax-mm-music-category.php';
		 $music_category = new WOVAX_MM_Music_Category();
		 
		 require_once 'classes/class-wovax-mm-music.php'; 
		 $music = new WOVAX_MM_Music();
		 
		 /*require_once 'classes/class-wovax-mm-query.php';
		 $query = new WOVAX_MM_Query();*/
		 
		 require_once 'classes/class-wovax-mm-library-shortcode.php';
		 $library = new WOVAX_MM_Library_Shortcode( $music_category , $music_library );
		 
		 require_once 'classes/class-wovax-mm-musicitems-shortcode.php';
		 $musicitems = new WOVAX_MM_Musicitems_Shortcode( $music_library );
		 
		 require_once 'classes/class-wovax-mm-ajax.php';
		 $ajax = new WOVAX_MM_Ajax( $music_library );
		 
		 // Register post type
		 add_action( 'init' , array( $music , 'register' ) );
		 
		 // Add Shortcode
		 add_action( 'init' , array( $library , 'register' ) );
		 
		 // Add Shortcode
		 add_action( 'init' , array( $musicitems , 'register' ) );
		 
		 // Add Taxonomy
		 add_action( 'init' , array( $music_category , 'register' ), 99 );
		 
		 // Add edit form to edit post page for music post type
		 add_action( 'edit_form_after_title' , array( $music , 'the_editor' ) );
		 
		 if ( is_admin() ){
			 
			 add_action( 'admin_enqueue_scripts', array( $this , 'add_admin_scripts' ), 11, 1 );
			 
			 require_once 'classes/class-wovax-mm-selector.php';
			 $music_selector = new WOVAX_MM_Selector( $music_library , $music_category );
			 add_action('init' , array( $music_selector , 'init' ) );
			 
			 
			 // Get save class and create new instance
			 require_once 'classes/class-wovax-mm-save-post.php'; 
		 	 $save_post = new WOVAX_MM_Save_Post( $music );
			 
			 // Save post
			 add_action( 'save_post_music' , array( $save_post , 'save' ) );
			 
			 add_action( 'wp_ajax_music_selector_search', array( $ajax , 'the_music_selector_search' ) );
			 
		 } else {
			 
			 add_action( 'wp_enqueue_scripts' , array( $this, 'add_public_scripts') );
			 
			 add_filter( 'the_content' , array( $music , 'add_subheader' ), 99 );
			 
		 }// end if
		 
		 if ( isset( $_GET['wovax_mm_ajax'] ) ){
			 
			 add_filter( 'template_include', array( $ajax , 'get_template' ) , 99 );
			 
		 } // end if 
		 
	 } // end init
	 
	 
	 /**
	  * Add public scripts
	  */
	 public function add_public_scripts(){
		 
		 wp_enqueue_style( 
		 	'wovax_mm_public_style' , 
			plugin_dir_url( __FILE__ ) . 'css/public-style.css', 
			array() , 
			WOVAX_Music_Manager::$version );
		 wp_enqueue_style( 
		 	'font_awesome' , 
			plugin_dir_url( __FILE__ ) . 'font-awesome/css/font-awesome.min.css', 
			array() , 
			WOVAX_Music_Manager::$version );
		 
		 wp_enqueue_script( 
		 	'wovax_mm_public_script' , 
			plugin_dir_url( __FILE__ ) . 'js/public-script.js', 
			array('jquery') , 
			WOVAX_Music_Manager::$version , true );
		 
	 } // end add_public_scripts
	 
	 
	 /**
	  * Add admin scripts
	  */
	 public function add_admin_scripts( $hook ){
		 
		 if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
					 
			wp_enqueue_script(  
				'wovax_mm_selector_js', 
				plugin_dir_url(  __FILE__  ) .'js/admin-script.js' , 
				array('jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , 
				WOVAX_Music_Manager::$version , true );
				
			wp_enqueue_style( 
				'wovax_mm_admin_style' , 
				plugin_dir_url( __FILE__ ) . 'css/admin-style.css', 
				array() , 
				WOVAX_Music_Manager::$version );
			
		} // end if
		 
	 } // end add_admin_scripts
	 
	 
	 /**
	  * Flush reqrites since we are adding cpt and taxonomy
	  */
	public function wovax_mm_flush_rewrites() {
		
		require_once 'classes/class-wovax-mm-music.php'; 
		$music = new WOVAX_MM_Music();
		$music->register();

		require_once 'classes/class-wovax-mm-music-category.php';
		$music_category = new WOVAX_MM_Music_Category();
		$music_category->register();
		
		flush_rewrite_rules();
		
	} // end wovax_mm_flush_rewrites
	 
	
} // end WOVAX_Music_Manager

$wovax_mm = WOVAX_Music_Manager::get_instance();