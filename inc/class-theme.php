<?php
/**
 * Bootstraps the Consensus Custom theme.
 *
 * @package ConsensusCustom
 */

namespace ConsensusCustom;

/**
 * Main plugin bootstrap file.
 */
class Theme extends Theme_Base {

	/**
	 * Plugin assets prefix.
	 *
	 * @var string Lowercased dashed prefix.
	 */
	public $assets_prefix;

	/**
	 * Plugin meta prefix.
	 *
	 * @var string Lowercased underscored prefix.
	 */
	public $meta_prefix;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Initiate classes.
		$classes = array(
			new Register( $this ),
		);

		// Add classes doc hooks.
		foreach ( $classes as $instance ) {
			$this->add_doc_hooks( $instance );
		}

		// Define some prefixes to use througout the plugin.
		$this->assets_prefix = strtolower( preg_replace( '/\B([A-Z])/', '-$1', __NAMESPACE__ ) );
		$this->meta_prefix   = strtolower( preg_replace( '/\B([A-Z])/', '_$1', __NAMESPACE__ ) );
	}

	/**
	 * Register Front Assets
	 *
	 * @action wp_enqueue_scripts
	 */
	public function register_assets() {
		wp_enqueue_style( 'font', 'https://fonts.googleapis.com/css?family=Montserrat:500,600,700', array(), '1' );
		wp_enqueue_style( 'consensus-custom-style', get_stylesheet_uri(), null, time() );
		wp_register_script(
			"{$this->assets_prefix}-front-ui",
			"{$this->dir_url}/js/consensus-front-ui.js",
			array(
				'jquery',
				'wp-util',
			),
			'1.0.0',
			true
		);
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @action customize_preview_init
	 */
	public function consensus_custom_customize_preview_js() {
		wp_enqueue_script( 'consensus-custom-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @action body_class
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function consensus_custom_body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 *
	 * @action wp_head
	 */
	public function consensus_custom_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @action customize_register
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function consensus_custom_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector'        => '.site-title a',
				'render_callback' => 'consensus_custom_customize_partial_blogname',
			) );
			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'consensus_custom_customize_partial_blogdescription',
			) );
		}
	}
}
