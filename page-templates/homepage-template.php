<?php
/**
 * Template Name: Homepage
 *
 * @package ConsensusCustom
 */

get_header();

while ( have_posts() ) :
	the_post();

	$the_meta = get_post_meta( get_the_ID(), 'page-meta', true );

	// Set for 7 sections.  Change integer to add or remove sections.
	for ( $i = 1; $i <= 7; $i++ ) {

		$section_info = ! empty( $the_meta[ 'home-section-' . $i ] ) ? $the_meta[ 'home-section-' . $i ] : '';

		include locate_template( 'template-parts/home-' . $i . '.php' );
	}
endwhile; // End of the loop.

get_footer();
