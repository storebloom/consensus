<?php
/**
 * Template Name: Portfolio
 *
 * @package ConsensusCustom
 */

get_header();

$types = get_terms( ['taxonomy' => 'type'] );

foreach ( $types as $num => $type ) {
	include locate_template( 'template-parts/portfolio-1.php' );
}

get_footer();
