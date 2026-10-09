<?php
/**
 * The main template file
 */

get_header();

if ( have_posts() ) {
	get_template_part( 'template-parts/main/hero-section' );

	while ( have_posts() ) {
		the_post();
		
		the_title( '<h1>', '</h1>' );
		// get_template_part( 'template-parts/content', get_post_type() );
	}

	mpdef_pagination();
} else {
	// get_template_part( 'template-parts/content', 'none' );
}

get_footer();
