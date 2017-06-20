<?php
/**
 * Store Pro.
 *
 * This file adds helper functions used in the Store Pro Theme.
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
 * Ensure $number is an absolute integer (whole number,
 * zero or greater). If the input is an absolute integer,
 * return it; otherwise, return the default.
 *
 * @param  int $number The input number.
 * @param  obj $setting The setting id.
 * @return int Absolute integer.
 */
function sp_sanitize_number( $number, $setting ) {

	$number = absint( $number );

	return ( $number ? $number : $setting->default );
}

/**
 * Calculate the color contrast.
 *
 * @param  string $color The input color.
 * @return string Hex color code for contrast color
 */
function sp_color_contrast( $color ) {
	$hexcolor = str_replace( '#', '', $color );
	$red      = hexdec( substr( $hexcolor, 0, 2 ) );
	$green    = hexdec( substr( $hexcolor, 2, 2 ) );
	$blue     = hexdec( substr( $hexcolor, 4, 2 ) );
	$luminosity = ( ( $red * 0.2126 ) + ( $green * 0.7152 ) + ( $blue * 0.0722 ) );
	return ( $luminosity > 200 ) ? '#2c2d33' : '#ffffff';
}

/**
 * Calculate the color brightness.
 *
 * @param  string $color The input color.
 * @param  string $change The amount to change.
 * @return string Hex color code for the color brightness
 */
function sp_color_brightness( $color, $change ) {
	$hexcolor = str_replace( '#', '', $color );
	$red   = hexdec( substr( $hexcolor, 0, 2 ) );
	$green = hexdec( substr( $hexcolor, 2, 2 ) );
	$blue  = hexdec( substr( $hexcolor, 4, 2 ) );
	$red   = max( 0, min( 255, $red + $change ) );
	$green = max( 0, min( 255, $green + $change ) );
	$blue  = max( 0, min( 255, $blue + $change ) );
	return '#' . dechex( $red ) . dechex( $green ) . dechex( $blue );
}

/**
 * Add flexible widget CSS classes.
 *
 * @param  string $id Widget area ID.
 * @return string $class Flexible widgets CSS class.
 */
function sp_flexible_widgets( $id ) {

	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		$count = count( $sidebars_widgets[ $id ] );
	} else {
		$count = 0;
	}

	$class = '';

	if ( 6 === $count ) {
		$class = ' flexible-widgets-6';
	} elseif ( 0 === $count % 5 ) {
		$class = ' flexible-widgets-5';
	} elseif ( 0 === $count % 4 ) {
		$class = ' flexible-widgets-4';
	} elseif ( 0 === $count % 3 ) {
		$class = ' flexible-widgets-3';
	} elseif ( 0 === $count % 2 ) {
		$class = ' flexible-widgets-2';
	} else {
		$class = '';
	}
	return $class;
}

/**
 * Quick and dirty way to mostly minify CSS.
 *
 * @author Gary Jones
 * @link https://github.com/GaryJones/Simple-PHP-CSS-Minification
 * @param string $css CSS to minify.
 * @return string Minified CSS.
 */
function sp_minify_css( $css ) {

	// Normalize whitespace.
	$css = preg_replace( '/\s+/', ' ', $css );

	// Remove spaces before and after comment.
	$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );

	// Remove comment blocks, everything between /* and */, unless preserved with /*! ... */ or /** ... */.
	$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );

	// Remove ; before }.
	$css = preg_replace( '/;(?=\s*})/', '', $css );

	// Remove space after , : ; { } */ >.
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

	// Remove space before , ; { } ( ) >.
	$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );

	// Strips leading 0 on decimal values (converts 0.5px into .5px).
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

	// Strips units if value is 0 (converts 0px to 0).
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

	// Converts all zeros value into short-hand.
	$css = preg_replace( '/0 0 0 0/', '0', $css );

	// Shorten 6-character hex color codes to 3-character where possible.
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );

	return trim( $css );
}
