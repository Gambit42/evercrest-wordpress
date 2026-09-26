<?php
/**
 * Title: Evercrest contact
 * Slug: nad/evercrest-contact
 * Categories: call-to-action
 * Description: Dark closing section with contact details and a viewing request form.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since nad 1.0
 */

$nad_interests = array( 'New build', 'Existing residence', 'Land search', 'Renovation' );
$nad_budgets   = array( '< $5M', '$5–10M', '$10–20M', '$20M+' );
?>
<!-- wp:group {"tagName":"section","anchor":"contact","align":"full","className":"ec-contact","backgroundColor":"contrast","textColor":"base","layout":{"type":"default"}} -->
<section id="contact" class="wp-block-group alignfull ec-contact has-base-color has-contrast-background-color has-text-color has-background">
	<!-- wp:group {"className":"ec-wrap ec-contact-inner","layout":{"type":"default"}} -->
	<div class="wp-block-group ec-wrap ec-contact-inner">
		<!-- wp:group {"className":"ec-contact-copy ec-reveal","layout":{"type":"default"}} -->
		<div class="wp-block-group ec-contact-copy ec-reveal">
			<!-- wp:paragraph {"className":"ec-eyebrow"} -->
			<p class="ec-eyebrow">Begin</p>
			<!-- /wp:paragraph -->
			<!-- wp:heading {"className":"ec-h2-xxl"} -->
			<h2 class="wp-block-heading ec-h2-xxl">Let’s Build<br>Yours</h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"className":"ec-contact-lead"} -->
			<p class="ec-contact-lead">Placeholder closing copy inviting prospective clients to start a conversation with the studio.</p>
			<!-- /wp:paragraph -->
			<!-- wp:group {"className":"ec-contact-details","layout":{"type":"default"}} -->
			<div class="wp-block-group ec-contact-details">
				<!-- wp:paragraph -->
				<p><span class="ec-mono">Email</span><a href="mailto:hello@evercrest.estates">hello@evercrest.estates</a></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><span class="ec-mono">Phone</span><a href="tel:+12125550148">+1 (212) 555-0148</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:html -->
		<div class="ec-form-panel ec-reveal">
			<form class="ec-form">
				<div class="ec-form-row">
					<label class="ec-field"><span class="ec-mono">Name</span><input name="name" placeholder="Full name" autocomplete="name"></label>
					<label class="ec-field"><span class="ec-mono">Email</span><input type="email" name="email" placeholder="you@email.com" autocomplete="email"></label>
				</div>
				<fieldset class="ec-choices">
					<legend class="ec-mono">I’m interested in</legend>
					<div class="ec-chips">
						<?php foreach ( $nad_interests as $nad_n => $nad_label ) : ?>
						<button type="button" aria-pressed="<?php echo 0 === $nad_n ? 'true' : 'false'; ?>"><?php echo esc_html( $nad_label ); ?></button>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<fieldset class="ec-choices">
					<legend class="ec-mono">Budget</legend>
					<div class="ec-chips ec-chips-single">
						<?php foreach ( $nad_budgets as $nad_label ) : ?>
						<button type="button" aria-pressed="<?php echo '$10–20M' === $nad_label ? 'true' : 'false'; ?>"><?php echo esc_html( $nad_label ); ?></button>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<button type="submit" class="ec-submit">Book a viewing</button>
			</form>
			<div class="ec-form-done" hidden>
				<p class="ec-form-done-title">Thank you.</p>
				<p>A member of the studio will be in touch within two working days.</p>
			</div>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
