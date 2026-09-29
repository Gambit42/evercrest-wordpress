<?php
/**
 * Title: Evercrest testimonials
 * Slug: nad/evercrest-testimonial
 * Categories: testimonials
 * Description: Client quotes in a carousel with arrows and a counter.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

$nad_quotes = function_exists( 'evercrest_core_testimonials' ) ? evercrest_core_testimonials() : array();
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ec-testimonial ec-carousel","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull ec-testimonial ec-carousel">
	<!-- wp:group {"className":"ec-wrap ec-testimonial-inner ec-reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-wrap ec-testimonial-inner ec-reveal">
		<!-- wp:group {"className":"ec-track swiper","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-track swiper">
			<!-- wp:group {"className":"swiper-wrapper","layout":{"type":"default"}} -->
			<div class="wp-block-group swiper-wrapper">
			<?php foreach ( $nad_quotes as $nad_q ) : ?>
			<!-- wp:group {"className":"ec-quote-slide swiper-slide","layout":{"type":"default"}} -->
			<div class="wp-block-group ec-quote-slide swiper-slide">
				<!-- wp:group {"className":"ec-ph ec-quote-img","layout":{"type":"default"}} -->
				<div class="wp-block-group ec-ph ec-quote-img">
					<?php if ( has_post_thumbnail( $nad_q ) ) : ?>
						<!-- wp:image {"className":"ec-ph-img"} -->
						<figure class="wp-block-image ec-ph-img"><?php echo get_the_post_thumbnail( $nad_q, 'large', array( 'alt' => get_the_title( $nad_q ) ) ); ?></figure>
						<!-- /wp:image -->
					<?php endif; ?>
					<!-- wp:paragraph -->
					<p>client portrait</p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
				<!-- wp:group {"className":"ec-quote-body","layout":{"type":"default"}} -->
				<div class="wp-block-group ec-quote-body">
					<!-- wp:paragraph {"className":"ec-quote-mark"} -->
					<p class="ec-quote-mark" aria-hidden="true">“</p>
					<!-- /wp:paragraph -->
					<!-- wp:paragraph {"className":"ec-quote"} -->
					<p class="ec-quote"><?php echo esc_html( wp_strip_all_tags( $nad_q->post_content ) ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"className":"ec-quote-cite","layout":{"type":"default"}} -->
					<div class="wp-block-group ec-quote-cite">
						<!-- wp:paragraph {"className":"ec-quote-name"} -->
						<p class="ec-quote-name"><?php echo esc_html( get_the_title( $nad_q ) ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"className":"ec-quote-role"} -->
						<p class="ec-quote-role"><?php echo esc_html( evercrest_core_meta( $nad_q->ID, 'attribution' ) ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
			<?php endforeach; ?>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
		<!-- wp:html -->
		<div class="ec-carousel-nav">
			<span class="ec-mono" data-ec-count>01 / <?php echo esc_html( str_pad( (string) count( $nad_quotes ), 2, '0', STR_PAD_LEFT ) ); ?></span>
			<button type="button" class="ec-arrow" data-ec-dir="-1" aria-label="Previous testimonial">←</button>
			<button type="button" class="ec-arrow" data-ec-dir="1" aria-label="Next testimonial">→</button>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
