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
			<?php foreach( $types as $num => $type ) : ?>
				<div class="service-type">
					<div data-type="<?php echo esc_attr( $type->name ); ?>" class="service-name<?php echo 0 === $num ? ' selected' : ''; ?>">
						<?php echo esc_html( $type->name ); ?>
					</div>
					<div class="service-desc<?php echo 0 === $num ? ' selected' : ''; ?>">
						<?php echo esc_html( wp_trim_words( $type->description, 25, '...' ) ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="services-right">
			<?php foreach( $types as $count => $type ) : ?>
				<div data-type="<?php echo esc_attr( $type->name ); ?>" class="service-brands<?php echo 0 === $count ? ' selected' : ''; ?>">
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

				foreach( $brands as $brand_num => $brand ) :
					$logos = get_section_info( 'use-case-section', 'consensus', $brand->ID );
					$black_logos = isset( $logos['black-logos'] ) ? $logos['black-logos'] : '';
					$use_case = get_post_permalink( $brand->ID );
				?>
					<div class="service-brand">
						<a href="<?php echo esc_url( $use_case ); ?>" class="inner-brand">
							<?php echo wp_kses_post( $black_logos ); ?>
						</a>
					</div>
				<?php endforeach; ?>
					<a href="/great-brand-show" class="service-brand see-all">
						<?php echo esc_html__( '+ SEE ALL', 'consensus-custom' ); ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>