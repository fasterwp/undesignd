<?php
/**
 * This file adds widget areas to the Store Pro theme.
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

// Register before header widget area.
genesis_register_sidebar( array(
	'id'          => 'before-header',
	'name'        => __( 'Before Header', 'store-pro' ),
	'description' => __( 'This is the before header flexible widgets area. Widgets displayed in this area will automatically adjust width depending on the number of widgets.', 'store-pro' ),
) );

// Register header right widget area.
genesis_register_sidebar( array(
	'id'          => 'header-right-widget',
	'name'        => __( 'Header Right', 'store-pro' ),
	'description' => __( 'This is the header right widget area. This widget area is not suitable to display every type of widget, and works best with a custom menu, a search form, or possibly a text widget.', 'store-pro' ),
) );

// Register after header widget area.
genesis_register_sidebar( array(
	'id'          => 'after-header',
	'name'        => __( 'After Header', 'store-pro' ),
	'description' => __( 'This is the after header flexible widgets area. Widgets displayed in this area will automatically adjust width depending on the number of widgets.', 'store-pro' ),
) );

// Register shop sidebar widget area.
genesis_register_sidebar( array(
	'id'          => 'sidebar',
	'name'        => __( 'Primary Sidebar', 'store-pro' ),
	'description' => __( 'This is the primary sidebar if you are using a two column site layout option. Not displayed on shop page or product archives.', 'store-pro' ),
) );

// Register shop sidebar widget area.
genesis_register_sidebar( array(
	'id'          => 'shop-sidebar',
	'name'        => __( 'Shop Sidebar', 'store-pro' ),
	'description' => __( 'This is the shop sidebar widget area if you are using a two column site layout option for your product archive.', 'store-pro' ),
) );

// Register before footer widget area.
genesis_register_sidebar( array(
	'id'          => 'before-footer',
	'name'        => __( 'Before Footer', 'store-pro' ),
	'description' => __( 'This is the before footer flexible widgets area. Widgets displayed in this area will automatically adjust width depending on the number of widgets.', 'store-pro' ),
) );

/**
 * Display before header widget area.
 */
function sp_before_header_widget_area() {

	genesis_widget_area( 'before-header', array(
	    'before' => sprintf( '<div class="before-header%s"><div class="wrap">', sp_flexible_widgets( 'before-header' ) ),
	    'after'  => '</div></div>',
	) );
}
add_action( 'genesis_before_header', 'sp_before_header_widget_area' );

/**
 * Display header right widget area.
 */
function sp_header_right_widget_area() {

	genesis_widget_area( 'header-right-widget', array(
	    'before' => sprintf( '<div class="header-widget-area">' ),
	    'after'  => '</div>',
	) );
}
add_action( 'genesis_header', 'sp_header_right_widget_area', 14 );

/**
 * Display after header widget area.
 */
function sp_after_header_widget_area() {

	genesis_widget_area( 'after-header', array(
	    'before' => sprintf( '<div class="after-header%s"><div class="wrap">', sp_flexible_widgets( 'after-header' ) ),
	    'after'  => '</div></div>',
	) );
}
add_action( 'genesis_after_header', 'sp_after_header_widget_area' );

/**
 * Display shop sidebar widget area.
 */
function sp_shop_widget_area() {

	if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {

		genesis_widget_area( 'shop-sidebar', array(
		    'before' => '<div class="shop-sidebar">',
		    'after'  => '</div>',
		) );
	}
}
add_action( 'genesis_before_sidebar_widget_area', 'sp_shop_widget_area' );

/**
 * Display before footer widget area.
 */
function sp_before_footer_widget_area() {

	genesis_widget_area( 'before-footer', array(
	    'before' => sprintf( '<div class="before-footer%s"><div class="wrap">', sp_flexible_widgets( 'before-footer' ) ),
	    'after'  => '</div></div>',
	) );
}
add_action( 'genesis_before_footer', 'sp_before_footer_widget_area' );

// Conditional variable of front page widgets. (Dynamic widget areas workaround).
$count_frontpage_widgets = is_customize_preview() ? 20 : get_option( 'sp_frontpage_widgets', 1 );

// Register dynamic front page widget areas.
for ( $i = 1; $i <= $count_frontpage_widgets; $i++ ) {

	genesis_register_sidebar( array(
		'id'          => 'front-page-' . $i,
		'name'        => __( 'Front Page ', 'store-pro' ) . $i,
		'description' => __( 'This is the front page ', 'store-pro' ) . $i . __( ' widget area.', 'store-pro' ),
	) );
}

// Conditional variable of front page widgets. (Dynamic widget areas workaround).
$count_footer_widgets = is_customize_preview() ? 20 : get_option( 'sp_footer_widgets', 3 );

// Register dynamic footer widget areas.
for ( $i = 1; $i <= $count_footer_widgets; $i++ ) {

	genesis_register_sidebar( array(
		'id'          => 'footer-widget-' . $i,
		'name'        => __( 'Footer Widget ', 'store-pro' ) . $i,
		'description' => __( 'This is the footer widget ', 'store-pro' ) . $i . __( ' widget area.', 'store-pro' ),
	) );
}

/**
 * Display footer widgets widget areas.
 *
 * @var $widget_areas Number of footer widget areas.
 */
function sp_footer_widgets() {

	$widget_areas = get_option( 'sp_footer_widgets', 3 );

	// Return early if no front page widget areas.
	if ( '0' === $widget_areas ) {
		return;
	}

	echo '<div class="footer-widgets flexible-widgets-' . esc_attr( $widget_areas ) . '">';

	// Loop through widget areas.
	for ( $i = 1; $i <= $widget_areas; $i++ ) {

		genesis_widget_area( "footer-widget-$i", array(
			'before' => sprintf( '<div class="widget-area footer-widgets-%s">', $i ),
			'after'  => '</div>',
		) );
	}

	echo '</div>';
}
add_action( 'genesis_footer', 'sp_footer_widgets', 6 );
