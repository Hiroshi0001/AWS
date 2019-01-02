<?php
/**
 * Theme Customizer.
 *
 * @package Business_Club
 */

/**
 * Add Customizer options.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function business_club_customize_register( $wp_customize ) {

	// Load custom controls.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/control.php';

	// Custom controls and sections.
	$wp_customize->register_control_type( 'Business_Club_Heading_Control' );
	$wp_customize->register_control_type( 'Business_Club_Message_Control' );
	$wp_customize->register_section_type( 'Business_Club_Upsell_Section' );

	// Upsell section.
	$wp_customize->add_section(
		new Business_Club_Upsell_Section( $wp_customize, 'custom_theme_upsell',
			array(
				'title'    => esc_html__( 'Business Club Pro', 'business-club' ),
				'pro_text' => esc_html__( 'Buy Pro', 'business-club' ),
				'pro_url'  => 'http://axlethemes.com/downloads/business-club-pro/',
				'priority' => 1,
			)
		)
	);

	// Load helpers.
	require_once trailingslashit( get_template_directory() ) . 'includes/helpers.php';

	// Load customize sanitize.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/sanitize.php';

	// Load customize callback.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/callback.php';

	// Load customize options.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/option.php';

	// Load slider customize options.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/slider.php';
}

add_action( 'customize_register', 'business_club_customize_register' );

/**
 * Register Customizer partials.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function business_club_customizer_partials( WP_Customize_Manager $wp_customize ) {

	// Bail if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'refresh';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'refresh';
		return;
	}

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Register partial for blogname.
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector'            => '.site-title a',
		'container_inclusive' => false,
		'render_callback'     => 'business_club_customize_partial_blogname',
	) );

	// Register partial for blogdescription.
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector'            => '.site-description',
		'container_inclusive' => false,
		'render_callback'     => 'business_club_customize_partial_blogdescription',
	) );

}

add_action( 'customize_register', 'business_club_customizer_partials', 99 );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function business_club_customize_partial_blogname() {

	bloginfo( 'name' );

}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function business_club_customize_partial_blogdescription() {

	bloginfo( 'description' );

}

/**
 * Customizer control assets.
 *
 * @since 1.0.4
 */
function business_club_customizer_control_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script( 'business-club-customize-controls', get_template_directory_uri() . '/js/customize-controls' . $min . '.js', array( 'jquery', 'customize-controls' ), '1.0.4' );
	wp_enqueue_style( 'business-club-customize-controls', get_template_directory_uri() . '/css/customize-controls' . $min . '.css', array(), '1.0.4' );

}

add_action( 'customize_controls_enqueue_scripts', 'business_club_customizer_control_scripts', 0 );
