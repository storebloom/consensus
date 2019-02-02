<?php
/**
 * Template part for portfolio use case types
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<div class="portfolio-section">
	<div class="portfolio-title">
		<?php echo esc_html( $type->name ); ?>
	</div>
	<div class="portfolio-desc">
		<?php echo wp_kses_post( $type->description ); ?>
	</div>

	<div class="portfolio-brands">
		<?php
		$brands = get_posts(array(
			'post_type' => 'use-case',
			'numberposts' => -1,
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
			if ( 10 > $brand_num ) {
				$usecase  = get_section_info( 'use-case-section', 'consensus', $brand->ID );
				$logos    = isset( $usecase['logos'] ) ? $usecase['logos'] : '';
				$subtitle = isset( $usecase['subtitle'] ) ? $usecase['subtitle'] : '';
				$link     = get_post_permalink( $brand->ID );

				include locate_template( 'template-parts/portfolio-2.php' );
			}
		endforeach; ?>
	</div>

	<?php if ( count( $brands ) > 10 ) : ?>
		<div class="portfolio-see-all" data-type="<?php echo esc_attr( $type->term_id ); ?>">
			<?php echo esc_html__( 'See All', 'consensus-custom' ); ?>
		</div>
	<?php endif; ?>
</div>
