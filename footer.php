<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ConsensusCustom
 */

$footer_class = 'on' === get_post_meta( get_the_ID(), 'hide-footer', true ) ? ' hide-footer' : '';
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer<?php echo esc_attr( $footer_class ); ?>">
		<div class="footer-inner-wrap">
			<div class="first-footer-area"><?php dynamic_sidebar( 'footer-1' ); ?></div>
			<div class="second-footer-area"><?php dynamic_sidebar( 'footer-2' ); ?></div>
			<div class="third-footer-area"><?php dynamic_sidebar( 'footer-3' ); ?></div>
			<div class="fourth-footer-area"><?php dynamic_sidebar( 'footer-4' ); ?></div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
