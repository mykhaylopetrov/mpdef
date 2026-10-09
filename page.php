<?php
/**
 * Template part for displaying page content in page.php
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'article' ) ); ?>>
		<?php get_template_part( 'template-parts/default-page/hero-section' ); ?>
		<?php get_template_part( 'template-parts/default-page/article-content-section' ); ?>
	</article>

<?php
endwhile;

get_footer();