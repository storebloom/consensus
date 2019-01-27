<?php
/**
 * Template part for use case section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="use-case-section-1" class="use-case-section">
	<h1><?php echo get_the_title(); ?></h1>

	<?php if ( isset( $section_info['subtitle'] ) && '' !== $section_info['subtitle'] ) : ?>
		<div class="section-subtitle">
			<?php echo esc_html( $section_info['subtitle'] ); ?>
		</div>
	<?php endif; ?>

	<div class="section-thumbnail">
		<?php echo wp_kses_post( $main_image ); ?>
	</div>
</div>