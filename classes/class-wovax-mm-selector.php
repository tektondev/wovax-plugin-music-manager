<?php
/**
 * Class for TinyMCE select music plugin
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
 class WOVAX_MM_Selector {
	 
	 protected $library;
	 
	 protected $music_category;
	 
	 
	 public function __construct( $library , $music_category ){
		 
		 $this->library = $library;
		 
		 $this->music_category = $music_category;
		 
	 }
	
	 
	 public function init(){
		 
		 add_action('media_buttons', array( $this , 'add_music_button' ) );
		 
		 add_action('admin_footer-post.php' , array( $this , 'the_selector_box' ) );
		 add_action('admin_footer-post-new.php' , array( $this , 'the_selector_box' ) );
		 
	 }
	 
	 public function add_music_button(){
		 
		 echo '<a href="#" id="mm-insert-music" class="button">Add Music</a>';
		 
	 }
	 
	public function the_selector_box(){
	   
	   $query = $this->library->get_search_query( false );
	   
	   $music_array = $this->library->get_music_from_query( $query );
	   
	   $music_terms = $this->music_category->get_select_terms();
	   
	   ob_start();
	   
	   foreach( $music_array as $music ){
		   
		   include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-editor-selector-music-item.php';
		   
	   } // end foreach
	   
	   $music_html = ob_get_clean();
	   
	   include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-editor-selector.php';
	   
	}
	 
	  
	 /**
	  * Register plugin to TinyMCE
	  *
	  * @param array $plugin_array https://codex.wordpress.org/TinyMCE_Custom_Buttons
	  * @return array Updated array of plugins
	  */
	 /*public function register( $plugin_array ){
		 
		 var_dump( plugins_url( '/js/plugin-music-selector.js' , dirname(__FILE__) ) );
		 
		 $plugin_array['wovaxmusic'] = plugins_url( '/js/plugin-music-selector.js' , dirname(__FILE__) );
		 
		 return $plugin_array;
		 
	 } // end register
	 
	 
	  /**
	  * Register plugin to TinyMCE
	  *
	  * @param array $plugin_array https://codex.wordpress.org/TinyMCE_Custom_Buttons
	  * @return array Updated array of plugins
	  */
	/* public function add_buttons( $buttons ) {
		 
   		array_push( $buttons, 'dropcap', 'showrecent' ); // dropcap', 'recentposts
		
   		return $buttons; 
		
	 } // end register*/
	 
	 
	 
 } // end Wovax_MM_Selector