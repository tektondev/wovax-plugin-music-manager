<?php

$s = sanitize_text_field( $_GET['s'] );

$query_args = array(
			'post_type'      => 'music',
			'post_status'    => 'publish',
			'posts_per_page' => 20, 
			'orderby'        => 'date',
			'order'          => 'DESC',
			's'              => $s
		);

$query = new WP_Query( $query_args );

$music_posts = array();

if ( $query->have_posts() ){
	
	while ( $query->have_posts() ){
			
		$query->the_post();
		
		$music = new WOVAX_MM_Music();
		
		$music->set_settings( $query->post->ID );
		
		$music->set_post( $query->post );
		
		include plugin_dir_path(  __FILE__ ) . 'inc/music-item.php';
		
	} // end while
	
} else {
}


