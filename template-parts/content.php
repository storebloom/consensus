<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-vol">
		<?php echo esc_html( $article_vol ); ?>
	</div>
	<h1 class="artile-title">
		<?php the_title(); ?>
	</h1>

	<?php if( isset( $section_info['subtitle'] ) && '' !== $section_info['subtitle'] ) : ?>
		<div class="article-subtitle">
			<?php echo wp_kses_post( $section_info['subtitle'] ); ?>
		</div>
	<?php endif; ?>

	<div class="article-date">
		<?php echo esc_html( get_the_date() ); ?>
	</div>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>
