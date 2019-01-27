<?php
/**
 * Template part for blog page section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="blog-section-2" class="blog-section">
	<div class="section-lead-wrap">
		<?php if ( isset( $section_info['subtitle'] ) && '' !== $section_info['subtitle'] ) : ?>
			<div class="section-subtitle">
				<?php echo esc_html( $section_info['subtitle'] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['form'] ) && '' !== $section_info['form'] ) : ?>
			<div class="section-lead-form">
				<input type="text" placeholder="<?php echo esc_attr( $section_info['form'] ); ?>">
			</div>
		<?php endif; ?>
	</div>
</div>
