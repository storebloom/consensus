<?php
/**
 * Template part for home page section 7
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-7" class="home-section">
	<div class="left-section">
		<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
			<div class="section-image blue-back">
				<img src="<?php echo esc_url( $section_info['image'] ); ?>">
			</div>
		<?php endif; ?>
	</div>

	<div class="section-title-wrap right-section">
		<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
			<div class="section-subtitle">
				<?php echo esc_html( $section_info['title'] ); ?>
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
</div>