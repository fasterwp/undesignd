<?php
/**
 * This file adds customizer settings to the Store Pro theme.
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

// Global array of theme colors.
$sp_colors = array(
	'accent' 			=> '#2c2d33',
	'links' 			=> '#2c2d33',
	'body' 				=> '#55575d',
	'headings'			=> '#43454b',
	'light'				=> '#f5f6f7',
);

/**
 * Register settings and controls with the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function sp_customizer_register( $wp_customize ) {

	// Make sure preview refreshes on change.
	$wp_customize->get_setting( 'header_image' )->transport = 'refresh';

	// Globals.
	global $wp_customize, $sp_colors;

	// Loop through array and display colors.
	foreach ( $sp_colors as $id => $hex ) {

		$section = 'colors';
		$setting = "sp_{$id}_color";
		$label	 = ucwords( str_replace( '_', ' ', $id ) );

		// Add color setting.
		$wp_customize->add_setting(
			$setting,
			array(
				'default'           => $hex,
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		// Add color control.
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$setting,
				array(
					'label'       => $label,
					'section'     => $section,
					'settings'    => $setting,
				)
			)
		);
	}

	// Footer widget areas.
	$wp_customize->add_setting(
		'sp_footer_widgets',
		array(
			'default'           => 3,
			'sanitize_callback' => 'sanitize_text_field',
			'type'				=> 'option',
			'transport'   		=> 'refresh',
		)
	);

	$wp_customize->add_control(
		'sp_footer_widgets',
		array(
			'type'		  => 'number',
			'label'       => __( 'Footer Widget Areas', 'store-pro' ),
			'description' => __( 'Select the number of widget areas to display in the footer section.', 'store-pro' ),
			'section'     => 'genesis_layout',
			'settings'    => 'sp_footer_widgets',
			'priority'	  => 20,
		)
	);

	// Front page widget areas.
	$wp_customize->add_setting(
		'sp_frontpage_widgets',
		array(
			'default'           => 1,
			'sanitize_callback' => 'sanitize_text_field',
			'type'				=> 'option',
			'transport'   		=> 'refresh',
		)
	);

	$wp_customize->add_control(
		'sp_frontpage_widgets',
		array(
			'type'		  => 'number',
			'label'       => __( 'Front Page Widget Areas', 'store-pro' ),
			'description' => __( 'Select the number of widget areas to display on the home page.', 'store-pro' ),
			'section'     => 'static_front_page',
			'settings'    => 'sp_frontpage_widgets',
		)
	);

	// Add front page setting to the Customizer.
	$wp_customize->add_setting(
		'sp_frontpage_content',
		array(
		    'default'           => 'true',
		    'type'              => 'option',
		)
	);

	$wp_customize->add_control(
		'sp_frontpage_content',
		array(
			'label'       => __( 'Front Page Content', 'store-pro' ),
			'description' => __( 'Show or hide the front page content.', 'store-pro' ),
			'section'     => 'static_front_page',
			'settings'    => 'sp_frontpage_content',
			'type'        => 'select',
			'choices'     => array(
				'false'   => __( 'Hide content', 'store-pro' ),
				'true'    => __( 'Show content', 'store-pro' ),
			),
	    )
	);

	// Front page content order.
	$wp_customize->add_setting(
		'sp_frontpage_order',
		array(
		    'default'           => 'true',
		    'type'              => 'option',
		)
	);

	$wp_customize->add_control(
		'sp_frontpage_order',
		array(
			'label'       => __( 'Front Page Order', 'store-pro' ),
			'description' => __( 'Show the front page widgets before or after the page content.', 'store-pro' ),
			'section'     => 'static_front_page',
			'settings'    => 'sp_frontpage_order',
			'type'        => 'select',
			'choices'     => array(
				'true'    => __( 'Before content', 'store-pro' ),
				'false'   => __( 'After content', 'store-pro' ),
			),
	    )
	);
}
add_action( 'customize_register', 'sp_customizer_register' );
