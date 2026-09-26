<?php
/**
 * Title: Evercrest hero
 * Slug: nad/evercrest-hero
 * Categories: banner
 * Description: Centered display headline and subtitle over a full-width four-image strip pinched by a concave curve.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

// Alt text => Media Library attachment ID, left to right.
$nad_shots = array(
	'Modern luxury home exterior with a pool'          => 10,
	'Contemporary white villa with clean lines'        => 11,
	'Timber and glass residence at dusk'               => 12,
	'White pavilion residence beside the pool terrace' => 13,
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ec-hero","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull ec-hero">
	<!-- wp:group {"className":"ec-hero-top","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-hero-top">
		<!-- wp:heading {"textAlign":"center","level":1} -->
		<h1 class="wp-block-heading has-text-align-center">Homes Unlike<br>Anything Else</h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","className":"ec-hero-lead"} -->
		<p class="has-text-align-center ec-hero-lead">The home you deserve has never been built before. We design and deliver one-of-a-kind private estates — start to finish.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:html -->
	<svg width="0" height="0" aria-hidden="true" style="position:absolute"><clipPath id="ec-hero-curve" clipPathUnits="objectBoundingBox"><path d="M0,0 Q0.5,0.16 1,0 L1,1 Q0.5,0.84 0,1 Z"/></clipPath></svg>
	<!-- /wp:html -->

	<!-- wp:group {"className":"ec-hero-strip","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-hero-strip">
		<?php foreach ( $nad_shots as $nad_alt => $nad_img ) : ?>
		<!-- wp:group {"className":"ec-ph","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-ph">
			<!-- wp:image {"className":"ec-ph-img"} -->
			<figure class="wp-block-image ec-ph-img"><img src="<?php echo esc_url( wp_get_attachment_url( $nad_img ) ); ?>" alt="<?php echo esc_attr( $nad_alt ); ?>"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
