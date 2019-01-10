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

	/**
	 * Register theme widget areas.
	 *
	 * @action widgets_init
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function consensus_custom_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'consensus-custom' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'consensus-custom' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}

	/**
	 * Register new user roles for theme.
	 *
	 * @action init
	 */
	public function add_new_roles() {
		// Add Marketing role. Must remove role to remove capabilities and recreate.
		$result = add_role(
			'markerting',
			__( 'Marketing' ),
			array(
				'read'                   => true,
				'edit_posts'             => true,
				'edit_pages'             => true,
				'delete_posts'           => true,
				'delete_published_posts' => true,
				'edit_published_posts'   => true,
				'publish_posts'          => true,
				'upload_files'           => true,
				'edit_theme_options'     => true,
				'edit_plugins'           => false,
				'edit_users'             => false,
				'manage_options'         => false,
			)
		);
	}

	/**
	 * Register theme metaboxes.
	 *
	 * @action add_meta_boxes
	 */
	public function register_metaboxes() {
		// Add the metabox for selecting header type.  Center logo of default.
		add_meta_box( 'center_logo', esc_html__( 'Center logo in header?', 'consensus-custom' ), array( $this, 'center_logo_custom_box' ), array( 'page' ), 'side', 'high' );

		// Add the metabox for hiding footer.
		add_meta_box( 'hide_footer', esc_html__( 'Hide footer on page?', 'consensus-custom' ), array( $this, 'hide_footer_custom_box' ), array( 'page' ), 'side', 'high' );

	}

	/**
	 * Call back function for center_logo metabox.
	 */
	public function center_logo_custom_box() {
		global $post;

		$center_logo = get_post_meta( $post->ID, 'center-logo', true );

		// Include the meta box template.
		include_once "{$this->theme->dir_path}/../templates/metabox/center-logo.php";
	}

	/**
	 * Call back function for hide_footer metabox.
	 */
	public function hide_footer_custom_box() {
		global $post;

		$hide_footer = get_post_meta( $post->ID, 'hide-footer', true );

		// Include the meta box template.
		include_once "{$this->theme->dir_path}/../templates/metabox/hide-footer.php";
	}

	/**
	 * Save metabox data.
	 *
	 * @action save_post
	 * @param $post_id
	 */
	public function wporg_save_postdata( $post_id ) {
		$center_logo = isset( $_POST['center-logo'] ) && 'on' === $_POST['center-logo'] ? 'on' : ''; // WPCS: CSRF ok.
		$hide_footer = isset( $_POST['hide-footer'] ) && 'on' === $_POST['hide-footer'] ? 'on' : ''; // WPCS: CSRF ok.

		update_post_meta( $post_id, 'center-logo', $center_logo );
		update_post_meta( $post_id, 'hide-footer', $hide_footer );
	}
}
