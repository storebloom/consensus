<?php
/**
 * Template part for blog page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="blog-section-1" class="blog-section">
	<div class="section-title-wrap">
		<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
			<div class="section-title">
				<?php echo esc_html( $section_info['title'] ); ?>
			</div>
		<?php endif; ?>

		<div class="section-image">
			<?php echo wp_kses_post( $main_image ); ?>
		</div>
	</div>
</div>
