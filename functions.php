<?php
/**
 * Consensus Custom functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ConsensusCustom
 */

if ( ! function_exists( 'consensus_custom_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function consensus_custom_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Consensus Custom, use a find and replace
		 * to change 'consensus-custom' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'consensus-custom', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'consensus_custom_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'consensus_custom_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function consensus_custom_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'consensus_custom_content_width', 640 );
}
add_action( 'after_setup_theme', 'consensus_custom_content_width', 0 );

/**
 * Helper function to retreive page sections data.
 *
 * @param string  $page The page name to get info for.
 * @param string  $section The Section of the page to get info for.
 * @param integer $postid The post id to get info from.
 *
 * @return array
 */
function get_section_info( $page, $section, $postid ) {
	$the_meta = get_post_meta( $postid, 'page-meta', true );

	return ! empty( $the_meta[ $page . '-' . $section ] ) ? $the_meta[ $page . '-' . $section ] : '';
}

/**
 * Bootstrap the theme.
 */
require get_template_directory() . '/inc/class-theme-base.php';
require get_template_directory() . '/inc/class-theme.php';

$consensus_custom_theme = new ConsensusCustom\Theme();
