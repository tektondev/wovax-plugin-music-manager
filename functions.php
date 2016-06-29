<?php
/**
 * JT Genesis Child
 *
 * @package      JTGenesisChild
 * @since        1.0.0
 * @copyright    Copyright (c) 2016
 * @license      GPL-2.0+
 */

/**
 * Set up the content width value based on the theme's design.
 *
 */
if ( ! isset( $content_width ) )
    $content_width = 740;

/**
 * Theme setup.
 *
 * Attach all of the site-wide functions to the correct hooks and filters. All
 * the functions themselves are defined below this setup function.
 *
 * @since 1.0.0
 */

function jt_child_theme_setup() {

	// Genesis Specific Changes
	include_once( get_stylesheet_directory() . '/inc/genesis-changes.php' );

	// Editor Styles
	add_editor_style( 'css/editor-style.css' );

	// Image Sizes
	add_image_size( 'jt_square_small', 50, 50, true );
	add_image_size( 'jt_blog_thumb', 279, 320, true );
	add_image_size( 'jt_rectangle_medium', 900, 700 );
	add_image_size( 'jt_hero', 1920, 900, true );

	// Remove Sidebar Text
	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

	// Set comment area defaults
	add_filter( 'comment_form_defaults', 'jt_comment_text' );

	// Global enqueues
	add_action( 'wp_enqueue_scripts', 'jt_global_enqueues' );

}
add_action( 'genesis_setup', 'jt_child_theme_setup', 15 );


/* ==========================================================================
 * Backend Functions
 * ========================================================================== */

/**
 * Change the comment area text
 *
 * @since  1.0.0
 * @param  array $args
 * @return array
 */
function jt_comment_text( $args ) {
	$args['title_reply']          = __( 'Leave A Reply', 'jt_genesis_child' );
	$args['label_submit']         = __( 'Post Comment',  'jt_genesis_child' );
	$args['comment_notes_before'] = '';
	$args['comment_notes_after']  = '';

	// Remove labels and add placeholders
	$args['fields']['author'] = '<p class="comment-form-author"><input id="author" name="author" type="text" value="" size="30" aria-required="true" placeholder="Your Name"></p>';
	$args['fields']['email'] = '<p class="comment-form-email"><input id="email" name="email" type="email" value="" size="30" aria-describedby="email-notes" aria-required="true" placeholder="Your Email"></p>';
	$args['fields']['url'] = '<p class="comment-form-url"><input id="url" name="url" type="url" value="" size="30" placeholder="Website URL"></p>';
	$args['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-describedby="form-allowed-tags" aria-required="true" placeholder="Your Comment"></textarea></p>';
	return $args;
}

/**
 * Global enqueues
 *
 * @since  1.0.0
 * @global array $wp_styles
 */
function jt_global_enqueues() {

    // javascript
    wp_enqueue_script( 'fitvids', get_stylesheet_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.1', true );
    wp_enqueue_script( 'jt-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery', 'fitvids' ), '1.0', true );
    wp_enqueue_script( 'jt-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'magnific', get_stylesheet_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'sticky', get_stylesheet_directory_uri() . '/js/sticky.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'bxslider', get_stylesheet_directory_uri() . '/js/jquery.bxslider.min.js', array( 'jquery' ), '4.2.3', true );

    // css
    wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'ionicons', '//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array(), CHILD_THEME_VERSION );
    wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,300,600,700');
    wp_enqueue_style( 'magnific', get_stylesheet_directory_uri() . '/css/magnific-popup.css' );
    wp_enqueue_style( 'bxslider', get_stylesheet_directory_uri() . '/css/jquery.bxslider.css' );
}


/* ==========================================================================
 * Frontend Functions
 * ========================================================================== */

/**
 * Page Header
 *
 */
function jt_page_header() {

	if( is_page() ) {

		$background_image = jt_cf( 'jt_header_image' );
		$title = jt_cf( 'jt_header_title' ) ? jt_bar_to_br( jt_cf( 'jt_header_title', false, false, '<h1>', '</h1>', 'esc_html' ) ) : the_title( '<h1>', '</h1>', false);
		$subtitle = jt_bar_to_br( jt_cf( 'jt_header_subtitle', false, false, '<p>', '</p>', 'esc_html' ) );

		if( $background_image ) {
			$background_image = 'style="background-image: url(' . wp_get_attachment_image_src( $background_image, 'full' )[0] . ')"';
			$background_image_class = ' -with-background-image';
		}

		?>

		<div class="page-header bg-blue <?php echo $background_image_class; ?>" <?php echo $background_image; ?>>
			<div class="wrap">
				<?php echo $title; ?>
				<?php echo $subtitle; ?>
			</div>
		</div>

		<?php
	}
}
add_action( 'genesis_after_header', 'jt_page_header' );

/**
 * Flexible Content
 *
 */
function jt_flexible_template() {
	$rows = get_post_meta( get_the_ID(), 'jt_content', true );
	if($rows != '') {
		foreach( $rows as $count => $row ) {
			switch( $row ) {

				// Full Width Content
				case 'full_width_content' :
					$title = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_full_width_content_title', false, false, '<h2>', '</h2>', 'esc_html' ) );
					$subtitle = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_full_width_content_subtitle', false, false, '<p class="subtitle">', '</p>', 'esc_html' ) );
					$content = jt_cf( 'jt_content_' . $count . '_content', false, false );
					$background_class = jt_cf( 'jt_content_' . $count . '_full_width_content_background_class', false, false );

					?>

					<section class="flexible-content full-width-content <?php echo $background_class; ?>">
						<div class="wrap">
							<?php echo $title; ?>
							<?php echo $subtitle; ?>
							<?php echo apply_filters( 'the_content', $content ); ?>
						</div>
					</section>

					<?php

				break;

				case 'three_boxes' :
					$title = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_three_boxes_title', false, false, '<h2>', '</h2>', 'esc_html' ) );
					$subtitle = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_three_boxes_subtitle', false, false, '<p>', '</p>', 'esc_html' ) );

					$box_1_image = jt_cf( 'jt_content_' . $count . '_box_1_image');
					$box_1_title = jt_cf( 'jt_content_' . $count . '_box_1_title', false, false, '<h3>', '</h3>', 'esc_html');
					$box_1_description = jt_cf( 'jt_content_' . $count . '_box_1_description', false, false, '<p>', '</p>', 'esc_html');

					$box_2_image = jt_cf( 'jt_content_' . $count . '_box_2_image');
					$box_2_title = jt_cf( 'jt_content_' . $count . '_box_2_title', false, false, '<h3>', '</h3>', 'esc_html');
					$box_2_description = jt_cf( 'jt_content_' . $count . '_box_2_description', false, false, '<p>', '</p>', 'esc_html');

					$box_3_image = jt_cf( 'jt_content_' . $count . '_box_3_image');
					$box_3_title = jt_cf( 'jt_content_' . $count . '_box_3_title', false, false, '<h3>', '</h3>', 'esc_html');
					$box_3_description = jt_cf( 'jt_content_' . $count . '_box_3_description', false, false, '<p>', '</p>', 'esc_html');

					$box_1_image = $box_1_image ? wp_get_attachment_image_src( $box_1_image, 'jt_rectangle_medium' ) : '';
					$box_2_image = $box_2_image ? wp_get_attachment_image_src( $box_2_image, 'jt_rectangle_medium' ) : '';
					$box_3_image = $box_3_image ? wp_get_attachment_image_src( $box_3_image, 'jt_rectangle_medium' ) : '';

					$box_1 = '<div class="one-third first"><div class="box-image" style="background-image: url(' . $box_1_image[0] . ');"></div>' . $box_1_title . $box_1_description . '</div>';
					$box_2 = '<div class="one-third"><div class="box-image" style="background-image: url(' . $box_2_image[0] . ');"></div>' . $box_2_title . $box_2_description . '</div>';
					$box_3 = '<div class="one-third"><div class="box-image" style="background-image: url(' . $box_3_image[0] . ');"></div>' . $box_3_title . $box_3_description . '</div>';

					$boxes = $box_1 . $box_2 . $box_3;

					$cta_1_link = jt_cf( 'jt_content_' . $count . '_three_boxes_cta_1_link');
					$cta_1 = jt_cf( 'jt_content_' . $count . '_three_boxes_cta_1_text', false, false, '<a class="button" href="' . $cta_1_link . '">', '</a>', 'esc_html');
					$cta_2_link = jt_cf( 'jt_content_' . $count . '_three_boxes_cta_2_link');
					$cta_2 = jt_cf( 'jt_content_' . $count . '_three_boxes_cta_2_text', false, false, '<a class="button -light" href="' . $cta_2_link . '">', '</a>', 'esc_html');

					?>

					<section class="flexible-content three-boxes">
						<div class="wrap">
							<?php echo $title; ?>
							<?php echo $subtitle; ?>
						<div class="grid equal-heights">
							<?php echo $boxes; ?>
						</div>
							<?php echo $cta_1; ?>
							<?php echo $cta_2; ?>
						</div>
					</section>

					<?php

				break;

				// Content with Image
				case 'content_with_image';

					$title = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_content_with_image_title', false, false, '<h2>', '</h2>', 'esc_html' ) );
					$content = jt_cf( 'jt_content_' . $count . '_content_with_image_content', false, false );
					$background_class = jt_cf( 'jt_content_' . $count . '_content_with_image_background_class', false, false );
					$image = jt_cf( 'jt_content_' . $count . '_content_with_image_image');
					$image_placement = jt_cf( 'jt_content_' . $count . '_image_placement');
					$image_class = jt_cf( 'jt_content_' . $count . '_image_class');
					$cta_link = jt_cf( 'jt_content_' . $count . '_content_with_image_cta_link');
					$cta = jt_cf( 'jt_content_' . $count . '_content_with_image_cta_text', false, false, '<a class="button -light" href="' . $cta_link . '">', '</a>', 'esc_html');

					?>

					<section class="flexible-content content-with-image <?php echo $background_class; ?>">
						<div class="wrap">
							<div class="grid equal-heights">
								<div class="one-half first">
					<?php if( $image_placement == 'left' ) : ?>
									<div class="image <?php echo $image_class; ?>">
					 		 			<?php echo wp_get_attachment_image( $image, 'full' ); ?>
									</div>
								</div>
					<?php else : ?>
									<div class="content">
					 					<?php echo $title; ?>
					 					<?php echo $content; ?>
					 					<?php echo $cta; ?>
									</div>
								</div>
					<?php endif; ?>
								<div class="one-half">
					<?php if( $image_placement == 'right' ) : ?>
									<div class="image <?php echo $image_class; ?>">
					 		 			<?php echo wp_get_attachment_image( $image, 'full' ); ?>
									</div>
								</div>
					<?php else : ?>
									<div class="content">
					 					<?php echo $title; ?>
					 					<?php echo $content; ?>
					 					<?php echo $cta; ?>
									</div>
								</div>
					<?php endif; ?>
							</div>
						</div>
					</section>

					<?php

				break;

				// Team Grid
				case 'team_grid' :
					$title = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_team_grid_title', false, false, '<h2>', '</h2>', 'esc_html' ) );
					$subtitle = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_team_grid_subtitle', false, false, '<p>', '</p>', 'esc_html' ) );
					$team = jt_cf( 'jt_content_' . $count . '_team_members' );

					?>

					<section class="flexible-content team-grid">
						<div class="wrap">
							<?php echo $title; ?>
							<?php echo $subtitle; ?>
					<?php

					// Count up through the team members and loop through each of them
					for( $i = 0; $i < $team; $i++ ) {
					$member_image = jt_cf( 'jt_content_' . $count . '_team_members_' . $i . '_member_image' );
					$member_name = jt_cf( 'jt_content_' . $count . '_team_members_' . $i . '_member_name', false, false, '<h3>', '</h3>', 'esc_html' );
					$member_title = jt_cf( 'jt_content_' . $count . '_team_members_' . $i . '_member_title', false, false, '<p>', '</p>', 'esc_html' );
					$member_class = ($i % 3 == 0) ? ' first' : '';

					if ( $member_image ) {
						$member_image = 'style="background-image: url(' . wp_get_attachment_image_src( $member_image, 'jt_rectangle_medium' )[0] . ')"';
					}

					?>
							<div class="one-third<?php echo $member_class; ?>">
								<div class="box-image" <?php echo $member_image; ?>></div>
								<div class="box-content">
									<?php echo $member_name; ?>
									<?php echo $member_title; ?>
								</div>
							</div>
					<?php } ?>
						</div>

					</section>

					<?php

				break;

				// Testimonials Repeater
				case 'testimonials' :
					$testimonials = jt_cf( 'jt_content_' . $count . '_testimonials' );
					$background_class = jt_cf( 'jt_content_' . $count . '_testimonials_background_class' );
					$background_image = jt_cf( 'jt_content_' . $count . '_testimonials_background_image' );

					if ( $background_image ) {
						$background_image = 'style="background-image: url(' . wp_get_attachment_image_src( $background_image, 'large' )[0] . ')"';
						$background_class .= ' -with-background-image';
					}

					?>

					<section class="flexible-content testimonials <?php echo $background_class; ?>" <?php echo $background_image; ?>>
						<div class="wrap">
							<ul class="testimonial-slider">

					<?php

					// Count up through the testimonials and loop through each of them
					for( $i = 0; $i < $testimonials; $i++ ) {
					$quote = jt_cf( 'jt_content_' . $count . '_testimonials_' . $i . '_quote', false, false, '<blockquote>', '</blockquote>', 'esc_html' );
					$customer_image = jt_cf( 'jt_content_' . $count . '_testimonials_' . $i . '_customer_image' );
					$customer_name = jt_cf( 'jt_content_' . $count . '_testimonials_' . $i . '_customer_name', false, false, '<h3>', '</h3>', 'esc_html' );
					$customer_title = jt_cf( 'jt_content_' . $count . '_testimonials_' . $i . '_customer_title', false, false, '<h4>', '</h4>', 'esc_html' );

					?>
								<li>
									<?php echo $quote; ?>
									<div class="testimonial-source">
										<?php echo wp_get_attachment_image( $customer_image, 'jt_square_small' ); ?>
										<?php echo $customer_name; ?>
										<?php echo $customer_title; ?>
									</div>
								</li>
					<?php } ?>
							</ul>
						</div>
					</section>

					<?php

				break;

				// Full Width Content
				case 'page_end_navigation' :

					$description = jt_cf( 'jt_content_' . $count . '_page_end_navigation_description', false, false, '<p>', '</p>', 'esc_html' );
					$title = jt_bar_to_br( jt_cf( 'jt_content_' . $count . '_page_end_navigation_title', false, false, '<h1>', '</h1>', 'esc_html' ) );
					$link = jt_cf( 'jt_content_' . $count . '_page_end_navigation_link' );
					$background_class = jt_cf( 'jt_content_' . $count . '_page_end_navigation_background_class', false, false );

					?>

					<a href="<?php echo $link; ?>" class="flexible-content page-end-navigation <?php echo $background_class; ?>">
						<div class="wrap">
							<h6>Next Up</h6>
							<?php echo $title; ?>
							<?php echo $description; ?>
							<span href="<?php echo $link; ?>" class="button -minimal -arrow-right">Learn More</span>
						</div>
					</a>

					<?php

				break;
			}
		}
	}
}
add_action( 'jt_content_area', 'jt_flexible_template' );

/**
 * Blog Stuff
 *
 */

// Use archive.php for the blog and search
function jt_blog_template( $template ) {
	if( is_home() || is_search() )
		$template = get_query_template( 'archive' );
	return $template;
}
add_filter( 'template_include', 'jt_blog_template' );

// Only Search posts
function SearchFilter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts','SearchFilter');

// Blog Toolbar
function jt_blog_toolbar() {

	if ( is_home() || is_search() || is_singular( 'post' ) || is_archive() ) {

		?>
		<div class="blog-toolbar">
			<div class="wrap">
				<?php
				genesis_widget_area( 'footer-social-icons', array(
					'before'	=> '<div class="one-half first widget-area">',
					'after' 	=> '</div>'
					)
				);
				?>
				<div class="one-half">
					<?php echo genesis_search_form(); ?>
				</div>
			</div>
		</div>
		<?php

	}

}
add_action( 'genesis_after_header', 'jt_blog_toolbar', 12 );

// Customize search form input box text
add_filter( 'genesis_search_text', 'jt_search_text' );
function jt_search_text( $text ) {
	return esc_attr( 'Search ' . get_bloginfo( $title ) );
}

// edit the post info
function jt_post_info_filter( $post_info ) {
	global $post;
	$author_id = $post->post_author;
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$author_url = get_author_posts_url( $author_id, $author_nicename );
	$author_avatar = get_avatar( get_the_author_meta( 'user_email', $author_id ) );
	$post_info = '<a href="' . $author_url . '">' . $author_avatar . $author_name . '</a> on [post_date]';
	return $post_info;
}
add_filter( 'genesis_post_info', 'jt_post_info_filter' );


/**
 * Footer
 *
 */

function jt_footer() {

	echo '<div class="footer-cta">';
	echo 	'<div class="one-fourth first"><a class="button -light -full-width" href="http://www.christkirk.com/sermons">Sermons</a></div>';
	echo 	'<div class="one-fourth"><a class="button -light -full-width" href="http://www.christkirk.com/events">Events</a></div>';
	echo 	'<div class="one-fourth"><a class="button -light -full-width" href="http://www.christkirk.com/worship-with-us">Worship With Us</a></div>';
	echo 	'<div class="one-fourth"><a class="button -light -full-width" href="http://www.christkirk.com/get-involved">Get Involved</a></div>';
	echo '</div>';

	genesis_widget_area( 'footer-site-menus', array(
		'before' 	=> '<div class="footer-site-menus widget-area">',
		'after' 	=> '</div>',
		)
	);
	genesis_widget_area( 'footer-social-icons', array(
		'before'	=> '<div class="footer-social-icons one-half first widget-area">',
		'after' 	=> '</div>'
		)
	);

	echo '<div class="one-half site-credits">';
	echo 	'<p>&copy; Copyright ' . get_bloginfo( $title ) . ' ' . date( 'Y') . '. All Rights Reserved.</p>';
	echo '</div>';
}

remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'jt_footer' );

/* ==========================================================================
 * Helper Functions
 * ========================================================================== */

/**
 * Bar to Line Break
 *
 */
function jt_bar_to_br( $content ) {
	return str_replace( ' | ', '<br class="mobile-hide">', $content );
}