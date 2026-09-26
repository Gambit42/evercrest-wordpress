<?php
/**
 * Title: Evercrest journal
 * Slug: nad/evercrest-journal
 * Categories: text
 * Description: Latest posts (Posts menu) in a swipeable carousel with arrows.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

?>
<!-- wp:group {"tagName":"section","anchor":"journal","className":"ec-wrap ec-journal ec-carousel","layout":{"type":"default"}} -->
<section id="journal" class="wp-block-group ec-wrap ec-journal ec-carousel">
	<!-- wp:group {"className":"ec-section-head ec-reveal","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-section-head ec-reveal">
		<!-- wp:group {"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"ec-eyebrow"} -->
			<p class="ec-eyebrow">Journal</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading -->
			<h2 class="wp-block-heading">Notes From the Studio</h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"className":"ec-head-actions","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-head-actions">
			<!-- wp:html -->
			<div class="ec-carousel-nav">
				<button type="button" class="ec-arrow" data-ec-dir="-1" aria-label="Previous entries">←</button>
				<button type="button" class="ec-arrow" data-ec-dir="1" aria-label="Next entries">→</button>
			</div>
			<!-- /wp:html -->
			<!-- wp:paragraph {"className":"ec-link"} -->
			<p class="ec-link"><a href="/journal/">All entries</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"ec-track ec-journal-track swiper","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-track ec-journal-track swiper">
		<!-- wp:nad/journal /-->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
