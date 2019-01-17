<?php
/**
 * Template part for home page section 5
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-5" class="home-section">
	<div class="case-study">
		<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
			<div class="background-title">
				<?php echo esc_html( $section_info['title'] ); ?>
			</div>
		<?php endif; ?>

		<div class="cs-left-section">
			<div class="cs-brands-section"></div>
			<div class="cs-brand-photos"></div>
		</div>
		<div class="cs-categories"></div>
	</div>
</div>