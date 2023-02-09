<?php
/**
 * Traveller Agency Theme Customizer
 *
 * @link: https://developer.wordpress.org/themes/customize-api/customizer-objects/
 *
 * @package Traveller Agency
 */

use WPTRT\Customize\Section\Traveller_Agency_Button;

add_action( 'customize_register', function( $manager ) {

    $manager->register_section_type( Traveller_Agency_Button::class );

    $manager->add_section(
        new Traveller_Agency_Button( $manager, 'traveller_agency_pro', [
            'title'       => __( 'Traveller Agency Pro', 'traveller-agency' ),
            'priority'    => 0,
            'button_text' => __( 'GET PREMIUM', 'traveller-agency' ),
            'button_url'  => esc_url( 'https://www.themagnifico.net/themes/travel-agency-wordpress-theme/', 'traveller-agency')
        ] )
    );

} );

// Load the JS and CSS.
add_action( 'customize_controls_enqueue_scripts', function() {

    $version = wp_get_theme()->get( 'Version' );

    wp_enqueue_script(
        'traveller-agency-customize-section-button',
        get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/js/customize-controls.js' ),
        [ 'customize-controls' ],
        $version,
        true
    );

    wp_enqueue_style(
        'traveller-agency-customize-section-button',
        get_theme_file_uri( 'vendor/wptrt/customize-section-button/public/css/customize-controls.css' ),
        [ 'customize-controls' ],
        $version
    );

} );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function traveller_agency_customize_register($wp_customize){
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

    //Header
    $wp_customize->add_section('traveller_agency_header',array(
        'title' => esc_html__('Header Option','traveller-agency')
    ));

    $wp_customize->add_setting('traveller_agency_phone',array(
        'default' => '',
        'sanitize_callback' => 'traveller_agency_sanitize_phone_number'
    ));
    $wp_customize->add_control('traveller_agency_phone',array(
        'label' => esc_html__('Phone Number','traveller-agency'),
        'section' => 'traveller_agency_header',
        'setting' => 'traveller_agency_phone',
        'type'  => 'text'
    ));

    //Slider
    $wp_customize->add_section('traveller_agency_top_slider',array(
        'title' => esc_html__('Slider Option','traveller-agency')
    ));

    for ( $count = 1; $count <= 3; $count++ ) {
        $wp_customize->add_setting( 'traveller_agency_top_slider_page' . $count, array(
            'default'           => '',
            'sanitize_callback' => 'traveller_agency_sanitize_dropdown_pages'
        ) );
        $wp_customize->add_control( 'traveller_agency_top_slider_page' . $count, array(
            'label'    => __( 'Select Slide Page', 'traveller-agency' ),
            'section'  => 'traveller_agency_top_slider',
            'type'     => 'dropdown-pages'
        ) );
    }

    $wp_customize->add_setting('traveller_agency_email_text',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('traveller_agency_email_text',array(
        'label' => esc_html__('Email Text','traveller-agency'),
        'section' => 'traveller_agency_top_slider',
        'setting' => 'traveller_agency_email_text',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('traveller_agency_email',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_email'
    ));
    $wp_customize->add_control('traveller_agency_email',array(
        'label' => esc_html__('Email','traveller-agency'),
        'section' => 'traveller_agency_top_slider',
        'setting' => 'traveller_agency_email',
        'type'  => 'text'
    ));

    // Best Destination

    $wp_customize->add_section('traveller_agency_best_destination',array(
        'title' => esc_html__('Best Destination','traveller-agency'),
        'description' => esc_html__('Here you have to select category which will display Best Destination in the home page.','traveller-agency')
    ));

    $wp_customize->add_setting('traveller_agency_best_destination_title',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('traveller_agency_best_destination_title',array(
        'label' => esc_html__('Title','traveller-agency'),
        'section' => 'traveller_agency_best_destination',
        'setting' => 'traveller_agency_best_destination_title',
        'type'  => 'text'
    ));

    $wp_customize->add_setting('traveller_agency_best_destination_short_title',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('traveller_agency_best_destination_short_title',array(
        'label' => esc_html__('Short Title','traveller-agency'),
        'section' => 'traveller_agency_best_destination',
        'setting' => 'traveller_agency_best_destination_short_title',
        'type'  => 'text'
    ));

    $categories = get_categories();
    $cat_post = array();
    $cat_post[]= 'select';
    $i = 0; 
    foreach($categories as $category){
        if($i==0){
            $default = $category->slug;
            $i++;
        }
        $cat_post[$category->slug] = $category->name;
    }

    $wp_customize->add_setting('traveller_agency_best_destination_category',array(
        'default'   => 'select',
        'sanitize_callback' => 'traveller_agency_sanitize_select',
    ));
    $wp_customize->add_control('traveller_agency_best_destination_category',array(
        'type'    => 'select',
        'choices' => $cat_post,
        'label' => __('Select category to display latest properties','traveller-agency'),
        'section' => 'traveller_agency_best_destination',
    ));
    
    // Footer
    $wp_customize->add_section('traveller_agency_site_footer_section', array(
        'title' => esc_html__('Footer', 'traveller-agency'),
    ));

    $wp_customize->add_setting('traveller_agency_footer_text_setting', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('traveller_agency_footer_text_setting', array(
        'label' => __('Replace the footer text', 'traveller-agency'),
        'section' => 'traveller_agency_site_footer_section',
        'priority' => 1,
        'type' => 'text',
    ));
}
add_action('customize_register', 'traveller_agency_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function traveller_agency_customize_partial_blogname(){
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function traveller_agency_customize_partial_blogdescription(){
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function traveller_agency_customize_preview_js(){
    wp_enqueue_script('traveller-agency-customizer', esc_url(get_template_directory_uri()) . '/assets/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'traveller_agency_customize_preview_js');