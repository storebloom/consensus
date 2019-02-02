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
			<div class="section-subtitle smaller">
				<?php echo esc_html( $section_info['title'] ); ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="leadership-section-wrap">
		<?php foreach( $leaderships as $leadership ) :
			$thumbnail = get_the_post_thumbnail_url( $leadership->ID );
			$main_image = false !== $thumbnail ? 'background: url(' . $thumbnail . ')' : '';
			?>
			<div data-leader="<?php echo esc_attr( $leadership->ID ); ?>" class="leadership-item" style="<?php echo esc_attr( $main_image ); ?>">
				<span><?php echo esc_html( $leadership->post_title ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="leadership-popover"></div>
</div>