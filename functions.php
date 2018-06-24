<?php
/**
 * CA World 2018 functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage CA_World_2018
 * @since CA World 2018 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'caworld2018_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own caworld2018_setup() function to override in a child theme.
 *
 * @since CA World 2018 1.0
 */
function caworld2018_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/caworld2018
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'caworld2018' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'caworld2018' );

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
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'primary', 'caworld2018' ),
		'social'  => __( 'social-links', 'caworld2018' ),
	) );
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', caworld2018_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // caworld2018_setup
add_action( 'after_setup_theme', 'caworld2018_setup' );

if ( ! function_exists( 'caworld2018_pages_init' ) ) :
function caworld2018_pages_init() {

	function the_slug_exists($post_name) {
		global $wpdb;
		if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A')) {
			return true;
		} else {
			return false;
		}
	}
	// create the readings page
	if (isset($_GET['activated']) && is_admin()) {
	    $title = 'Readings';
	    $content = <<<EOD
				<h2>You should probably remove this section...</h2>
				Some readings are provided automatically, as part of installing the CA World 2018 theme.
				<h2>Meeting Format</h2>
				<p><a href="https://ca.org/content/uploads/2015/04/meeting_formats.pdf">Download the PDF for your first meeting! (.PDF, 371kb)</a>
EOD;
	    $check = get_page_by_title($blog_page_title);
	    $page = array(
		    'post_type' => 'page',
		    'post_title' => $title,
		    'post_content' => $content,
		    'post_status' => 'publish',
		    'post_author' => 1,
		    'post_slug' => 'readings'
	    );
	    if(!isset($check->ID) && !the_slug_exists('blog')){
	        $id = wp_insert_post($page);
	    }
	}
	// create the contact page
	if (isset($_GET['activated']) && is_admin()) {
	    $blog_page_title = 'Contact Us';
	    $blog_page_content = 'This is blog page placeholder. Anything you enter here will not appear in the front end, except for search results pages.';
	    $blog_page_check = get_page_by_title($blog_page_title);
	    $blog_page = array(
		    'post_type' => 'page',
		    'post_title' => $blog_page_title,
		    'post_content' => $blog_page_content,
		    'post_status' => 'publish',
		    'post_author' => 1,
		    'post_slug' => 'blog'
	    );
	    if(!isset($blog_page_check->ID) && !the_slug_exists('blog')){
	        $blog_page_id = wp_insert_post($blog_page);
	    }
	}
	// change the Sample page to the home page
	if (isset($_GET['activated']) && is_admin()){
	    $home_page_title = 'Home';
	    $home_page_content = <<<EOD
				<strong>Cocaine Anonymous is a Fellowship of men and women who share their experience, strength and hope with each other that they may solve their common problem and help others recover from their addiction.</strong>

				The best way to reach someone is to speak to them on a common level. The members of C.A. are all recovering addicts who maintain their individual sobriety by working with others. We come from various social, ethnic, economic and religious backgrounds, but what we have in common is addiction.

				<strong>The only requirement for membership is a desire to stop using cocaine and all other mind-altering substances.</strong>

				&nbsp;
EOD;
	    $home_page_check = get_page_by_title($home_page_title);
	    $home_page = array(
		    'post_type' => 'page',
		    'post_title' => $home_page_title,
		    'post_content' => $home_page_content,
		    'post_status' => 'publish',
		    'post_author' => 1,
		    'ID' => 2,
		    'post_slug' => 'home'
	    );
	    if(!isset($home_page_check->ID) && !the_slug_exists('home')){
	        $home_page_id = wp_insert_post($home_page);
	    }
	}
	if (isset($_GET['activated']) && is_admin()){
		// Set the blog page
		$blog = get_page_by_title( 'Blog' );
		update_option( 'page_for_posts', $blog->ID );

		// Use a static front page
		$front_page = 2; // this is the default page created by WordPress
		update_option( 'page_on_front', $front_page );
		update_option( 'show_on_front', 'page' );
	}
}
endif; // caworld2018_pages_init
add_action( 'after_setup_theme', 'caworld2018_pages_init' );

if ( ! function_exists( 'caworld2018_menus_init' ) ) :
/**
 * Sets up boilerplate menu items
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 *
 * @since CA World 2018 1.0
 */
function caworld2018_menus_init() {
 $menuname = 'Primary Menu';
$bpmenulocation = 'primary';
// Does the menu exist already?
$menu_exists = wp_get_nav_menu_object( $menuname );

// If it doesn't exist, let's create it.
if( !$menu_exists){
    $menu_id = wp_create_nav_menu($menuname);

    // Set up default BuddyPress links and add them to the menu.
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Home'),
        'menu-item-classes' => 'home',
        'menu-item-url' => home_url( '/' ),
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Readings'),
        'menu-item-classes' => 'readings',
        'menu-item-url' => home_url( '/readings/' ),
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Contact Us'),
        'menu-item-classes' => 'contact-us',
        'menu-item-url' => home_url( '/contact-us/' ),
        'menu-item-status' => 'publish'));

    // Grab the theme locations and assign our newly-created menu
    // to the BuddyPress menu location.
    if( !has_nav_menu( $bpmenulocation ) ){
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
	}
}
endif; // caworld2018_menus_init
add_action( 'after_setup_theme', 'caworld2018_menus_init' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since CA World 2018 1.0
 */
function caworld2018_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'caworld2018_content_width', 840 );
}
add_action( 'after_setup_theme', 'caworld2018_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since CA World 2018 1.0
 */
function caworld2018_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'caworld2018' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'caworld2018' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'caworld2018' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'caworld2018' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'caworld2018' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'caworld2018' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'caworld2018_widgets_init' );

if ( ! function_exists( 'caworld2018_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own caworld2018_fonts_url() function to override in a child theme.
 *
 * @since CA World 2018 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function caworld2018_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'caworld2018' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'caworld2018' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'caworld2018' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since CA World 2018 1.0
 */
function caworld2018_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'caworld2018_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since CA World 2018 1.0
 */
function caworld2018_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'caworld2018-fonts', caworld2018_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'caworld2018-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'caworld2018-ie', get_template_directory_uri() . '/css/ie.css', array( 'caworld2018-style' ), '20160816' );
	wp_style_add_data( 'caworld2018-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'caworld2018-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'caworld2018-style' ), '20160816' );
	wp_style_add_data( 'caworld2018-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'caworld2018-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'caworld2018-style' ), '20160816' );
	wp_style_add_data( 'caworld2018-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'caworld2018-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'caworld2018-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'caworld2018-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'caworld2018-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'caworld2018-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'caworld2018-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'caworld2018' ),
		'collapse' => __( 'collapse child menu', 'caworld2018' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'caworld2018_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since CA World 2018 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function caworld2018_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'caworld2018_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since CA World 2018 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function caworld2018_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since CA World 2018 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function caworld2018_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'caworld2018_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since CA World 2018 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function caworld2018_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'caworld2018_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function caworld2018_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'caworld2018_widget_tag_cloud_args' );

if (!function_exists('write_log')) {
    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ));
            } else {
                error_log( $log );
            }
        }
    }
}

if (!function_exists('debugvar')) {
    function debugvar ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                echo '<pre>';print_r( $log );echo '</pre>';
            } else {
                echo '<pre>';var_dump( $log );echo '</pre>';
            }
        }
    }
}