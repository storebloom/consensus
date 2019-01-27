<?php
/**
 * Template part for use case section 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="use-case-section-4" class="use-case-section">
	<h3><?php echo esc_html( 'Solution') ?></h3>

	<?php if ( isset( $section_info['solution'] ) && '' !== $section_info['solution'] ) : ?>
		<div class="section-content">
			<?php echo wp_kses_post( $section_info['solution'] ); ?>
		</div>
	<?php endif; ?>
</div>