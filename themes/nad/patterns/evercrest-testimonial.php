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

// Quote, client, residence, portrait (Media Library attachment ID).
// Sample testimonials with Pexels stock portraits: swap in real client quotes and photos before launch.
$nad_quotes = array(
	array( 'We came to Evercrest with a site and a feeling. Thirty months later we have a house that answers every question the mountain asks of it — and a winter heating bill a third of what we used to pay.', 'Daniel Whitmore', 'Owner, The Aspen Residence', 40 ),
	array( 'They walked the bluff at dawn, at noon and at dusk before drawing a single line. That patience is in every room. When the fog rolls in, the house simply belongs to it.', 'Claire Ashford', 'Owner, Glasshouse on the Bluff', 41 ),
	array( 'What surprised us most was the calm. One team, one point of contact, no surprises on the budget — and a garden already growing the day we moved in.', 'Elena Marchetti', 'Owner, Villa Serena', 42 ),
);
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
					<!-- wp:image {"className":"ec-ph-img"} -->
					<figure class="wp-block-image ec-ph-img"><img src="<?php echo esc_url( wp_get_attachment_url( $nad_q[3] ) ); ?>" alt="<?php echo esc_attr( $nad_q[1] ); ?>"/></figure>
					<!-- /wp:image -->
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
					<p class="ec-quote"><?php echo esc_html( $nad_q[0] ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"className":"ec-quote-cite","layout":{"type":"default"}} -->
					<div class="wp-block-group ec-quote-cite">
						<!-- wp:paragraph {"className":"ec-quote-name"} -->
						<p class="ec-quote-name"><?php echo esc_html( $nad_q[1] ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"className":"ec-quote-role"} -->
						<p class="ec-quote-role"><?php echo esc_html( $nad_q[2] ); ?></p>
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
