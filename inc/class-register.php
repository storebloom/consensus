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
		wp_enqueue_script( 'custom-navigation' );
		wp_enqueue_script( 'custom-skip-link-focus-fix' );
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
		for ( $x = 1; $x <= 5; $x ++ ) {
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
	 * Register the Leadership post type.
	 *
	 * @action init
	 */
	public function register_leadership() {
		$supports = array( 'title', 'editor', 'thumbnail' );
		$labels = array(
			'name'                  => esc_html__( ' Leaderships', 'consensus-custom' ),
			'singular_name'         => esc_html__( ' Leadership', 'consensus-custom' ),
			'all_items'             => esc_html__( ' Leaderships', 'consensus-custom' ),
			'menu_name'             => _x( ' Leaderships', 'Admin menu name', 'consensus-custom' ),
			'add_new'               => esc_html__( 'Add New', 'consensus-custom' ),
			'add_new_item'          => esc_html__( 'Add new leadership', 'consensus-custom' ),
			'edit'                  => esc_html__( 'Edit', 'consensus-custom' ),
			'edit_item'             => esc_html__( 'Edit leadership', 'consensus-custom' ),
			'new_item'              => esc_html__( 'New leadership', 'consensus-custom' ),
			'view'                  => esc_html__( 'View leadership', 'consensus-custom' ),
			'view_item'             => esc_html__( 'View leadership', 'consensus-custom' ),
			'search_items'          => esc_html__( 'Search leaderships', 'consensus-custom' ),
			'not_found'             => esc_html__( 'No leaderships found', 'consensus-custom' ),
			'not_found_in_trash'    => esc_html__( 'No leaderships found in trash', 'consensus-custom' ),
			'parent'                => esc_html__( 'Parent leadership', 'consensus-custom' ),
			'featured_image'        => esc_html__( ' Leadership image', 'consensus-custom' ),
			'set_featured_image'    => esc_html__( 'Set leadership image', 'consensus-custom' ),
			'remove_featured_image' => esc_html__( 'Remove leadership image', 'consensus-custom' ),
			'use_featured_image'    => esc_html__( 'Use as leadership image', 'consensus-custom' ),
			'insert_into_item'      => esc_html__( 'Insert into leadership', 'consensus-custom' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this leadership', 'consensus-custom' ),
			'filter_items_list'     => esc_html__( 'Filter leaderships', 'consensus-custom' ),
			'items_list_navigation' => esc_html__( ' Leaderships navigation', 'consensus-custom' ),
			'items_list'            => esc_html__( ' Leaderships list', 'consensus-custom' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Leadership Members', 'consensus-custom' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-groups',
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'leadership',
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports,
			'show_in_rest'       => true,
		);

		register_post_type( 'leadership', $args );
	}

	/**
	 * Register the Use Case post type.
	 *
	 * @action init
	 */
	public function register_use_case() {
		$supports = array( 'title', 'thumbnail' );
		$labels = array(
			'name'                  => esc_html__( ' Use Cases', 'consensus-custom' ),
			'singular_name'         => esc_html__( ' Use Case', 'consensus-custom' ),
			'all_items'             => esc_html__( ' Use Cases', 'consensus-custom' ),
			'menu_name'             => _x( ' Use Cases', 'Admin menu name', 'consensus-custom' ),
			'add_new'               => esc_html__( 'Add New', 'consensus-custom' ),
			'add_new_item'          => esc_html__( 'Add new use case', 'consensus-custom' ),
			'edit'                  => esc_html__( 'Edit', 'consensus-custom' ),
			'edit_item'             => esc_html__( 'Edit use case', 'consensus-custom' ),
			'new_item'              => esc_html__( 'New use case', 'consensus-custom' ),
			'view'                  => esc_html__( 'View use case', 'consensus-custom' ),
			'view_item'             => esc_html__( 'View use case', 'consensus-custom' ),
			'search_items'          => esc_html__( 'Search use cases', 'consensus-custom' ),
			'not_found'             => esc_html__( 'No use cases found', 'consensus-custom' ),
			'not_found_in_trash'    => esc_html__( 'No use cases found in trash', 'consensus-custom' ),
			'parent'                => esc_html__( 'Parent use case', 'consensus-custom' ),
			'featured_image'        => esc_html__( ' Use Case image', 'consensus-custom' ),
			'set_featured_image'    => esc_html__( 'Set use case image', 'consensus-custom' ),
			'remove_featured_image' => esc_html__( 'Remove use case image', 'consensus-custom' ),
			'use_featured_image'    => esc_html__( 'Use as use case image', 'consensus-custom' ),
			'insert_into_item'      => esc_html__( 'Insert into use case', 'consensus-custom' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this use case', 'consensus-custom' ),
			'filter_items_list'     => esc_html__( 'Filter use cases', 'consensus-custom' ),
			'items_list_navigation' => esc_html__( ' Use Cases navigation', 'consensus-custom' ),
			'items_list'            => esc_html__( ' Use Cases list', 'consensus-custom' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Use Cases', 'consensus-custom' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-networking',
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'use-case',
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports,
			'show_in_rest'       => true,
			'taxonomy'           => array(
				'type',
			),
		);

		register_post_type( 'use-case', $args );
	}

	/**
	 * Register custom taxonomies.
	 *
	 * @action init
	 */
	public function register_taxonomies() {
		// Use Case Type labels.
		$type_labels = array(
			'name'              => _x( 'Types', 'taxonomy general name', 'consensus-custom' ),
			'singular_name'     => _x( 'Type', 'taxonomy singular name', 'consensus-custom' ),
			'search_items'      => esc_html__( 'Search Types', 'consensus-custom' ),
			'all_items'         => esc_html__( 'All Types', 'consensus-custom' ),
			'parent_item'       => esc_html__( 'Parent Type', 'consensus-custom' ),
			'parent_item_colon' => esc_html__( 'Parent Type:', 'consensus-custom' ),
			'edit_item'         => esc_html__( 'Edit Type', 'consensus-custom' ),
			'update_item'       => esc_html__( 'Update Type', 'consensus-custom' ),
			'add_new_item'      => esc_html__( 'Add New Type', 'consensus-custom' ),
			'new_item_name'     => esc_html__( 'New Type', 'consensus-custom' ),
			'menu_name'         => esc_html__( 'Types', 'consensus-custom' ),
			'not_found'         => esc_html__( 'No type found.', 'consensus-custom' ),
		);

		$taxonomies = array(
			'use-case' => array(
				'slug'         => 'type',
				'labels'       => $type_labels,
				'hierarchical' => true,
			),
		);

		// Register all custom taxonomies
		foreach ( $taxonomies as $type => $info ) {
			$args = array(
				'hierarchical'       => $info['hierarchical'],
				'labels'             => $info['labels'],
				'show_ui'            => true,
				'show_admin_column'  => true,
				'query_var'          => true,
				'rewrite'            => array(
					'slug' => $info['slug'],
				),
				'show_in_nav_menus'  => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => true,
			);

			register_taxonomy( $info['slug'], $type, $args );
		}
	}

	/**
	 * AJAX Callback function to retrieve leadership html.
	 *
	 * @action wp_ajax_get_leader
	 * @action wp_ajax_no_priv_get_leader
	 */
	public function get_leader() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['id'] ) || '' === $_POST['id'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$leader = get_post( intval( $_POST['id'] ) ); // WPCS: input var ok.
		$workdate = get_post_meta( $leader, 'workdate', true );

		$thumbnail = get_the_post_thumbnail_url( $leadership->ID );
		$main_image = false !== $thumbnail ? '<img src="' . $thumbnail . '">' : '';

		$html  = '<div class="leader-top">';
		$html .= '<div class="leader-name">';
		$html .= $leader->post_title;
		$html .= '</div>';
		$html .= '<div class="leader-close">';
		$html .= esc_html__( 'X Close', 'consensus-custom' );
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="leader-thumb">';
		$html .= $main_image;
		$html .= '</div>';
		$Html .= '<div class="leader-content">';
		$html .= '<div class="leader-left">';
		$html .= $workdate;
		$html .= '</div>';
		$html .= '<div class="leader-right">';
		$html .= '<h2>' . $leader->post_title . '</h2>';
		$html .= $leader->post_content;
		$html .= '</div>';
		$html .= '</div>';

		wp_send_json_success( $html );
	}

	/**
	 * AJAX Callback function to retrieve brand html.
	 *
	 * @action wp_ajax_get_brand_photos
	 * @action wp_ajax_no_priv_get_brand_photos
	 */
	public function get_brand() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['id'] ) || '' === $_POST['id'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get brand photos failed' );
		}

		$brand = $this->get_brand_photos( intval( $_POST['id'] ) ); // WPCS: input var ok.

		wp_send_json_success( $html );
	}

	/**
	 * Helper function to get brand photos.
	 *
	 * @param integer $brandid The brand id.
	 */
	public function get_brand_photos( $brandid ) {
		$photos = get_section_info( 'use-case-section', 'consensus', $brandid );
		$html = '';

		if ( isset( $photos['images'] ) && is_array( $photos['images']) ) {
			foreach ( $photos['images'] as $photo ) {
				$html .= '<img src="' . esc_url( $photo ) . '">';
			}
		}

		return $html;
	}

	/**
	 * AJAX Callback function to retrieve brands html.
	 *
	 * @action wp_ajax_get_brands
	 * @action wp_ajax_no_priv_get_brands
	 */
	public function get_brands() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['id'] ) || '' === $_POST['id'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get brands failed' );
		}

		$html = '';
		$term = intval( $_POST['id'] ); // WPCS: input var ok.
		$brands = get_posts(array(
			'post_type' => 'use-case',
			'numberposts' => 4,
			'tax_query' => array(
				array(
					'taxonomy' => 'type',
					'terms' => $term,
					'field' => 'id',
					'include_children' => false
				)
			)
		));

		$html .= '<div id="' . esc_attr( $term . '-type' ) . '" class="case-study-brands">';

		foreach( $brands as $brand ) :
			$html .= '<div data-brand="' . esc_attr( $brand->ID ) . '" class="case-study-brand">';
			$html .= esc_html( $brand->post_title );
			$html .= '</div>';
		endforeach;

		$first_brand = isset( $brands[0]->ID ) ? $brands[0]->ID : '';
		$photos = '' !== $first_brand ? get_section_info( 'use-case-section', 'consensus', $first_brand )['images'] : '';
		$html .= '</div>';
		$html .= '<div class="case-study-brand-photos">';

		foreach( $brands as $brand ) {
			$html .= '<div data-brand="' . esc_attr( $brand->ID ) . '" class="case-study-brand">';
			$html .= $this->get_brand_photos( $brand->ID );
			$html .= '</div>';
		}

		$html .= '</div>';
		$html .= '</div>';

		wp_send_json_success( $html );
	}
}
