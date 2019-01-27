<?php
/**
 * Template Name: Blog
 *
 * @package ConsensusCustom
 */

get_header();

while ( have_posts() ) :
	the_post();

	$the_meta = get_post_meta( get_the_ID(), 'page-meta', true );
	$posts = get_posts(array(
		'post_type' => 'post',
		'numberposts' => 5,
	));
	$thumbnail = get_the_post_thumbnail_url();
	$main_image = false !== $thumbnail ? '<img src="' . $thumbnail . '">' : '';

	// Set for 3 sections.  Change integer to add or remove sections.
	for ( $i = 1; $i <= 3; $i++ ) {

		$section_info = ! empty( $the_meta[ 'blog-section-consensus' ] ) ? $the_meta[ 'blog-section-consensus' ] : '';

		include locate_template( 'template-parts/blog-' . $i . '.php' );
	}
endwhile; // End of the loop.

get_footer();
