<?php
/**
 * Disable Login by Email in WordPress
 */
remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );

/**
 * Disable the ability to edit files in the admin panel for themes and plugins
 */
define( 'DISALLOW_FILE_EDIT', true );

/**
 * Removing license.txt and readme.html files for protection
 */
if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
	$license_file = ABSPATH . '/license.txt';
	$readme_file = ABSPATH . '/readme.html';

	if ( file_exists( $license_file ) && is_admin() ) {
		$deleted = unlink( $license_file ) && unlink( $readme_file );

		if ( ! $deleted )
			$GLOBALS['readmedel'] = 'It was not possible to delete files: license.txt and readme.html from the folder `'. ABSPATH .'`. Remove them manually!';
		else
			$GLOBALS['readmedel'] = 'Files: license.txt and readme.html are removed from the folder `'. ABSPATH .'`.';

		add_action( 'admin_notices', function() {  echo '<div class="error is-dismissible"><p>'. $GLOBALS['readmedel'] .'</p></div>'; } );
	}
}

/**
 * Hide the admin bar from the public section of the site when logged in as an admin
 */
//add_filter( 'show_admin_bar', '__return_false' );