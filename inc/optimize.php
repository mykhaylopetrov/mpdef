<?php
/**
 * Disable Emojis
 */
if ( ! function_exists( 'mpdef_disable_emojis' ) ) {
	add_action( 'init', 'mpdef_disable_emojis' );
	function mpdef_disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_empty_string' );
		add_filter( 'tiny_mce_plugins', 'mpdef_disable_emojis_tinymce' );
		add_filter( 'wp_resource_hints', 'mpdef_disable_emojis_remove_dns_prefetch', 10, 2 );
	}
	function mpdef_disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
	function mpdef_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' == $relation_type ) {
			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			foreach ( $urls as $key => $url ) {
				if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
					unset( $urls[$key] );
				}
			}
		}

		return $urls;
	}
}

/**
 * Disable WordPress Revisions
 * 
 * Remove existing revisions from the database with the following SQL queries:
 * 
 * DELETE FROM wp_postmeta WHERE post_id IN (SELECT ID FROM wp_posts WHERE post_type = 'revision' AND post_name LIKE '%revision%');
 * DELETE FROM wp_term_relationships WHERE object_id IN (SELECT ID FROM wp_posts WHERE post_type = 'revision' AND post_name LIKE '%revision%');
 * DELETE FROM wp_posts WHERE post_type = 'revision' AND post_name LIKE '%revision%';
 */
if ( ! function_exists( 'mpdef_revisions_to_keep' ) ) {
	add_filter( 'wp_revisions_to_keep', 'mpdef_revisions_to_keep' );
	function mpdef_revisions_to_keep( $revisions ) {
		return 0;
	}
}

/**
 * Disable Auto-save for Posts in the Browser
 */
if ( ! function_exists( 'mpdef_disable_autosave' ) ) {
	add_action( 'wp_print_scripts', 'mpdef_disable_autosave' );
	add_action( 'admin_init', 'mpdef_disable_autosave' );
	function mpdef_disable_autosave() {
		wp_deregister_script( 'autosave' );
	}
}

/**
 * Disable Auto-save for Posts in Gutenberg
 * Just increase the save interval to 1 week - 604800 seconds
 * Check the value in the array - only when the editor is open:
 *
 * var_dump( $editor_settings['autosaveInterval'] ); 
 */
if ( ! function_exists( 'mpdef_change_gutenberg_autosave_interval' ) ) {
	add_filter( 'block_editor_settings_all', 'mpdef_change_gutenberg_autosave_interval', 10, 2 );
	function mpdef_change_gutenberg_autosave_interval( $editor_settings, $post ) {
		$editor_settings['autosaveInterval'] = 604800;

		return $editor_settings;
	}
}

/**
 * Disable Widgets from WordPress Dashboard
 */
if ( ! function_exists( 'mpdef_clear_wp_dash' ) ) {
	add_action( 'wp_dashboard_setup', 'mpdef_clear_wp_dash' );
	function mpdef_clear_wp_dash() {
		$dash_side   = & $GLOBALS['wp_meta_boxes']['dashboard']['side']['core'];
		$dash_normal = & $GLOBALS['wp_meta_boxes']['dashboard']['normal']['core'];

		unset( $dash_side['dashboard_quick_press'] );
		unset( $dash_side['dashboard_primary'] );
		unset( $dash_side['dashboard_secondary'] );
		unset( $dash_side['dashboard_recent_drafts'] );
		unset( $dash_normal['dashboard_right_now'] );
		unset( $dash_normal['dashboard_activity'] );
		unset( $dash_normal['dashboard_incoming_links'] );
		unset( $dash_normal['dashboard_recent_comments'] );
		unset( $dash_normal['dashboard_plugins'] );
		unset( $dash_normal['dashboard_site_health'] );

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}
}

/**
 * Remove the X-Pingback link and disable XML-RPC (site.com/xmlrpc.php)
 * 
 * <link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://site.com/xmlrpc.php?rsd" />
 * <link rel="pingback" href="http://site.com/xmlrpc.php">
 * 
 * Even after these steps, the file site.com/xmlrpc.php is still accessible and will open. You need to block access to it via
 * .htaccess so that it returns a 404 error.
 */
add_filter( 'wp_headers', 'mpdef_remove_pingback_header' );
add_filter( 'template_redirect', 'mpdef_remove_x_pingback_headers' );
add_filter( 'xmlrpc_methods', 'mpdef_block_xmlrpc_attacks' );
add_action( 'pre_ping', 'mpdef_disable_pingback' );
add_filter( 'pre_update_option_enable_xmlrpc', '__return_false' );
add_filter( 'pre_option_enable_xmlrpc', '__return_zero' );
// We'll disable the ability to publish via xmlrpc.php
add_filter( 'xmlrpc_enabled','__return_false' );
// Disable X-Pingback HTTP Header. 
function mpdef_remove_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
function mpdef_remove_x_pingback_headers( $headers ) {
    if ( function_exists( 'header_remove' ) ) {
        header_remove( 'X-Pingback' );
        header_remove( 'Server' );
	}
}
function mpdef_block_xmlrpc_attacks( $methods ) {
	unset( $methods['system.multicall'] );
    unset( $methods['system.listMethods'] );
    unset( $methods['system.getCapabilities'] );
    unset( $methods['pingback.extensions.getPingbacks'] );
    unset( $methods['pingback.ping'] );
    unset( $methods['wp.getUsersBlogs'] );

	return $methods;
}
function mpdef_disable_pingback( &$links ) {
    foreach ( $links as $l => $link )
    if ( 0 === strpos( $link, get_option( 'home' ) ) )
    unset( $links[$l] );
}

/**
 * Remove links to wordpress.org from the admin bar
 */
if ( ! function_exists( 'mpdef_remove_admin_bar_links' ) ) {
	add_action( 'wp_before_admin_bar_render', 'mpdef_remove_admin_bar_links' );
	function mpdef_remove_admin_bar_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
		$wp_admin_bar->remove_menu( 'about' );
		$wp_admin_bar->remove_menu( 'wporg' );
		$wp_admin_bar->remove_menu( 'documentation' );
		$wp_admin_bar->remove_menu( 'support-forums' );
		$wp_admin_bar->remove_menu( 'feedback' );
		$wp_admin_bar->remove_menu( 'view-site' );
	}
}

/**
 * Remove the short link /?p=
 * 
 * <link rel='shortlink' href='http://site.com/?p=xxx' />
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Remove links to the previous, next post, disable links in <head>
 * <link rel='prev' .../>
 * <link rel='next' .../>
 */
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
add_filter( 'wpseo_next_rel_link', '__return_false' );
add_filter( 'wpseo_prev_rel_link', '__return_false' );
add_filter( 'aioseo_next_link', '__return_false' );
add_filter( 'aioseo_prev_link', '__return_false' );

/**
 * Remove WLW Manifest link
 */
remove_action( 'wp_head', 'wlwmanifest_link' );

/**
 * Remove RSD link
 * 
 * <link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://site.com/xmlrpc.php?rsd" />
 */
remove_action( 'wp_head', 'rsd_link' );

/**
 * Remove dns-prefetch link
 */
remove_action( 'wp_head', 'wp_resource_hints', 2 );

/**
 * Remove usage of jquery-migrate.min.js
 * 
 * <script src='http://site.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.4.1' id='jquery-migrate-js'></script>
 */
add_action( 'wp_default_scripts', 'mpdef_remove_jquery_migrate' );
add_action( 'wp_print_scripts', 'mpdef_remove_jquery_migrate_2', 100 );
function mpdef_remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];
		 if ( $script->deps ) {
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		 }
	}
}
function mpdef_remove_jquery_migrate_2() {
	wp_dequeue_script( 'jquery-migrate' );
}

/**
 * Remove meta name generator and hide WordPress version
 * 
 * <meta name="generator" content="WordPress 6.3.2" />
 */
add_filter( 'the_generator', '__return_empty_string' );
remove_action( 'wp_head', 'wp_generator' );

/**
 * Remove in <head> link
 * 
 * <link rel='https://api.w.org/' href='https://site.com/wp-json/' />
 */
remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0 );
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

/**
 * Remove the link to the wp-includes/js/wp-embed.min.js file
 */
add_action( 'wp_footer', 'mpdef_disable_embed' );
add_action( 'init', 'mpdef_speed_stop_loading_wp_embed' );
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
function mpdef_speed_stop_loading_wp_embed() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'wp-embed' );
    }
}
function mpdef_disable_embed() {
    wp_dequeue_script( 'wp-embed' );
}

/**
 * Disable the creation of copies of images generated by WordPress itself
 */
add_action( 'intermediate_image_sizes_advanced', 'mpdef_disable_image_sizes' );
// Disabling scalable image sizes
add_filter( 'big_image_size_threshold', '__return_false' );
add_action( 'init', 'mpdef_disable_other_image_sizes' );
// Disabling generated image sizes
function mpdef_disable_image_sizes( $sizes ) {
	//unset( $sizes['thumbnail'] );  // Disabling thumbnails
	unset( $sizes['medium'] );       // Disabling medium size
	unset( $sizes['large'] );        // Disabling large size
	unset( $sizes['medium_large'] ); // Disabling medium large size
	unset( $sizes['1536x1536'] );    // Disabling 2x medium large size 
	unset( $sizes['2048x2048'] );    // Disabling 2x large size

	return $sizes;
}
// Disabling other image sizes
function mpdef_disable_other_image_sizes() {
	remove_image_size( 'post-thumbnail' ); // Disabling images added via set_post_thumbnail_size() 
	remove_image_size( 'another-size' );   // Disabling other added image sizes, which are defined through add_image_size() in themes
}

/**
 * Disabling and Replacing French “Yelochka” Quotation Marks with Typewriter-Style Double Quotation Marks 
 */
add_filter( 'run_wptexturize', '__return_false' ); 
remove_filter( 'the_title', 'wptexturize' );
remove_filter( 'the_excerpt', 'wptexturize' );
remove_filter( 'comment_text', 'wptexturize' );
remove_filter( 'list_cats', 'wptexturize' );
remove_filter( 'single_post_title', 'wptexturize' );
remove_filter( 'comment_author', 'wptexturize' );
remove_filter( 'term_name', 'wptexturize' );
remove_filter( 'link_name', 'wptexturize' );
remove_filter( 'link_description', 'wptexturize' );
remove_filter( 'link_notes', 'wptexturize' );
remove_filter( 'bloginfo', 'wptexturize' );
remove_filter( 'wp_title', 'wptexturize' );
remove_filter( 'widget_title', 'wptexturize' );
remove_filter( 'the_content', 'wptexturize' );

/**
 * Disabling the mandatory check for new versions of WP, plugins and theme in the admin panel,
 * so that it doesn't slow down when you haven't logged in for a long time and then log in...
 * All checks will take place unnoticed via cron or when you visit the page: "Console > Updates".
 */
if ( is_admin() ) {
	remove_action( 'admin_init', '_maybe_update_core' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_themes' );

	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-themes.php', 'wp_update_themes' );

	add_filter( 'pre_site_transient_browser_'. md5( $_SERVER['HTTP_USER_AGENT'] ), '__return_empty_array' );
}