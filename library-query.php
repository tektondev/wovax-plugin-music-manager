<?php

require_once 'classes/class-wovax-mm-music-category.php';
$music_category = new WOVAX_MM_Music_Category();

require_once 'classes/class-wovax-mm-library.php';
$music_library = new WOVAX_MM_Library(); 

require_once 'classes/class-wovax-mm-library-shortcode.php';
$library = new WOVAX_MM_Library_Shortcode( $music_category , $music_library );

$library->do_request();