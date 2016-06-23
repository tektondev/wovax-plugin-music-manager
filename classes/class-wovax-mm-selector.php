<?php
/**
 * Class for TinyMCE select music plugin
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */
 class WOVAX_MM_Selector {
	 
	 protected $library;
	 
	 
	 public function __construct( $library ){
		 
		 $this->library = $library;
		 
	 }
	
	 
	 public function init(){
		 
		 add_action('media_buttons', array( $this , 'add_music_button' ) );
		 
		 add_action('edit_form_after_title' , array( $this , 'the_selector_box' ) );
		 

		 
	 }
	 
	 public function add_music_button(){
		 
		 echo '<a href="#TB_inline?width=100%&inlineId=wovax-mm-selector&height=auto" id="insert-music" class="button thickbox">Add Music</a>';
		 
	 }
	 
	public function the_selector_box(){
	   
	   $query = $this->library->get_search_query( false );
	   
	   $music_array = $this->library->get_music_from_query( $query );
	  
	   uasort( $music_array , array( $this->library , 'sort_search_array' ) );
	   
	   ob_start();
	   
	   foreach( $music_array as $music ){
		   
		   include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-editor-selector-music-item.php';
		   
	   } // end foreach
	   
	   $music_html = ob_get_clean();
	   
	   add_thickbox();
	   
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