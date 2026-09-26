<?php
/**
 * Title: Evercrest approach
 * Slug: nad/evercrest-approach
 * Categories: text
 * Description: Sticky intro with image on the left, three numbered steps with timelines on the right.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

// Number => title, body, duration.
$nad_steps = array(
	'01' => array( 'Discovery', 'We begin with how you actually live — your routines, your taste, the site and its light. No templates, no assumptions.', '4–8 weeks' ),
	'02' => array( 'Design', 'Our architects translate that brief into a singular concept, refined together until every room earns its place.', '4–6 months' ),
	'03' => array( 'Build & handover', 'We manage construction end to end with trusted makers, then hand you the keys to a home that exists nowhere else.', '14–24 months' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"approach","className":"ec-wrap ec-approach","layout":{"type":"default"}} -->
<section id="approach" class="wp-block-group ec-wrap ec-approach">
	<!-- wp:group {"className":"ec-approach-intro ec-reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-approach-intro ec-reveal">
		<!-- wp:paragraph {"className":"ec-eyebrow"} -->
		<p class="ec-eyebrow">Our Approach</p>
		<!-- /wp:paragraph -->
		<!-- wp:heading -->
		<h2 class="wp-block-heading">From Blank<br>Page to Keys</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"className":"ec-body"} -->
		<p class="ec-body">Every Evercrest home follows the same path and ends somewhere entirely its own. Three steps, no shortcuts.</p>
		<!-- /wp:paragraph -->
		<!-- wp:group {"className":"ec-ph ec-approach-img","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-ph ec-approach-img">
			<!-- wp:image {"className":"ec-ph-img"} -->
			<figure class="wp-block-image ec-ph-img"><img src="<?php echo esc_url( wp_get_attachment_url( 13 ) ); ?>" alt=""/></figure>
			<!-- /wp:image -->
			<!-- wp:paragraph -->
			<p>architect sketches · studio photo</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"ec-steps ec-reveal-group","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-steps ec-reveal-group">
		<?php foreach ( $nad_steps as $nad_num => $nad_step ) : ?>
		<!-- wp:group {"className":"ec-step","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-step">
			<!-- wp:paragraph {"className":"ec-mono ec-step-num"} -->
			<p class="ec-mono ec-step-num"><?php echo esc_html( $nad_num ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading"><?php echo esc_html( $nad_step[0] ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"className":"ec-body"} -->
				<p class="ec-body"><?php echo esc_html( $nad_step[1] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:paragraph {"className":"ec-mono ec-step-time"} -->
			<p class="ec-mono ec-step-time"><?php echo esc_html( $nad_step[2] ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
