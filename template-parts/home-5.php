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
	<div class="case-study-wrap">
		<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
			<div class="background-title">
				<?php echo esc_html( $section_info['title'] ); ?>
			</div>
		<?php endif; ?>

		<div class="case-study-left">
			<div id="<?php echo esc_attr( $types[0]->term_id . '-type' ); ?>" class="case-study-brands">
				<?php $brands = get_posts(array(
					'post_type' => 'use-case',
					'numberposts' => 4,
					'tax_query' => array(
						array(
							'taxonomy' => 'type',
							'terms' => $types[0]->term_id,
							'field' => 'id',
							'include_children' => false
						)
					)
				));

				foreach( $brands as $brand_num => $brand ) : ?>
					<div data-brand="<?php echo esc_attr( $brand->ID ); ?>" class="case-study-brand<?php echo 0 === $brand_num ? esc_attr( ' selected' ) : ''; ?>">
						<?php echo esc_html( $brand->post_title ); ?>
					</div>
				<?php
				endforeach;

				$photos = get_section_info( 'use-case-section', 'consensus', $brands[0]->ID );
				$photos = isset( $photos['images'] ) ? $photos['images'] : array();
				?>
			</div>
			<div class="case-study-brand-photos">
				<?php foreach ( $photos as $photo ) : ?>
					<img src="<?php echo esc_url( $photo ); ?>">
				<?php endforeach; ?>
			</div>
		</div>
		<div class="case-study-right">
			<?php $count = count( $types ) - 1; foreach( array_reverse( $types ) as $type_num => $type ) : ?>
				<div data-cs-type="<?php echo esc_html( $type->term_id . '-type' ); ?>" class="case-study-type<?php echo $count === $type_num ? esc_attr( ' selected' ) : ''; ?>">
					<?php echo esc_html( $type->name ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>