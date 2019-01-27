<?php
/**
 * Template part for home page section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div id="home-section-2" class="home-section">
	<div class="section-title-wrap">
		<div class="section-number"><?php echo esc_html__( '01', 'consensus-custom' ); ?></div>
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
	</div>

	<div class="services-section-wrap">
		<div class="services-left">
			<?php foreach( $types as $type ) : ?>
				<div class="service-type">
					<div class="service-name">
						<?php echo esc_html( $type->name ); ?>
					</div>
					<div class="service-desc">
						<?php echo esc_html( $type->description ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="services-right">
			<?php foreach( $types as $type ) : ?>
				<div data-type="<?php echo esc_attr( $type->name ); ?>" class="service-brands">
			<?php $brands = get_posts(array(
					'post_type' => 'use-case',
					'numberposts' => 3,
					'tax_query' => array(
						array(
							'taxonomy' => 'type',
							'terms' => $type->term_id,
							'field' => 'id',
							'include_children' => false
						)
					)
				));

				foreach( $brands as $brand ) :
					$logos = get_section_info( 'use-case-section', 'consensus', $brand->ID );
				?>
					<div class="service-brand">
						<?php echo wp_kses_post( $logos['logos'] ); ?>
					</div>
				<?php endforeach; ?>
				</div>
			<?php endforeach; ?>

			<div class="services-see-all">
				<?php echo esc_html__( '+ SEE ALL', 'consensus-custom' ); ?>
			</div>
		</div>
	</div>
</div>