<?php
/**
 * Template part for home page section 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-4" class="home-section grey">
	<div class="section-title-wrap">
		<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
			<div class="section-subtitle">
				<?php echo esc_html( $section_info['title'] ); ?>
			</div>
		<?php endif; ?>

		<div class="section-line-space"></div>

		<?php if ( isset( $section_info['subtitle'] ) && '' !== $section_info['subtitle'] ) : ?>
			<div class="section-subtitle">
				<?php echo esc_html( $section_info['subtitle'] ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="employee-section-wrap">
		Employee Section here
	</div>
</div>