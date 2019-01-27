<?php
/**
Template Name: Single Use Case Template
Template Post Type: use-case
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<?php

	$the_meta = get_post_meta( get_the_ID(), 'page-meta', true );
	$section_info = ! empty( $the_meta[ 'use-case-section-consensus' ] ) ? $the_meta[ 'use-case-section-consensus' ] : '';
	$thumbnail = get_the_post_thumbnail_url();
	$main_image = false !== $thumbnail ? '<img src="' . $thumbnail . '">' : '';

	// Set for 4 sections.  Change integer to add or remove sections.
	for ( $i = 1; $i <= 4; $i++ ) {

		include locate_template( 'template-parts/use-case-' . $i . '.php' );
	}
endwhile; // End of the loop.

get_footer();
