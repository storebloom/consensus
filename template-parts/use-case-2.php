<?php
/**
 * Template part for use case section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="use-case-section-2" class="use-case-section">
	<h3><?php echo esc_html( 'Client') ?></h3>

	<?php if ( isset( $section_info['client'] ) && '' !== $section_info['client'] ) : ?>
		<div class="section-content">
			<?php echo wp_kses_post( $section_info['client'] ); ?>
		</div>
	<?php endif; ?>
</div>