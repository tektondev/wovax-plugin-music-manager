<?php 

/**
 * Class for custom post type music
 * 
 * @author Wovac LLc, Danial Bleile
 * @uses WOVAX_MM_Post | class-wovax-post.php
 * @version 0.0.1
 */

require_once 'class-wovax-mm-post.php';

class WOVAX_MM_Music extends WOVAX_MM_Post {
	
	//@var string Slug for post type
	protected $slug = 'music';
	
	//@var array Labels used when registering post type
	protected $labels = array(
		'name'               => 'Music',
		'singular_name'      => 'Music',
		'menu_name'          => 'Music',
		'name_admin_bar'     => 'Music',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Music',
		'new_item'           => 'New Music',
		'edit_item'          => 'Edit Music',
		'view_item'          => 'View Music',
		'all_items'          => 'All Music',
		'search_items'       => 'Search Music',
		'parent_item_colon'  => 'Parent Music:',
		'not_found'          => 'No music found.',
		'not_found_in_trash' => 'No music found in Trash.'
	);
		
	//@var array Args for registering the post type. Labels added when registering post type
	protected $args = array(
        'description'        => 'Description.',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
	);
	
	//@var array Custom fields/post_meta used for post type & default value
	protected $fields = array(
		'_music_subtitle' => '',
		'_music_sheet'    => '',
		'_music_mp3'      => '',
		'_music_video'    => '',
		'_music_page'     => '',
	);
	
	//@var array Defautls for shortcode
	protected $defaults = array(
		'display' => 'az-index',
	);
	
	/**
	 * Include the edit form for custom post type
	 * 
	 * @param object WP Post object
	 */
	public function editor_form( $post ){
		
		$this->set_settings( $post->ID );
		
		include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-editor.php';
		
	} // end the_editor
	
	
	/**
	 * Sanitizes settings
	 * 
	 * @param array $settings Array of settings to clean
	 * @return array Cleaned settings
	 */
	public function get_clean_settings( $settings ){
		
		$clean_settings = array();
		
		if ( isset( $settings['_music_subtitle'] ) ){
			
			$clean_settings['_music_subtitle'] = sanitize_text_field( $settings['_music_subtitle'] );
			
		} // end if
		
		if ( isset( $settings['_music_sheet'] ) ){
			
			$clean_settings['_music_sheet'] = sanitize_text_field( $settings['_music_sheet'] );
			
		} // end if
		
		if ( isset( $settings['_music_mp3'] ) ){
			
			$clean_settings['_music_mp3'] = sanitize_text_field( $settings['_music_mp3'] );
			
		} // end if
		
		if ( isset( $settings['_music_video'] ) ){
			
			$clean_settings['_music_video'] = sanitize_text_field( $settings['_music_video'] );
			
		} // end if
		
		if ( isset( $settings['_music_page'] ) ){
			
			$clean_settings['_music_page'] = sanitize_text_field( $settings['_music_page'] );
			
		} // end if
		
		return $clean_settings;
		
	} // end get_clean_settings
	
	
	/**
	 * Checks for singular music and adds subhead
	 * 
	 * @param string $content Post content to filter
	 * @return string Filtered content
	 */
	public function add_subheader( $content ){
		
		if ( is_singular('music') ){
			
			/**
			 * Plugins and themes can call the_content filter multiple times so
			 * let's check and make sure we didn't already add this. There is a possibility that
			 * improper use of the_content in a theme or plugin could cause this to break.
			 */
			if ( strpos( $content , 'wovax-mm-music-subheader' ) === false ){
				
				global $post;
				
				$this->set_settings( $post->ID );
				
				ob_start();
				
				include plugin_dir_path( dirname( __FILE__ ) ) . 'inc/music-single-subheader.php';
				
				return ob_get_clean() . $content;
				
			} else {
				
				return $content;
				
			} // end if
			
		} else {
			
			return $content;
			
		} // end if
		
	} // end add_subheader
	
	
	/**
	  * Enqueue admin scripts
	  */
	 /*public function edit_post_scripts(){
		 
		 wp_enqueue_style( 'wovax_mm_music_editor_css' , plugin_dir_url( dirname( __FILE__ ) ) . 'css/editor-style.css', array() , WOVAX_Music_Manager::$version );
	 
	 } // end add_admin_scripts*/
	 
	 
}