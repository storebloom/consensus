<?php
/**
 * Template part for home page section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-3" class="home-section grey">
	<div class="section-title-wrap left-section">
		<div class="section-number"><?php echo esc_html__( '02', 'consensus-custom' ); ?></div>
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

		<div class="section-line-space"></div>

		<?php if ( isset( $section_info['content'] ) && '' !== $section_info['content'] ) : ?>
			<div class="section-subtitle">
				<?php echo wp_kses_post( $section_info['content'] ); ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="right-section">
		<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
			<div class="section-image">
				<img src="<?php echo esc_url( $section_info['image'] ); ?>">
			</div>
		<?php endif; ?>
	</div>
	<div class="left-section">
		<?php if ( isset( $section_info['image-2'] ) && '' !== $section_info['image-2'] ) : ?>
			<div class="section-image">
				<img src="<?php echo esc_url( $section_info['image-2'] ); ?>">
			</div>
		<?php endif; ?>
	</div>
	<div class="section-title-wrap right-section">
		<?php if ( isset( $section_info['subtitle-2'] ) && '' !== $section_info['subtitle-2'] ) : ?>
			<div class="section-subtitle">
				<?php echo esc_html( $section_info['subtitle-2'] ); ?>
			</div>
		<?php endif; ?>

		<div class="section-line-space"></div>

		<?php if ( isset( $section_info['content-2'] ) && '' !== $section_info['content-2'] ) : ?>
			<div class="section-subtitle">
				<?php echo wp_kses_post( $section_info['content-2'] ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>