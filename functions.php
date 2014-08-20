<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis Sample Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.1.2' );

//* Enqueue Styles and Scripts
add_action( 'wp_enqueue_scripts', 'genesis_sample_scripts' );
function genesis_sample_scripts() {

	$minnified = '.min';

	//* Should we load minified scripts? Also enqueue live reload to allow for extensionless reloading with 'grunt watch'.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) {

		$minnified = '';
		wp_enqueue_script( 'live-reload', '//localhost:35729/livereload.js', array(), CHILD_THEME_VERSION, true );

	}

	//* Add Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts' );

	//* Remove default CSS
	wp_dequeue_style( 'genesis-sample-theme' );

	//* Add compiled CSS
	wp_register_style( 'genesis-sample-styles', get_stylesheet_directory_uri() . '/style' . $minnified . '.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'genesis-sample-styles' );

	//* Add compiled JS
	wp_enqueue_script( 'genesis-sample-scripts', get_stylesheet_directory_uri() . '/js/project' . $minnified . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );
