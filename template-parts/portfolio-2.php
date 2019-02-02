<?php
/**
 * Template part for portfolio brands
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ConsensusCustom
 */

?>
<a href="<?php echo esc_url( $link ); ?>" class="portfolio-brands-item">
	<div class="portfolio-brand-logos">
		<?php echo wp_kses_post( $logos ); ?>
	</div>
	<div class="portfolio-use-case-title">
		<?php echo esc_html__( 'Advisor to ', 'consensus-custom' ) . $brand->post_title; ?>
		<span class="use-case-subtitle">
							<?php echo esc_html( $subtitle ); ?>
						</span>
		<span class="read-more-port" ><?php echo esc_html__( 'Read More +', 'consensus-custom' ); ?></span>
	</div>
</a>
