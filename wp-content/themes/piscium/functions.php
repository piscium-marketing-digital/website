<?php
/**
 * Piscium functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Piscium
 */

if ( ! function_exists( 'piscium_setup' ) ) :
function piscium_setup() {
	load_theme_textdomain( 'piscium', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'piscium' ),
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	add_theme_support( 'custom-background', apply_filters( 'piscium_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'piscium_setup' );

function piscium_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'piscium_content_width', 640 );
}
add_action( 'after_setup_theme', 'piscium_content_width', 0 );

function piscium_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'piscium' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'piscium' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'piscium_widgets_init' );

function piscium_scripts() {
	wp_enqueue_style( 'montserrat-font-style', 'https://fonts.googleapis.com/css?family=Montserrat:200,200i,400,400i,600' );
	wp_enqueue_style( 'font-awesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
	wp_enqueue_style( 'piscium-style', get_stylesheet_uri() );

	wp_enqueue_script( 'bootstrap-script', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'piscium_scripts' );

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/jetpack.php';
