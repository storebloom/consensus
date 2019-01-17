<?php
/**
 * Template part for home page section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-2" class="home-section">
	<div class="section-title-wrap">
		<div class="section-number"><?php echo esc_html__( '01', 'consensus-custom' ); ?></div>
		<div class="section-line-space"></div>

		<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
			<div class="section-title">
				<?php echo esc_html( $section_info['title'] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['subtitle'] ) && '' !== $section_info['subtitle'] ) : ?>
			<div class="section-subtitle">
				<?php echo esc_html( $section_info['subtitle'] ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="case-study-section-wrap">
		Case Study Section Goes Here.
	</div>
</div>