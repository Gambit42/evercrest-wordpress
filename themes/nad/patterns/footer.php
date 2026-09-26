<?php
/**
 * Title: Footer
 * Slug: nad/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Evercrest footer: brand statement, link columns, copyright and social links.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

?>
<!-- wp:group {"align":"full","className":"ec-footer","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ec-footer">
	<!-- wp:group {"className":"ec-footer-cols","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-footer-cols">
		<!-- wp:group {"className":"ec-footer-about-col","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-footer-about-col">
			<!-- wp:group {"className":"ec-brand","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group ec-brand">
				<!-- wp:image {"width":"auto","height":"24px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/evercrest/evercrestlogo.svg' ); ?>" alt="Evercrest" style="width:auto;height:24px"/></figure>
				<!-- /wp:image -->
				<!-- wp:site-title {"level":0,"className":"ec-wordmark"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:paragraph {"className":"ec-footer-about","textColor":"accent-4"} -->
			<p class="ec-footer-about has-accent-4-color has-text-color">We compose private residences and estates from a blank page — no templates, no repeated floorplans, no shortcuts.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"className":"ec-eyebrow"} -->
			<h3 class="wp-block-heading ec-eyebrow">Explore</h3>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"ec-links"} -->
			<ul class="wp-block-list ec-links">
				<!-- wp:list-item --><li><a href="/#residences">Residences</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="#">Index</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="#">Journal</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="#">Studio</a></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"className":"ec-eyebrow"} -->
			<h3 class="wp-block-heading ec-eyebrow">Company</h3>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"ec-links"} -->
			<ul class="wp-block-list ec-links">
				<!-- wp:list-item --><li><a href="#">About</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="/#approach">Our approach</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="#">Press</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="#">Careers</a></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:heading {"level":3,"className":"ec-eyebrow"} -->
			<h3 class="wp-block-heading ec-eyebrow">Contact</h3>
			<!-- /wp:heading -->
			<!-- wp:list {"className":"ec-links"} -->
			<ul class="wp-block-list ec-links">
				<!-- wp:list-item --><li><a href="#contact">Book a viewing</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="mailto:hello@evercrest.estates">hello@evercrest.estates</a></li><!-- /wp:list-item -->
				<!-- wp:list-item --><li><a href="tel:+12125550148">+1 (212) 555-0148</a></li><!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:paragraph {"className":"ec-footer-mark"} -->
	<p class="ec-footer-mark" aria-hidden="true">Evercrest</p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"ec-footer-bar","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-footer-bar">
		<!-- wp:paragraph {"textColor":"accent-4"} -->
		<p class="has-accent-4-color has-text-color">© <?php echo esc_html( gmdate( 'Y' ) ); ?> Evercrest Estates. All rights reserved.</p>
		<!-- /wp:paragraph -->
		<!-- wp:paragraph {"className":"ec-social"} -->
		<p class="ec-social"><a href="#">Instagram</a><a href="#">Pinterest</a><a href="#">LinkedIn</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
