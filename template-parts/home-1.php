<?php
/**
 * Template part for home page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-1">
	<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-2'] ) && '' !== $section_info['image-2'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-2'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-3'] ) && '' !== $section_info['image-3'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-3'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-4'] ) && '' !== $section_info['image-4'] ) : ?>
		<div class="section-image-right section-cell">
			<img src="<?php echo esc_url( $section_info['image-4'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-5'] ) && '' !== $section_info['image-5'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-5'] ); ?>">
		</div>
	<?php endif; ?>

	<div class="section-logo section-cell">
		<?php get_template_part( 'images/inline', 'center-consensus.svg' ); ?>


		<?php if ( isset( $section_info['first-phrase'] ) && '' !== $section_info['first-phrase'] ) : ?>
			<div class="home-phrase-1 phrase">
				<?php echo esc_html( $section_info['first-phrase'] ); ?>
			</div>
		<?php endif; ?>
		<?php if ( isset( $section_info['second-phrase'] ) && '' !== $section_info['second-phrase'] ) : ?>
			<div class="home-phrase-2 phrase">
				<?php echo wp_kses_post( $section_info['second-phrase'] ); ?>
			</div>
		<?php endif; ?>
		<?php if ( isset( $section_info['third-phrase'] ) && '' !== $section_info['third-phrase'] ) : ?>
			<div class="home-phrase-3 phrase">
				<?php echo wp_kses_post( $section_info['third-phrase'] ); ?>
			</div>
		<?php endif; ?>

		<div class="home-animation-scroll">scroll</div>
	</div>

	<?php if ( isset( $section_info['image-6'] ) && '' !== $section_info['image-6'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-6'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-7'] ) && '' !== $section_info['image-7'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-7'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-8'] ) && '' !== $section_info['image-8'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-8'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-9'] ) && '' !== $section_info['image-9'] ) : ?>
		<div class="section-image-top section-cell">
			<img src="<?php echo esc_url( $section_info['image-9'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-10'] ) && '' !== $section_info['image-10'] ) : ?>
		<div class="section-image section-cell">
			<img src="<?php echo esc_url( $section_info['image-10'] ); ?>">
		</div>
	<?php endif; ?>
	<?php if ( isset( $section_info['image-11'] ) && '' !== $section_info['image-11'] ) : ?>
		<div class="section-image-right section-cell">
			<img src="<?php echo esc_url( $section_info['image-11'] ); ?>">
		</div>
	<?php endif; ?>
</div>