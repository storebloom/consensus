<?php
/**
 * Template part for home page section 6
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-6" class="home-section grey">
	<div class="section-title-wrap left-section">
		<div class="section-number"><?php echo esc_html__( '03', 'consensus-custom' ); ?></div>

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

		<?php if ( isset( $section_info['content'] ) && '' !== $section_info['content'] ) : ?>
			<div class="section-content">
				<?php echo wp_kses_post( $section_info['content'] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['button-text'] ) && '' !== $section_info['button-text'] ) : ?>
			<a href="<?php echo isset( $section_info['button-url'] ) ? esc_url( $section_info['button-url'] ) : ''; ?>" class="consensus-button">
				<?php echo esc_html( $section_info['button-text'] ); ?>
			</a>
		<?php endif; ?>
	</div>

	<div class="right-section">
		<?php if ( isset( $section_info['graphic'] ) && '' !== $section_info['graphic'] ) : ?>
			<img src="<?php echo esc_attr( $section_info['graphic'] ); ?>">
		<?php endif; ?>
	</div>
</div>