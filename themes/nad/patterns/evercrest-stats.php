<?php
/**
 * Title: Evercrest stats
 * Slug: nad/evercrest-stats
 * Categories: text
 * Description: Dark band with four key figures.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

$nad_stats = array(
	'86'   => 'Residences delivered',
	'14'   => 'Countries',
	'100%' => 'Bespoke designs',
	'31'   => 'Design awards',
);
?>
<!-- wp:group {"tagName":"section","align":"full","className":"ec-stats-band","backgroundColor":"contrast","textColor":"base","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull ec-stats-band has-base-color has-contrast-background-color has-text-color has-background">
	<!-- wp:group {"className":"ec-wrap ec-stats ec-reveal-group","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-wrap ec-stats ec-reveal-group">
		<?php foreach ( $nad_stats as $nad_value => $nad_label ) : ?>
		<!-- wp:group {"className":"ec-stat-item","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-stat-item">
			<!-- wp:paragraph {"className":"ec-stat"} -->
			<p class="ec-stat"><?php echo esc_html( $nad_value ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"className":"ec-stat-label"} -->
			<p class="ec-stat-label"><?php echo esc_html( $nad_label ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
