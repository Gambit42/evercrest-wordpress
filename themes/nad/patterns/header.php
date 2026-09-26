<?php
/**
 * Title: Header
 * Slug: nad/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Evercrest sticky header: logo + wordmark, pill navigation, phone and booking button.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

?>
<!-- wp:group {"align":"full","className":"ec-nav","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ec-nav">
	<!-- wp:group {"className":"ec-wrap ec-nav-inner","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-wrap ec-nav-inner">
		<!-- wp:group {"className":"ec-brand","layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group ec-brand">
			<!-- wp:image {"width":"auto","height":"18px","sizeSlug":"full","linkDestination":"custom"} -->
			<figure class="wp-block-image size-full is-resized"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/evercrest/evercrestlogo.svg' ); ?>" alt="Evercrest" style="width:auto;height:18px"/></a></figure>
			<!-- /wp:image -->
			<!-- wp:site-title {"level":0,"className":"ec-wordmark"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:navigation {"className":"ec-pill","overlayMenu":"always","layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:navigation-link {"label":"Residences","url":"/#residences","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Index","url":"/#index","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Journal","url":"/#journal","kind":"custom"} /-->
			<!-- wp:navigation-link {"label":"Studio","url":"/#approach","kind":"custom"} /-->
		<!-- /wp:navigation -->

		<!-- wp:group {"className":"ec-nav-actions","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-nav-actions">
			<!-- wp:paragraph {"className":"ec-nav-phone"} -->
			<p class="ec-nav-phone"><a href="tel:+12125550148">+1 (212) 555-0148</a></p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons {"className":"ec-btn-sm"} -->
			<div class="wp-block-buttons ec-btn-sm">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#contact">Book a viewing</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
