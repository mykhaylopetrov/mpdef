<?php
/**
 * The template for displaying 404 pages
 */

get_header();
?>

<section class="error-404">
	<div class="error-404__container">
		<div class="error-404__title">
			<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'mpdef' ); ?></h1>
		</div>
		<div class="error-404__subtitle">
			<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'mpdef' ); ?></p>
		</div>
		<div class="error-404__btn">
			<button class="btn btn--home" type="button"><a href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'Home', 'mpdef' ); ?></a></button>
		</div>
	</div>
</section>

<?php
get_footer();
