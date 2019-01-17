<?php
/**
 * Register
 *
 * @package ConsensusCustom
 */

namespace ConsensusCustom;

/**
 * Register Class
 *
 * @package ConsensusCustom
 */
class Register {

	/**
	 * Theme instance.
	 *
	 * @var object
	 */
	public $theme;

	/**
	 * Class constructor.
	 *
	 * @param object $plugin Plugin class.
	 */
	public function __construct( $theme ) {
		$this->theme = $theme;
	}

	/**
	 * Register theme menus.
	 *
	 * @action after_setup_theme
	 */
	public function nav_menu() {
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'consensus-custom' ),
			)
		);
	}

	/**
	 * Register footer widget area.
	 *
	 * @action widgets_init
	 */
	public function footer_widgets_init() {
		for ( $x = 1; $x <= 4; $x ++ ) {
			register_sidebar(
				array(
					'name'          => 'Footer ' . $x,
					'id'            => 'footer-' . $x,
					'description'   => '',
					'class'         => '',
					'before_widget' => '<li id="%1$s" class="widget %2$s">',
					'after_widget'  => '</li>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '</h2>',
				)
			);
		}
	}

	/**
	 * Enqueue Assets for front ui.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_assets() {
		wp_enqueue_script( "{$this->theme->assets_prefix}-front-ui" );
		wp_add_inline_script( "{$this->theme->assets_prefix}-front-ui", sprintf( 'ConsensusFrontUI.boot( %s );',
			wp_json_encode( array(
				'nonce' => wp_create_nonce( $this->theme->meta_prefix ),
			) )
		) );

		// Possible theme dependencies.  Will remove if unused.
		wp_enqueue_script( 'consensus-custom-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
		wp_enqueue_script( 'consensus-custom-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	}
}
