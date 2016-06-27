<?php
/**
 * Class for a custom taxonomy
 * 
 * @author Wovac LLc, Danial Bleile
 * @version 0.0.1
 */

require_once dirname(__FILE__) . '/class-wovax-mm-taxonomy.php';
 
class WOVAX_MM_Music_Category extends WOVAX_MM_Taxonomy {
	
	//@var string Slug for taxonomy
	protected $slug = 'wx_music_category';
	
	//@var array Labels for the taxonomy
	protected $labels = array(
		'name'              => 'Music Category',
		'singular_name'     => 'Music Category',
		'search_items'      => 'Search Music Categories',
		'all_items'         => 'All Music Categories',
		'parent_item'       => 'Parent Music Category' ,
		'parent_item_colon' => 'Parent Music Category:' ,
		'edit_item'         => 'Edit Music Category',
		'update_item'       => 'Update Music Category',
		'add_new_item'      => 'Add New Music Category',
		'new_item_name'     => 'New Music Category Name',
		'menu_name'         => 'Music Categories',
	);
	
	//@var array Args for registering the taxonomy
	protected $args = array(
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'music-category' ),
	);
	
	//@var array Add to post types
	protected $post_types = array( 'music' );
	
}