<?php

require_once 'classes/class-wovax-mm-music-category.php';
$music_category = new WOVAX_MM_Music_Category();
		 
require_once 'classes/class-wovax-mm-query.php';
$query = new WOVAX_MM_Query();

require_once 'classes/class-wovax-mm-library-shortcode.php';
$library = new WOVAX_MM_Library_Shortcode( $music_category , $query );

$library->do_request();