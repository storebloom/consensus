<?php
/**
 * Template part for use case section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="use-case-section-3" class="use-case-section">
	<h3><?php echo esc_html( 'Situation') ?></h3>

	<?php if ( isset( $section_info['situation'] ) && '' !== $section_info['situation'] ) : ?>
		<div class="section-content">
			<?php echo wp_kses_post( $section_info['situation'] ); ?>
		</div>
	<?php endif; ?>
</div>