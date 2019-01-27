<?php
/**
 * Template part for blog page section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="blog-section-3" class="blog-section">
	<?php foreach( $posts as $post ) :
		$article_date = get_the_date();
		$article_vol = $article_vol = get_article_volume( $article_date, $post->ID );
	?>
	<div class="article-wrap">
		<div class="article-left">
			<div class="article-vol">
				<?php echo esc_html( $article_vol ); ?>
			</div>
			<div class="article-title">
				<?php echo esc_html( $post->post_title ); ?>
			</div>
			<div class="article-date">
				<?php echo esc_html( $article_date ); ?>
			</div>
		</div>
		<div class="article-right">
			<div class="article-content">
				<?php the_content(); echo wp_trim_words( $post->post_content, 25, '...' ); ?>
			</div>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>">
				<?php echo esc_html__( 'Read', 'consensus-custom' ); ?>
				<span class="arrow-right"></span>
			</a>
		</div>
	</div>
	<?php
	endforeach; // End of the loop.
?>
</div>
