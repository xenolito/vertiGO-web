<?php
/**
 * pictau_tw functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package pictau_tw
 */

if ( ! defined( 'PICTAU_VERSION' ) ) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define( 'PICTAU_VERSION', '0.1.0' );
}

if ( ! defined( 'PICTAU_TYPOGRAPHY_CLASSES' ) ) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `pictau_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'PICTAU_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if ( ! function_exists( 'pictau_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function pictau_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on pictau_tw, use a find and replace
		 * to change 'pictau' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'pictau', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );


		/*
		 * Enable support for Custom logo.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
		 */
		add_theme_support( 'custom-logo' );


		/*
		 * Enable support for Custom spacing for blocks.
		 *
		 * @link https://developer.wordpress.org/news/2023/03/22/everything-you-need-to-know-about-spacing-in-block-themes/
		 */

		add_theme_support( 'custom-spacing' );

		// This theme uses wp_nav_menu() in 4 locations.
		register_nav_menus(
			array(
				'menu-1' => __( 'Primary', 'pictau' ),
				'menu-1-mobile'	=> __('Mobile', 'pictau'),
				'top_menu' => __( 'Top Menu', 'pictau'),
				'menu-2' => __( 'Footer Menu', 'pictau' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'widgets' );
		add_theme_support( 'widgets-block-edior' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );
		add_editor_style( 'style-editor-extra.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', 'pictau_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function pictau_widgets_init() {
	register_sidebar(
		array(
			'id'            => 'primary',
			'name'          => __( 'Primary Sidebar', 'pictau' ),
			'description'   => __( 'Main Sidebar', 'pictau' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			'show_in_rest'	=> true
		)
	);
}
add_action( 'widgets_init', 'pictau_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function pictau_scripts() {
	wp_enqueue_style( 'pictau-style', get_stylesheet_uri(), array(), PICTAU_VERSION );

	//? Eliminamos el script de CF7 porque necesitamos que se ejecute después del nuestro 'pictau-script' para que funcione correctamente con las modal / popups...
	wp_dequeue_script( 'contact-form-7' );

	wp_register_script('pictau-script', get_template_directory_uri() . '/js/script.min.js', array(), PICTAU_VERSION, true);
	wp_localize_script('pictau-script', 'wpGlobalVars', array('homeURL' => esc_url( home_url() ), 'mediaURL' => wp_upload_dir()['baseurl'], 'themeURL' => get_bloginfo('template_directory'), 'ui_label_back' => __('back', 'pictau')  ) );
	wp_enqueue_script( 'pictau-script');

	wp_register_script('pictau-script', get_template_directory_uri() . '/js/script.min.js', array(), PICTAU_VERSION, true);

	// ? Volvemos a cargar el script de CF7 después del nuestro...
	wp_enqueue_script('contact-form-7');

	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }
}
add_action( 'wp_enqueue_scripts', 'pictau_scripts' );

/**
 * Enqueue the block editor script.
 */
function pictau_enqueue_block_editor_script() {
	wp_enqueue_script(
		'pictau-editor',
		get_template_directory_uri() . '/js/block-editor.min.js',
		array(
			'wp-blocks',
			'wp-edit-post',
		),
		PICTAU_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'pictau_enqueue_block_editor_script' );

/**
 * Enqueue the script necessary to support Tailwind Typography in the block
 * editor, using an inline script to create a JavaScript array containing the
 * Tailwind Typography classes from PICTAU_TYPOGRAPHY_CLASSES.
 */
function pictau_enqueue_typography_script() {
	if ( is_admin() ) {
		wp_enqueue_script(
			'pictau-typography',
			get_template_directory_uri() . '/js/tailwind-typography-classes.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			PICTAU_VERSION,
			true
		);
		wp_add_inline_script( 'pictau-typography', "tailwindTypographyClasses = '" . esc_attr( PICTAU_TYPOGRAPHY_CLASSES ) . "'.split(' ');", 'before' );
	}
}
add_action( 'enqueue_block_assets', 'pictau_enqueue_typography_script' );

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function pictau_tinymce_add_class( $settings ) {
	$settings['body_class'] = PICTAU_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter( 'tiny_mce_before_init', 'pictau_tinymce_add_class' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Block Attributes — panel de atributos HTML en bloques Gutenberg.
 */
require get_template_directory() . '/inc/block-attributes.php';

/**
 * WP 7.0 bug: wp_unique_id_from_values() hashes the entire $parsed_block (including
 * parent-layout context added by prior render_block_data filters), so the same block
 * produces different hashes in a two-pass render (e.g. Yoast processes content in
 * wp_head with no parent context → hash A in <style>; actual render has parent
 * context → hash B in DOM → CSS never applied).
 * Fix: replace with a content-only hash so the class is always deterministic.
 */
add_action( 'init', function() {
	remove_filter( 'render_block_data', 'wp_render_custom_css_support_styles', 10 );

	// Replacement: deterministic CSS-only hash so both render passes produce the same class.
	// Static tracker avoids registering the same CSS twice via wp_add_inline_style.
	add_filter( 'render_block_data', function( $parsed_block ) {
		static $registered = [];
		$custom_css = $parsed_block['attrs']['style']['css'] ?? null;
		if ( ! is_string( $custom_css ) || '' === trim( $custom_css ) ) {
			return $parsed_block;
		}
		$block_type = WP_Block_Type_Registry::get_instance()->get_registered( $parsed_block['blockName'] );
		if ( ! block_has_support( $block_type, 'customCSS', true ) ) {
			return $parsed_block;
		}
		if ( preg_match( '#</?\w+#', $custom_css ) ) {
			return $parsed_block;
		}
		$class_name     = 'wp-custom-css-' . substr( md5( $custom_css ), 0, 8 );
		$existing_class = $parsed_block['attrs']['className'] ?? null;
		$updated_class  = is_string( $existing_class ) ? "$existing_class $class_name" : $class_name;
		_wp_array_set( $parsed_block, array( 'attrs', 'className' ), $updated_class );
		if ( ! isset( $registered[ $class_name ] ) ) {
			$registered[ $class_name ] = true;
			// WP_Theme_JSON::process_blocks_custom_css() is protected; replicate its output:
			// wrap in :root :where() to match WP's low-specificity block CSS pattern.
			$processed_css = ':root :where(.' . $class_name . '){' . trim( $custom_css ) . '}';
			wp_register_style( 'wp-block-custom-css', false, array( 'global-styles' ) );
			wp_add_inline_style( 'wp-block-custom-css', $processed_css );
		}
		return $parsed_block;
	}, 10 );

	// Snapshot CSS registered before wp_head prints it.
	add_action( 'wp_head', function() {
		global $wp_styles;
		$GLOBALS['_pct_css_at_head'] = count( (array) $wp_styles->get_data( 'wp-block-custom-css', 'after' ) );
	}, 7 ); // Before wp_print_styles (priority 8).

	// Output any block CSS registered after wp_head (main render) in wp_footer.
	add_action( 'wp_footer', function() {
		global $wp_styles;
		$all  = (array) $wp_styles->get_data( 'wp-block-custom-css', 'after' );
		$late = array_slice( $all, $GLOBALS['_pct_css_at_head'] ?? count( $all ) );
		if ( ! empty( $late ) ) {
			echo '<style id="wp-block-custom-css-footer">' . implode( '', $late ) . '</style>';
		}
	}, 1 );
}, 20 );

/**
 * Polylang: rewrite /#anchor menu links to home_url()/#anchor so they resolve
 * correctly in any language (home_url() returns the translated home per language).
 */
add_filter( 'nav_menu_link_attributes', function( $atts ) {
	if ( ! isset( $atts['href'] ) ) {
		return $atts;
	}
	$parsed = wp_parse_url( $atts['href'] );
	// Match both relative (/#anchor) and absolute root-anchor (https://site.com/#anchor)
	// that Polylang converts relative URLs to before this filter runs.
	$is_root_anchor = isset( $parsed['fragment'] )
		&& ( ! isset( $parsed['path'] ) || $parsed['path'] === '/' )
		&& ! isset( $parsed['query'] );
	if ( $is_root_anchor ) {
		$atts['href'] = home_url( '/' ) . '#' . $parsed['fragment'];
	}
	return $atts;
}, 10, 1 );

/**
 * WP 6.9.1 regression: wp-block-custom-css declares global-styles as dependency
 * but it's not always registered in multilingual contexts (Polylang).
 */
add_action( 'wp_enqueue_scripts', function() {
	if ( ! wp_style_is( 'global-styles', 'registered' ) ) {
		wp_register_style( 'global-styles', false, [], null );
	}
}, 20 );











