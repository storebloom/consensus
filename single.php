<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ConsensusCustom
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();

			$postid = get_the_ID();
			$the_meta = get_post_meta( $postid, 'page-meta', true );
			$section_info = ! empty( $the_meta[ 'article-consensus' ] ) ? $the_meta[ 'article-consensus' ] : '';
			$article_vol = get_article_volume( get_the_date(), $postid );

			include locate_template( 'template-parts/content.php' );
		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
