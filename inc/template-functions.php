<?php
/**
 * Add dropdown class to menu items with children
 */
add_filter( 'nav_menu_css_class', 'mpdef_menu_item_classes', 10, 3 );
function mpdef_menu_item_classes( $classes, $item, $args ) {
    if ( ! empty( $args->theme_location ) && 'header_menu' === $args->theme_location && in_array( 'menu-item-has-children', $classes, true ) ) {
        $classes[] = 'dropdown';
    }

    return $classes;
}

/**
 * Remove href="#" from menu items
 */
add_filter( 'wp_nav_menu_items', function ( $menu ) {
    return str_replace( '<a href="#"', '<a', $menu );
} );

/**
 * Year Shortcode
 * 
 * Usage: [mpdef_current_year_shortcode]
 */
if ( ! function_exists( 'mpdef_current_year_shortcode' ) ) {
	add_shortcode( 'mpdef_current_year_shortcode', 'mpdef_current_year_shortcode' );
	function mpdef_current_year_shortcode() {
        ob_start();

		echo date( 'Y' );

        return ob_get_clean();
	}
}

/**
 * SVG Support
 */
add_filter( 'upload_mimes', 'mpdef_svg_upload_allow' );
function mpdef_svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}
add_filter( 'wp_check_filetype_and_ext', 'mpdef_fix_svg_mime_type', 10, 5 );
function mpdef_fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ) {
	// WP 5.1 +
	if ( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) )
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
	else
		$dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );

	if ( $dosvg ) {
		if ( current_user_can( 'manage_options' ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		} else {
			$data['ext'] = $type_and_ext['type'] = false;
		}
	}

	return $data;
}
add_filter( 'wp_prepare_attachment_for_js', 'mpdef_show_svg_in_media_library' );
function mpdef_show_svg_in_media_library( $response ) {
	if ( $response['mime'] === 'image/svg+xml' ) {
		$response['image'] = [
			'src' => $response['url'],
		];
	}

	return $response;
}

/**
 * Add excerpt ellipsis ("...")
 */
if ( ! function_exists( 'mpdef_add_excerpt_ellipsis' ) ) {
add_filter( 'excerpt_more', 'mpdef_add_excerpt_ellipsis' );
	function mpdef_add_excerpt_ellipsis ( $more ) {
		return '...';
	}
}

/**
 * Pagination form search results, archive pages
 */
if ( ! function_exists( 'mpdef_pagination' ) ) {
	function mpdef_pagination() {
		global $wp_query;

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		if ( $wp_query->max_num_pages > 1 ) { ?>
			<nav class="posts-navigation">
				<?php
					echo paginate_links( 
						array(
							'total' => $wp_query->max_num_pages,
							'current' => $paged,
							'prev_text' => esc_html__( 'Prev', 'mpdef' ),
							'next_text' => esc_html__( 'Next', 'mpdef' ),
						) 
					);
				?>
			</nav>
		<?php 
		}
	}
}

/**
 * Disable Gutenberg editor for specific pages
 */
if ( ! function_exists( 'mpdef_disable_editor_for_specific_pages' ) ) {
	add_action( 'admin_init', 'mpdef_disable_editor_for_specific_pages' );
	function mpdef_disable_editor_for_specific_pages() {
		// Список слагів сторінок, для яких потрібно вимкнути редактор
		$disabled_pages = array( 
			// 'home',
			'about',
			// 'services'
		);

		// Отримуємо ID поточного запису
		$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
		if ( ! $post_id ) {
			return;
		}

		// Отримуємо об'єкт сторінки
		$post = get_post( $post_id );
		if ( ! $post || 'page' !== get_post_type( $post_id ) ) {
			return;
		}

		// Перевіряємо, чи це головна сторінка
		$is_front_page = ( get_option( 'page_on_front' ) == $post_id );

		// Якщо це головна сторінка або слаг у списку, вимикаємо редактор
		if ( $is_front_page || in_array( $post->post_name, $disabled_pages, true ) ) {
			remove_post_type_support( 'page', 'editor' );
		}
	}
}

/**
 * Templates include
 */
if ( ! function_exists( 'mpdef_templates_include' ) ) {  
	add_filter( 'template_include', 'mpdef_templates_include' );
	function mpdef_templates_include( $template ) {
		if ( is_page( 'about' ) ) {
			$theme_files = array( 'about-page.php', 'templates/about-page.php', 'tpl/about-page.php' );
			$exist = locate_template( $theme_files, false );
	
			if ( $exist != '' ) {
				return $exist;
			} else {
				return get_template_directory() . '/page.php';
			}
		}

		return $template;
	}
}