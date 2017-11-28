<?php
/**
 * Store Pro Theme.
 *
 * @package      Store Pro
 * @link         https://seothemes.net/store-pro
 * @author       Seo Themes
 * @copyright    Copyright © 2017 Seo Themes
 * @license      GPL-2.0+
 */

// Child theme (do not remove).
include_once( get_template_directory() . '/lib/init.php' );

// Set Localization (do not remove).
load_child_theme_textdomain( 'store-pro', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'store-pro' ) );

// Theme constants.
define( 'CHILD_THEME_NAME', 'mujo' );
define( 'CHILD_THEME_URL', 'http://www.seothemes.net/store-pro' );
define( 'CHILD_THEME_VERSION', '0.1.2' );

// Remove unused functionality.
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'header-right' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Enable support for structural wraps.
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'menu-primary',
	'menu-secondary',
	'site-inner',
	'footer-widgets',
	'footer',
) );

// Enable Accessibility support.
add_theme_support( 'genesis-accessibility', array(
	'404-page',
	'drop-down-menu',
	'headings',
	'rems',
	'search-form',
	'skip-links',
) );

// Rename primary and secondary navigation menus.
add_theme_support( 'genesis-menus' , array(
	'primary' => __( 'Primary Menu', 'store-pro' ),
	'secondary' => __( 'Secondary Menu', 'store-pro' ),
) );

// Enable HTML5 markup structure.
add_theme_support( 'html5', array(
	'caption',
	'comment-form',
	'comment-list',
	'gallery',
	'search-form',
) );

// Add support for post formats.
add_theme_support( 'post-formats', array(
	'aside',
	'audio',
	'chat',
	'gallery',
	'image',
	'link',
	'quote',
	'status',
	'video',
) );

// Enable support for post thumbnails.
add_theme_support( 'post-thumbnails' );

// Enable automatic output of WordPress title tags.
add_theme_support( 'title-tag' );

// Enable selective refresh and Customizer edit icons.
add_theme_support( 'customize-selective-refresh-widgets' );

// Enable theme support for custom background image.
add_theme_support( 'custom-background' );

// Enable logo option in Customizer > Site Identity.
add_theme_support( 'custom-logo', array(
	'height'      => 60,
	'width'       => 200,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( '.site-title', '.site-description' ),
) );

// Enable support for custom header image or video.
add_theme_support( 'custom-header', array(
	'header-selector' 	=> '.front-page-1',
	'default_image'    	=> get_stylesheet_directory_uri() . '/assets/images/hero.jpg',
	'header-text'     	=> false,
	'width'           	=> 1920,
	'height'          	=> 1080,
	'flex-height'     	=> true,
	'flex-width'		=> true,
	'video'				=> true,
) );

// Register default header (just in case).
register_default_headers( array(
	'child' => array(
		'url'           => '%2$s/assets/images/hero.jpg',
		'thumbnail_url' => '%2$s/assets/images/hero.jpg',
		'description'   => __( 'Hero Image', 'store-pro' ),
	),
) );

/**
 * Load custom scripts and styles.
 */
function sp_enqueue_scripts_styles() {

	// Google fonts.
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Noto+Serif|Muli|Playfair+Display', array(), CHILD_THEME_VERSION );

	// Line awesome.
	wp_enqueue_style( 'line-awesome', 'https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css' );

	// Theme scripts.
	wp_enqueue_script( 'store-pro', get_stylesheet_directory_uri() . '/assets/scripts/min/store-pro.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'sp_enqueue_scripts_styles' );

/**
 * Add "first" and "last" CSS classes to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 */
function widget_first_last_classes( $params ) {
	
		global $my_widget_num; // Global a counter array
		$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
		$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets
	
		if( !$my_widget_num ) {// If the counter array doesn't exist, create it
			$my_widget_num = array();
		}
	
		// if( !isset( $arr_registered_widgets[$this_id] ) || !is_array( $arr_registered_widgets[$this_id] ) ) { // Check if the current sidebar has no widgets
			// return $params; // No widgets in this sidebar... bail early.
		// }
	
		if( isset( $my_widget_num[$this_id] ) ) { // See if the counter array has an entry for this sidebar
			$my_widget_num[$this_id] ++;
		} else { // If not, create it starting with 1
			$my_widget_num[$this_id] = 1;
		}
	
		$class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options
	
		if( $my_widget_num[$this_id] == 1 ) { // If this is the first widget
			$class .= 'widget-first ';
		} elseif( $my_widget_num[$this_id] == count( $arr_registered_widgets[$this_id] ) ) { // If this is the last widget
			$class .= 'widget-last ';
		}
	
		// $params[0]['before_widget'] = str_replace( 'class="', $class, $params[0]['before_widget'] ); // Insert our new classes into "before widget"
		$params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1); // Insert our new classes into "before widget"
	
		return $params;
	
	}
	add_filter( 'dynamic_sidebar_params', 'widget_first_last_classes' );
	

// Theme includes.
include_once( get_stylesheet_directory() . '/includes/theme-defaults.php' );
include_once( get_stylesheet_directory() . '/includes/helper-functions.php' );
include_once( get_stylesheet_directory() . '/includes/class-optimizations.php' );
include_once( get_stylesheet_directory() . '/includes/class-clean-gallery.php' );
include_once( get_stylesheet_directory() . '/includes/class-plugin-activation.php' );
include_once( get_stylesheet_directory() . '/includes/widget-areas.php' );
include_once( get_stylesheet_directory() . '/includes/woocommerce.php' );
include_once( get_stylesheet_directory() . '/includes/customizer-settings.php' );
include_once( get_stylesheet_directory() . '/includes/customizer-output.php' );
