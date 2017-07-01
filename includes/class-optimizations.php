<?php
/**
 * Store Pro.
 *
 * This file contains WordPress and Genesis optimizations to remove
 * all of the unused functionality for the Store Pro theme. This is
 * what makes it an SEO Themes theme.
 *
 * @package      Store Pro
 * @link         https://seothemes.net/store-pro
 * @author       Seo Themes
 * @copyright    Copyright © 2017 Seo Themes
 * @license      GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Optimize class. This wraps everything up nicely.
 */
final class Optimizations {

	/**
	 * Holds the instance of this class.
	 *
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Remove hooks.
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
		remove_action( 'genesis_after_header', 'genesis_do_nav' );
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
		remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
		remove_action( 'genesis_post_content', 'genesis_do_post_image' );

		// Remove filters.
		remove_filter( 'the_title', 'capital_P_dangit', 11 );
		remove_filter( 'the_content', 'capital_P_dangit', 11 );
		remove_filter( 'comment_text', 'capital_P_dangit', 31 );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// Add hooks.
		add_action( 'init', array( $this, 'head_cleanup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'deregister_superfish' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_jquery' ), 100 );
		add_action( 'wp_head', array( $this, 'jquery_local_fallback' ) );
		add_action( 'wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 99 );
		add_action( 'genesis_site_title', 'the_custom_logo', 0 );
		add_action( 'genesis_header', 'genesis_do_nav', 10 );
		add_action( 'genesis_before', array( $this, 'display_featured_image' ) );
		add_action( 'genesis_meta', array( $this, 'viewport_meta_tag' ) );

		// Add filters.
		add_filter( 'use_default_gallery_style', '__return_false' );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_filter( 'nav_menu_description', 'wp_kses_post' );
		add_filter( 'style_loader_src', array( $this, 'remove_version_query' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'remove_version_query' ), 10, 2 );
		add_filter( 'script_loader_tag', array( $this, 'clean_script_tag' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'remove_superfish_class' ) );
		add_filter( 'nav_menu_css_class', array( $this, 'menu_class_filter' ), 100, 1 );
		add_filter( 'nav_menu_item_id', array( $this, 'menu_class_filter' ), 100, 1 );
		add_filter( 'page_css_class', array( $this, 'menu_class_filter' ), 100, 1 );
		add_filter( 'get_avatar', array( $this, 'remove_self_closing_tags' ) );
		add_filter( 'comment_id_fields', array( $this, 'remove_self_closing_tags' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'remove_self_closing_tags' ) );
		add_filter( 'get_bloginfo_rss', array( $this, 'remove_default_description' ) );
		add_filter( 'the_content', array( $this, 'remove_ptags_on_images' ) );
		add_filter( 'excerpt_more', array( $this, 'custom_excerpt_more' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'primary_menu_args' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'menu_description' ), 10, 4 );
		add_filter( 'wp_nav_menu_items', array( $this, 'nav_breadcrumb' ), 10, 2 );
		add_filter( 'theme_page_templates', array( $this, 'remove_page_templates' ) );
		add_filter( 'genesis_markup_content-sidebar-wrap', array( $this, 'return_empty' ) );
		add_filter( 'genesis_edit_post_link' , '__return_false' );
		add_filter( 'genesis_attr_body', array( $this, 'add_ontouchstart' ) );
		add_filter( 'genesis_attr_title-area', array( $this, 'title_area' ) );
		add_filter( 'genesis_attr_site-title', array( $this, 'site_title' ) );
		add_filter( 'genesis_breadcrumb_args', array( $this, 'breadcrumb_args' ) );
		add_filter( 'genesis_register_widget_area_defaults', array( $this, 'optimize_widgets' ) );
		add_filter( 'genesis_footer_creds_text', array( $this, 'footer_credits' ) );
		add_filter( 'genesis_attr_body', array( $this, 'body_classes' ) );
	}

	/**
	 * Return an empty variable.
	 *
	 * @param  array $atts Given attributes.
	 * @return array $atts Empty attributes.
	 */
	public function return_empty( $atts ) {

		$atts = '';
		return $atts;
	}

	/**
	 * Clean up wp_head.
	 */
	public function head_cleanup() {

		global $wp_widget_factory;

		if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
			remove_action( 'wp_head', [ $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ] );
		}

		add_action( 'wp_head', 'ob_start', 1, 0 );
		add_action( 'wp_head', function () {
			$pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
			echo preg_replace( $pattern, '', ob_get_clean() );
		}, 3, 0);
	}

	/**
	 * Don't return the default description in the
	 * RSS feed if it hasn't been changed.
	 *
	 * @param string $bloginfo Site tagline.
	 */
	public function remove_default_description( $bloginfo ) {
		$default_tagline = 'Just another WordPress site';
		return ( $bloginfo === $default_tagline ) ? '' : $bloginfo;
	}

	/**
	 * Remove query string from static files.
	 *
	 * @param  string $src The string.
	 * @return string $src The string.
	 */
	public function remove_version_query( $src ) {

		if ( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}

	/**
	 * Remove unnecessary self-closing tags
	 *
	 * @param string $input The input strings.
	 */
	public function remove_self_closing_tags( $input ) {
		return str_replace( ' />', '>', $input );
	}

	/**
	 * Clean up output of <script> tags.
	 *
	 * @param string $input Scripts.
	 */
	public function clean_script_tag( $input ) {
		$input = str_replace( "type='text/javascript' ", '', $input );
		return str_replace( "'", '"', $input );
	}

	/**
	 * Remove superfish scripts.
	 */
	public function deregister_superfish() {
		wp_deregister_script( 'superfish' );
		wp_deregister_script( 'superfish-args' );
	}

	/**
	 * Remove superfish menus.
	 *
	 * @param  array $args Default classes.
	 * @return array $args Cleaned up.
	 */
	public function remove_superfish_class( $args ) {

		$args['menu_class'] = 'genesis-nav-menu';
		return $args;
	}

	/**
	 * Remove tinymce emoji plugin.
	 *
	 * @param  array $plugins Array of plugins.
	 * @return array Removed from plugins.
	 */
	public function remove_tinymce_emojis( $plugins ) {
		if ( ! is_array( $plugins ) ) {
			return array();
		}
		return array_diff( $plugins, array( 'wpemoji' ) );
	}

	/**
	 * Emulate hover effects on mobile devices.
	 *
	 * @param  string $attr On touch start attribute.
	 * @return string
	 */
	public function add_ontouchstart( $attr ) {
		$attr['ontouchstart'] = ' ';
		return $attr;
	}

	/**
	 * Add custom Viewport meta tag for mobile browsers.
	 */
	function viewport_meta_tag() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>';
	}

	/**
	 * Load jQuery from jQuery's CDN with a local fallback.
	 */
	public function register_jquery() {
		$jquery_version = wp_scripts()->registered['jquery']->ver;
		wp_deregister_script( 'jquery' );
		wp_register_script(
			'jquery',
			'https://code.jquery.com/jquery-' . $jquery_version . '.min.js',
			[],
			null,
			null
		);
		add_filter( 'wp_resource_hints', function ( $urls, $relation_type ) {
			if ( 'dns-prefetch' === $relation_type ) {
				$urls[] = 'code.jquery.com';
			}
			return $urls;
		}, 10, 2);
		add_filter( 'script_loader_src', array( $this, 'jquery_local_fallback' ), 10, 2 );
	}

	/**
	 * Output the local fallback immediately after jQuery's <script>
	 *
	 * @link http://wordpress.stackexchange.com/a/12450
	 * @param string $src jQUery source.
	 * @param string $handle Theme handle.
	 */
	public function jquery_local_fallback( $src, $handle = null ) {
		static $add_jquery_fallback = false;
		if ( $add_jquery_fallback ) {
			echo '<script>(window.jQuery && jQuery.noConflict()) || document.write(\'<script src="' . $add_jquery_fallback . '"><\/script>\')</script>' . "\n";
			$add_jquery_fallback = false;
		}
		if ( 'jquery' === $handle ) {
			$add_jquery_fallback = apply_filters( 'script_loader_src', \includes_url( '/js/jquery/jquery.js' ), 'jquery-fallback' );
		}
		return $src;
	}

	/**
	 * Add schema microdata to title-area.
	 *
	 * @param  array $args Array of arguments.
	 * @return array $args Additional arguments.
	 */
	public function title_area( $args ) {
		$args['itemscope'] = 'itemscope';
		$args['itemtype'] = 'http://schema.org/Organization';
		return $args;
	}

	/**
	 * Correct site-title schema microdata.
	 *
	 * @param  array $args Array of arguments.
	 * @return array $args New arguments.
	 */
	public function site_title( $args ) {
		$args['itemprop'] = 'name';
		return $args;
	}

	/**
	 * Remove excessive menu-item classes.
	 *
	 * @param  array $var Too many classes.
	 * @return array Only necessary classes.
	 */
	public function menu_class_filter( $var ) {

		if ( ! is_array( $var ) ) {
			return '';
		}

		$var = array_intersect( $var, array(
			'current-menu-item',
			'menu-item',
			'menu-item-has-children',
			'mega-menu',
			'menu-description',
		) );
		return $var;
	}

	/**
	 * Reduce the Primary Navigation Menu to three levels depth.
	 * (For mega menu).
	 *
	 * @param  array $args Menu args.
	 * @return array $args New menu args.
	 */
	public function primary_menu_args( $args ) {

		if ( 'primary' !== $args['theme_location'] ) {
			return $args;
		}

		$args['depth'] = 3;

		return $args;
	}

	/**
	 * Display descriptions in Primary Navigation Menu.
	 *
	 * @param string $output  HTML output for the menu item.
	 * @param object $item    Menu item object.
	 * @param int    $depth   Depth in menu structure.
	 * @param object $args    Arguments passed to wp_nav_menu().
	 * @return string $output
	 */
	public function menu_description( $output, $item, $depth, $args ) {

		if ( 1 === $depth && ' ' !== $item->description && '' !== $item->description ) {
			$button = apply_filters( 'sp_menu_button', '<button class="small">Read more</button>' );
			$output = str_replace( '</a>', '<p itemprop="description">' . $item->description . '</p>' . $button . '</a>', $output );
		}
		return $output;
	}

	/**
	 * Callback for Genesis 'wp_nav_menu_items' filter.
	 *
	 * @param string   $menu The menu html.
	 * @param stdClass $args the current menu args.
	 * @return string  $menu The menu html
	 */
	public function nav_breadcrumb( $menu, $args ) {

		if ( 'secondary' !== $args->theme_location ) {
			return $menu;
		}

		ob_start();
		genesis_do_breadcrumbs();
		$breadcrumbs = ob_get_clean();

		$menu .= '</ul>' . $breadcrumbs;

		return $menu;

	}

	/**
	 * Remove Genesis Blog & Archive Page Templates.
	 *
	 * @param  array $page_templates All page templates.
	 * @return array Modified templates.
	 */
	public function remove_page_templates( $page_templates ) {
		unset( $page_templates['page_archive.php'] );
		unset( $page_templates['page_blog.php'] );
		return $page_templates;
	}

	/**
	 * Modify breadcrumb arguments.
	 *
	 * @param  array $args Original breadcrumb args.
	 * @return array Cleaned breadcrumbs.
	 */
	public function breadcrumb_args( $args ) {
		$args['prefix'] = '<div class="breadcrumb" itemscope="" itemtype="https://schema.org/BreadcrumbList"><div class="wrap">';
		$args['suffix'] = '</div></div>';
		$args['labels']['prefix'] = '';
		$args['labels']['author'] = '';
		$args['labels']['category'] = '';
		$args['labels']['tag'] = '';
		$args['labels']['date'] = '';
		$args['labels']['tax'] = '';
		$args['labels']['post_type'] = '';
		return $args;
	}

	/**
	 * Remove the p from around images.
	 *
	 * @link   http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
	 * @param  string $content Default content.
	 * @return string Filtered content.
	 */
	function remove_ptags_on_images( $content ) {
		return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	}

	/**
	 * Remove […] from the read more link.
	 *
	 * @param  string $more Default read more link.
	 * @return string Filtered read more link.
	 */
	function custom_excerpt_more( $more ) {

		global $post;
		return '...  <a class="excerpt-read-more" href="' . get_permalink( $post->ID ) . '" title="' . __( 'Read ', 'store-pro' ) . esc_attr( get_the_title( $post->ID ) ) . '">' . __( 'Read more &raquo;', 'store-pro' ) . '</a>';
	}

	/**
	 * Display featured image before post content on blog.
	 *
	 * @return array Featured image size.
	 */
	public function display_featured_image() {

		// Check display featured image option.
		$genesis_settings = get_option( 'genesis-settings' );

		if ( ( ! is_archive() && ! is_home() && ! is_page_template( 'page_blog.php' ) ) || ( 1 !== $genesis_settings['content_archive_thumbnail'] ) ) {
			return;
		}
		add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );
	}

	/**
	 * Optimize widget markup.
	 *
	 * Removes widget-wrap div and changes widget titles
	 * to use <b> instead of <h3>.
	 *
	 * @param array $defaults Widget area defaults.
	 */
	public function optimize_widgets( $defaults ) {

		global $wp_registered_sidebars;

		$defaults = array(

			'before_widget' => genesis_markup( array(
				'open'    => '<div class="widget %%2$s">',
				'context' => 'widget-wrap',
				'echo'    => false,
			) ),

			'after_widget'  => genesis_markup( array(
				'close'   => '</div>',
				'context' => 'widget-wrap',
				'echo'    => false,
			) ),

			'before_title'  => '<b class="widget-title">',
			'after_title'   => "</b>\n",

		);
		return $defaults;
	}

	/**
	 * Change the footer text.
	 *
	 * @param  string $creds Defaults.
	 * @return string Custom footer credits.
	 */
	public function footer_credits( $creds ) {
		$creds = '[footer_copyright] Undesigned Theme by <a href="https://simplenet.ro" title="Simplenet">Simplenet</a>';
		return $creds;
	}

	/**
	 * Remove excessive body classes.
	 *
	 * @uses body_class Located /lib/clean-up/clean-body-class.php
	 * @param array $attributes Existing classes for `body` element.
	 * @return array Amended classes for `body` element.
	 */
	public function body_classes( $attributes ) {

		global $wp_query;

		// Empty the array.
		$attributes['class'] = array();
		$class = $attributes['class'];

		if ( is_rtl() ) {
			$class[] = 'rtl';
		}

		if ( is_front_page() ) {
			$class[] = 'home';
		}

		if ( is_home() ) {
			$class[] = 'blog';
		}

		if ( is_page() ) {
			$class[] = 'page';
		}

		if ( is_single() ) {
			$class[] = 'single';
		}

		if ( is_archive() ) {
			$class[] = 'archive';
		}

		if ( is_date() ) {
			$class[] = 'date';
		}

		if ( is_search() ) {
			$class[] = 'search';
		}

		if ( is_paged() ) {
			$class[] = 'paged';
		}

		if ( is_attachment() ) {
			$class[] = 'attachment';
		}

		if ( is_404() ) {
			$class[] = 'error404';
		}

		if ( is_user_logged_in() ) {
			$class[] = 'logged-in';
		}

		if ( is_admin_bar_showing() ) {
			$class[] = 'admin-bar';
			$class[] = 'no-customize-support';
		}

		if ( has_custom_logo() ) {
			$class[] = 'wp-custom-logo';
		}

		$class = array_map( 'esc_attr', $class );

		// Filter the list of CSS body class for the current post or page.
		$class = apply_filters( 'body_class', $class );

		// Add any additional unwanted class here.
		$blacklist = array(
			'wp-featherlight-captions',
			'header-full-width',
			'custom-header',
			'header-image',
		);

		// Remove blacklisted class from array.
		$class = array_diff( $class, $blacklist );

		// Remove same class.
		$class = array_unique( $class );

		// Convert array to string.
		$attributes['class'] = join( ' ', $class );

		return $attributes;
	}

	/**
	 * Returns the instance.
	 *
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}

Optimizations::get_instance();
