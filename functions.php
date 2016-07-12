<?php
/**
 * Beverly Hill LA Dentist functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package Beverly Hill LA Dentist
 * @since 0.1.0
 */

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'BHLA Genesis Child' );
define( 'CHILD_THEME_URL', 'http://tannermoushey.com' );
define( 'CHILD_THEME_VERSION', '0.1.0' );

BHLA_Init::get_instance();
class BHLA_Init {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the BHLA_Init
	 *
	 * @return BHLA_Init
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof BHLA_Init ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		$this->hooks();
	}

	protected function hooks() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_styles' ) );
		add_action( 'wp_head', array( $this, 'header_meta' ) );

		add_filter( 'wp_nav_menu_args', array( $this, 'secondary_menu_args' ) );
		add_filter( 'genesis_author_box_gravatar_size', array( $this, 'author_box_gravatar' ) );
		add_filter( 'genesis_comment_list_args', array( $this, 'comments_gravatar' ) );
	}

	protected function includes() {
		//* Start the engine
		include_once( get_template_directory() . '/lib/init.php' );

		//* Setup Theme
		include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

		//* Set Localization (do not remove)
		load_child_theme_textdomain( 'genesis-sample', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'genesis-sample' ) );

		//* Add Image upload and Color select to WordPress Theme Customizer
		require_once( get_stylesheet_directory() . '/lib/customize.php' );

		//* Include Customizer CSS
		include_once( get_stylesheet_directory() . '/lib/output.php' );
	}

	/**
	 * Set up theme defaults and register supported WordPress features.
	 *
	 * @uses load_theme_textdomain() For translation/localization support.
	 *
	 * @since 0.1.0
	 */
	public function setup() {
		/**
		 * Makes Beverly Hill LA Dentist available for translation.
		 *
		 * Translations can be added to the /lang directory.
		 * If you're building a theme based on Beverly Hill LA Dentist, use a find and replace
		 * to change 'bhla' to the name of your theme in all template files.
		 */
		load_theme_textdomain( 'bhla', get_template_directory() . '/languages' );

		//* Add HTML5 markup structure
		add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

		//* Add Accessibility support
		add_theme_support( 'genesis-accessibility', array(
			'404-page',
			'drop-down-menu',
			'headings',
			'rems',
			'search-form',
			'skip-links'
		) );

		//* Add viewport meta tag for mobile browsers
		add_theme_support( 'genesis-responsive-viewport' );

		//* Add support for custom header
		add_theme_support( 'custom-header', array(
			'width'           => 600,
			'height'          => 160,
			'header-selector' => '.site-title a',
			'header-text'     => false,
			'flex-height'     => true,
		) );

		//* Add support for custom background
		add_theme_support( 'custom-background' );

		//* Add support for after entry widget
		add_theme_support( 'genesis-after-entry-widget-area' );

		//* Add support for 3-column footer widgets
		add_theme_support( 'genesis-footer-widgets', 3 );

		//* Add Image Sizes
		add_image_size( 'featured-image', 720, 400, true );

		//* Rename primary and secondary navigation menus
		add_theme_support( 'genesis-menus', array(
			'primary'   => __( 'After Header Menu', 'genesis-sample' ),
			'secondary' => __( 'Footer Menu', 'genesis-sample' )
		) );

		//* Reposition the secondary navigation menu
		remove_action( 'genesis_after_header', 'genesis_do_subnav' );
		add_action( 'genesis_footer', 'genesis_do_subnav', 5 );
	}

	/**
	 * Enqueue scripts and styles for front-end.
	 *
	 * @since 0.1.0
	 */
	public function scripts_styles() {
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

//		wp_enqueue_script( 'bhla', get_stylesheet_directory_uri() . "/assets/js/beverly_hill_la_dentist{$postfix}.js", array(), CHILD_THEME_VERSION, true );
//		wp_enqueue_style( 'bhla', get_stylesheet_directory_uri() . "/assets/css/beverly_hill_la_dentist{$postfix}.css", array(), CHILD_THEME_VERSION );

		wp_enqueue_style( 'bhla-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700', array(), CHILD_THEME_VERSION );
		wp_enqueue_style( 'dashicons' );

		wp_enqueue_script( 'bhla-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
		$output = array(
			'mainMenu' => __( 'Menu', 'bhla' ),
			'subMenu'  => __( 'Menu', 'bhla' ),
		);
		wp_localize_script( 'bhla-responsive-menu', 'genesisSampleL10n', $output );
	}

	/**
	 * Add humans.txt to the <head> element.
	 */
	public function header_meta() {
		$humans = '<link type="text/plain" rel="author" href="' . get_template_directory_uri() . '/humans.txt" />';

		echo apply_filters( 'bhla_humans', $humans );
	}

	/**
	 * Reduce the secondary navigation menu to one level depth
	 */
	public function secondary_menu_args( $args ) {

		if ( 'secondary' != $args['theme_location'] ) {
			return $args;
		}

		$args['depth'] = 1;

		return $args;

	}

	/**
	 * Modify size of the Gravatar in the author box
	 *
	 * @param $size
	 *
	 * @return int
	 */
	public function author_box_gravatar( $size ) {
		return 90;
	}

	/**
	 * Modify size of the Gravatar in the entry comments
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function comments_gravatar( $args ) {

		$args['avatar_size'] = 60;

		return $args;

	}

}