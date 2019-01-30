<?php
/**
 * Custom Fields
 *
 * @package consensus_Custom
 */

namespace ConsensusCustom;

/**
 * Custom_Fields Class
 *
 * @package ConsensusCustom
 */
class Custom_Fields {

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
	 * Enqueue Assets for custom fields.
	 *
	 * @action admin_enqueue_scripts
	 */
	public function enqueue_admin_assets() {
		global $post;

		$postid = isset($post->ID) ? $post->ID : '';

		wp_enqueue_script( "{$this->theme->assets_prefix}-custom-fields" );
		wp_add_inline_script( "{$this->theme->assets_prefix}-custom-fields", sprintf( 'ConsensusCustomFields.boot( %s );',
			wp_json_encode( array(
				'nonce' => wp_create_nonce( $this->theme->meta_prefix ),
				'postid' => $postid,
			) )
		) );
	}

	/**
	 * Register the page description metabox in page editor.
	 *
	 * @action add_meta_boxes
	 */
	public function register_theme_metaboxes( $post_type, $post ) {
		$template_file = get_post_meta( $post->ID, '_wp_page_template', true );
		$metaboxes     = $this->set_the_meta_boxes( $post->ID, $template_file );

		foreach ( $metaboxes as $metabox ) {
			add_meta_box(
				$metabox['id'],
				$metabox['description'],
				array( $this, 'get_metabox_html' ),
				$metabox['screen'],
				$metabox['context'],
				$metabox['priority'],
				$metabox['args']
			);
		}
	}

	/**
	 * Building function for metabox html.
	 *
	 * @param string $fields The html for all custom fields in that metabox.
	 */
	public function get_metabox_html( $postid, $fields = array() ) {
		// Noncename needed to verify where the data originated.
		wp_nonce_field( 'consensus-meta-settings', 'consensus_meta_noncename' );

		echo isset( $fields['args'] ) ? $fields['args'] : ''; // XSS ok. All html is sanitized before getting to this point.
	}

	/**
	 * Save the custom field meta metabox data.
	 *
	 * @action save_post
	 *
	 * @param integer $post_id the current posts id.
	 * @param object $post the current post object.
	 */
	public function save_meta( $post_id, $post ) {
		$value = array();

		if ( isset( $_POST['consensus_meta_noncename'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['consensus_meta_noncename'] ) ), 'consensus-meta-settings' ) ) { // WPSC: input var ok;
			return $post->ID;
		}

		// Is the user allowed to edit the post or page?
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $post->ID;
		}

		// Make sure this is not a revision.
		if ( 'revision' === $post->post_type ) {
			return;
		}

		// Sanitize all types of meta data.
		if ( isset( $_POST['page-meta'] ) && is_array( $_POST['page-meta'] ) ) {
			foreach ( $_POST['page-meta'] as $section => $meta ) {
				foreach ( $meta as $field_name => $field_value ) {
					if ( 'link-repeater' === $field_name ) {
						foreach ( $field_value as $num => $link_values ) {
							$value[ $section ][ $field_name ][ $num ]['title'] = sanitize_text_field( wp_unslash( $link_values['title'] ) );
							$value[ $section ][ $field_name ][ $num ]['url']   = str_replace( 'http://', '', esc_url_raw( $link_values['url'] ) );
						}
					} elseif ( 'wysiwyg-repeater' === $field_name ) {
						foreach ( $field_value as $num => $wysiwyg_values ) {
							$value[ $section ][ $field_name ][ $num ]['content'] = wp_kses_post( wp_unslash( $wysiwyg_values['content'] ) );
						}
					} elseif ( 'images' === $field_name ) {
						foreach ( $field_value as $num => $image_value ) {
							if ( '' !== $image_value ) {
								$value[ $section ][ $field_name ][$num] = sanitize_text_field( wp_unslash( $image_value ) );
							}
						}
					} else {
						$value[ $section ][ $field_name ] = wp_kses_post( wp_unslash( $field_value ) );
					}
				}
			}

			// Make sure the file array isn't empty
			if ( ! empty( $_FILES['page-meta']['name'] ) ) {

				// Setup the array of supported file types. In this case, it's just PDF.
				$supported_types = array( 'application/pdf' );

				// Get the file type of the upload
				$arr_file_type = wp_check_filetype( basename( $_FILES['page-meta']['name'] ) );
				$uploaded_type = $arr_file_type['type'];

				// Check if the type is supported. If not, throw an error.
				if ( in_array( $uploaded_type, $supported_types, true ) ) {

					// Use the WordPress API to upload the file
					$upload = wp_upload_bits( $_FILES['page-meta']['name'], null, wp_safe_remote_get( $_FILES['page-meta']['tmp_name'] ) );

					if ( isset( $upload['error'] ) && false !== $upload['error'] ) {
						wp_die( 'There was an error uploading your file. The error is: ' . esc_html( $upload['error'] ) );
					} else {
						$value['article-fields-section']['file'] = $upload['url'];
					} // End if().
				} else {
					wp_die( 'The file type that you\'ve uploaded is not a PDF.' );
				} // End if().
			} // End if().
		}

		update_post_meta( $post->ID, 'page-meta', $value );
	}

	/**
	 * Set all custom metaboxes in this function.
	 *
	 * @param integer $postid The post id of current page.
	 * @param string $page_template The page template to get metaboxes for.
	 */
	private function set_the_meta_boxes( $postid, $page_template ) {
		$metabox_array = array();
		$post_type     = get_post_type( $postid );

		// Page switch case.
		switch ( $page_template ) {
			case 'page-templates/homepage-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'home-section-';

				// Home section 1.
				$phrase_field  = $this->create_custom_field( $postid, $prefix . '1', 'first-phrase', 'text' );
				$phrase_field2 = $this->create_custom_field( $postid, $prefix . '1', 'second-phrase', 'wysiwyg' );
				$phrase_field3 = $this->create_custom_field( $postid, $prefix . '1', 'third-phrase', 'wysiwyg' );
				$anima_field   = $this->create_custom_field( $postid, $prefix . '1', 'image', 'image' );
				$anima_field2  = $this->create_custom_field( $postid, $prefix . '1', 'image-2', 'image' );
				$anima_field3  = $this->create_custom_field( $postid, $prefix . '1', 'image-3', 'image' );
				$anima_field4  = $this->create_custom_field( $postid, $prefix . '1', 'image-4', 'image' );
				$anima_field5  = $this->create_custom_field( $postid, $prefix . '1', 'image-5', 'image' );
				$anima_field6  = $this->create_custom_field( $postid, $prefix . '1', 'image-6', 'image' );
				$anima_field7  = $this->create_custom_field( $postid, $prefix . '1', 'image-7', 'image' );
				$anima_field8  = $this->create_custom_field( $postid, $prefix . '1', 'image-8', 'image' );
				$anima_field9  = $this->create_custom_field( $postid, $prefix . '1', 'image-9', 'image' );
				$anima_field10 = $this->create_custom_field( $postid, $prefix . '1', 'image-10', 'image' );
				$anima_field11 = $this->create_custom_field( $postid, $prefix . '1', 'image-11', 'image' );

				// Home section 2.
				$title_field    = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$subtitle_field = $this->create_custom_field( $postid, $prefix . '2', 'subtitle', 'text' );

				// Home section 3.
				$title_field2    = $this->create_custom_field( $postid, $prefix . '3', 'title', 'text' );
				$subtitle_field2 = $this->create_custom_field( $postid, $prefix . '3', 'subtitle', 'text' );
				$content_field   = $this->create_custom_field( $postid, $prefix . '3', 'content', 'wysiwyg' );
				$image_field     = $this->create_custom_field( $postid, $prefix . '3', 'image', 'image' );
				$image_field2    = $this->create_custom_field( $postid, $prefix . '3', 'image-2', 'image' );
				$subtitle_field3 = $this->create_custom_field( $postid, $prefix . '3', 'subtitle-2', 'text' );
				$content_field2  = $this->create_custom_field( $postid, $prefix . '3', 'content-2', 'wysiwyg' );

				// Home section 4.
				$title_field3 = $this->create_custom_field( $postid, $prefix . '4', 'title', 'text' );

				// Home section 5.
				$title_field4 = $this->create_custom_field( $postid, $prefix . '5', 'title', 'text' );

				// Home section 6.
				$title_field5    = $this->create_custom_field( $postid, $prefix . '6', 'title', 'text' );
				$subtitle_field4 = $this->create_custom_field( $postid, $prefix . '6', 'subtitle', 'text' );
				$content_field3  = $this->create_custom_field( $postid, $prefix . '6', 'content', 'wysiwyg' );
				$button_text     = $this->create_custom_field( $postid, $prefix . '6', 'button-text', 'text' );
				$button_url      = $this->create_custom_field( $postid, $prefix . '6', 'button-url', 'text' );
				$graphic         = $this->create_custom_field( $postid, $prefix . '6', 'graphic', 'image' );

				// Home section 7.
				$image_field3   = $this->create_custom_field( $postid, $prefix . '7', 'image', 'image' );
				$title_field6   = $this->create_custom_field( $postid, $prefix . '7', 'title', 'text' );
				$content_field4 = $this->create_custom_field( $postid, $prefix . '7', 'content', 'wysiwyg' );
				$button_text2   = $this->create_custom_field( $postid, $prefix . '7', 'button-text', 'text' );
				$button_url2    = $this->create_custom_field( $postid, $prefix . '7', 'button-url', 'text' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-consensus',
						'description' => 'Home Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $phrase_field . $phrase_field2 . $phrase_field3 . $anima_field . $anima_field2 . $anima_field3 . $anima_field4 . $anima_field5 . $anima_field6 . $anima_field7 . $anima_field8 . $anima_field9 . $anima_field10 . $anima_field11,
					),
					array(
						'id'          => $prefix . '2-consensus',
						'description' => 'Home Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field . $subtitle_field,
					),
					array(
						'id'          => $prefix . '3-consensus',
						'description' => 'Home Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field2 . $subtitle_field2 . $content_field . $image_field . $image_field2 . $subtitle_field3 . $content_field2,
					),
					array(
						'id'          => $prefix . '4-consensus',
						'description' => 'Home Section 4',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field3,
					),
					array(
						'id'          => $prefix . '5-consensus',
						'description' => 'Home Section 5',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field4,
					),
					array(
						'id'          => $prefix . '6-consensus',
						'description' => 'Home Section 6',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field5 . $subtitle_field4 . $content_field3 . $button_text . $button_url . $graphic,
					),
					array(
						'id'          => $prefix . '7-consensus',
						'description' => 'Home Section 7',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $image_field3 . $title_field6 . $content_field4 . $button_text2 . $button_url2,
					),
				);
				break;
			case 'page-templates/blog-template.php':

				$prefix = 'blog-section-consensus';

				$title_field    = $this->create_custom_field( $postid, $prefix, 'title', 'text' );
				$subtitle_field = $this->create_custom_field( $postid, $prefix, 'subtitle', 'text' );
				$form_field     = $this->create_custom_field( $postid, $prefix, 'form', 'text' );

				$metabox_array = array(
					array(
						'id'          => $prefix,
						'description' => 'Blog Fields',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field . $subtitle_field . $form_field,
					),
				);
				break;
		} // End switch().

		// Post Type switch case.
		switch ( $post_type ) {
			case 'use-case':
				$subtitle_field  = $this->create_custom_field( $postid, 'use-case-section-consensus', 'subtitle', 'text' );
				$client_field    = $this->create_custom_field( $postid, 'use-case-section-consensus', 'client', 'wysiwyg' );
				$situation_field = $this->create_custom_field( $postid, 'use-case-section-consensus', 'situation', 'wysiwyg' );
				$solution_field  = $this->create_custom_field( $postid, 'use-case-section-consensus', 'solution', 'wysiwyg' );
				$image_gallery   = $this->create_custom_field( $postid, 'use-case-section-consensus', 'images', 'image_repeater' );
				$logos           = $this->create_custom_field( $postid, 'use-case-section-consensus', 'logos', 'wysiwyg' );
				$black_logos     = $this->create_custom_field( $postid, 'use-case-section-consensus', 'black-logos', 'wysiwyg' );
				$metabox_array   = array(
					array(
						'id'          => 'use-case-section-consensus',
						'description' => 'Extra Fields For Use Cases',
						'screen'      => 'use-case',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $subtitle_field . $client_field . $situation_field . $solution_field . $logos . $black_logos . $image_gallery,
					),
				);
				break;
			case 'leadership':
				$work_field    = $this->create_custom_field( $postid, 'leaership-section-consensus', 'workdate', 'text' );
				$metabox_array = array(
					array(
						'id'          => 'leadership-section-consensus',
						'description' => 'Work Date',
						'screen'      => 'leadership',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $work_field,
					),
				);
				break;
			case 'post':
				$subtitle_field = $this->create_custom_field( $postid, 'article-consensus', 'subtitle', 'wysiwyg' );
				$metabox_array  = array(
					array(
						'id'          => 'article-consensus',
						'description' => 'Article Fields',
						'screen'      => 'post',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $subtitle_field,
					),
				);
				break;
		} // End switch().

		return $metabox_array;
	}

	/**
	 * Building function for registering and returning custom fields html.
	 *
	 * @param integer $postid The post/page/cpt to get custom field value from.
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $type The type of field to create.
	 * @param string $post_type The post type of the post specified.
	 *
	 * @return string
	 */
	private function create_custom_field( $postid, $section, $name, $type ) {
		$name2      = 'overview' === $name ? 'history' : $name;
		$value      = get_post_meta( $postid, 'page-meta', true );
		$value      = isset( $value[ $section ][ $name2 ] ) ? $value[ $section ][ $name2 ] : '';
		$field_type = 'get_' . $type . '_field_html';

		return $this->$field_type( $section, $name, $value );
	}

	/**
	 * Call back function for returning custom text field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_text_field_html( $section, $name, $value = '' ) {
		$allowed_tags          = wp_kses_allowed_html( 'post' );
		$allowed_tags['input'] = array(
			'value' => true,
			'name'  => true,
			'class' => true,
			'size'  => true,
		);

		$html  = '<div class="consensus-text-field">';
		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . ']" value="' . esc_html( $value ) . '" size="60">';
		$html .= '</div>';
		$html .= '</div>';

		return wp_kses( $html, $allowed_tags );
	}

	/**
	 * Call back function for returning custom text field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_image_field_html( $section, $name, $value = '' ) {
		$html  = '<div class="consensus-image-field">';
		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . ']" value="' . esc_html( $value ) . '" size="60">';
		$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Call back function for returning custom image text field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_image_text_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$image = isset( $field_value['image'] ) ? $field_value['image'] : '';

				$html .= '<div data-num="' . $field_num . '" class="consensus-image-text-field">';

				if ( 1 < count( $value ) ) {
					$html .= '<button type="button" class="remove-image-text-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][title]" value="' . esc_html( $title ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][image]" value="' . esc_html( $image ) . '" size="60">';
				$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
				$html .= '</div>';
				$html .= '</div>';
			}
		} else {

			$html  = '<div data-num="1" class="consensus-image-text-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][title]" value="' . esc_html( $value ) . '" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][image]" value="' . esc_html( $value ) . '" size="60">';
			$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '<button type="button" class="add-image-text-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning wysiwyg field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_wysiwyg_field_html( $section, $name, $value = '' ) {
		$html = '';

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][' . $name . ']',
		);

		$id = $section . '_' . $name . '_1';

		$html .= '<div class="consensus-wysiwyg-field">';
		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';

		ob_start();
		wp_editor( $value, $id, $options );

		$html .= ob_get_clean();
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Call back function for returning repeater wysiwyg / image field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_wysiwyg_image_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$image   = isset( $field_value['image'] ) ? $field_value['image'] : '';
				$content = isset( $field_value['content'] ) ? $field_value['content'] : '';
				$options = array(
					'media_buttons' => true,
					'textarea_name' => 'page-meta[' . $section . '][history][' . $field_num . '][content]',
				);
				$id      = $section . '_' . $name . '_' . $field_num . '_content';

				$html .= '<div data-num="' . $field_num . '" class="consensus-wysiwyg-image-repeater-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['content'] ) ) {
					$html .= '<button type="button" class="remove-wysiwyg-image-repeater-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

				ob_start();
				wp_editor( $content, $id, $options );

				$html .= ob_get_clean();
				$html .= \_WP_Editors::enqueue_scripts();
				$html .= \_WP_Editors::editor_js();
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][history][' . $field_num . '][image]" value="' . esc_attr( $image ) . '" size="60">';
				$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
				$html .= '</div>';
				$html .= '</div>';
			}
		} else {
			$options = array(
				'media_buttons' => true,
				'textarea_name' => 'page-meta[' . $section . '][history][1][content]',
			);
			$id      = $section . '_' . $name . '_1_content';

			$html .= '<div data-num="1" class="consensus-wysiwyg-image-repeater-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

			ob_start();
			wp_editor( '', $id, $options );

			$html .= ob_get_clean();
			$html .= \_WP_Editors::enqueue_scripts();
			$html .= \_WP_Editors::editor_js();
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][history][1][image]" value="" size="60">';
			$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-wysiwyg-image-repeater-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning repeater wysiwyg field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_wysiwyg_repeater_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title   = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$content = isset( $field_value['content'] ) ? $field_value['content'] : '';
				$url     = isset( $field_value['url'] ) ? $field_value['url'] : '';
				$options = array(
					'media_buttons' => true,
					'textarea_name' => 'page-meta[' . $section . '][' . $name . '][' . $field_num . '][content]',
				);
				$id      = $section . '_' . $name . '_' . $field_num;

				$html .= '<div data-num="' . $field_num . '" class="consensus-wysiwyg-repeater-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['content'] ) ) {
					$html .= '<button type="button" class="remove-wysiwyg-repeater-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

				ob_start();
				wp_editor( $content, $id, $options );

				$html .= ob_get_clean();
				$html .= \_WP_Editors::enqueue_scripts();
				$html .= \_WP_Editors::editor_js();
				$html .= '</div>';
			}
		} else {
			$options = array(
				'media_buttons' => true,
				'textarea_name' => 'page-meta[' . $section . '][' . $name . '][1][content]',
			);
			$id      = $section . '_' . $name . '_1';

			$html .= '<div data-num="1" class="consensus-wysiwyg-repeater-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

			ob_start();
			wp_editor( '', $id, $options );

			$html .= ob_get_clean();
			$html .= \_WP_Editors::enqueue_scripts();
			$html .= \_WP_Editors::editor_js();
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-wysiwyg-repeater-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning custom overlay repeater wysiwyg field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_overlay_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title   = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$content = isset( $field_value['content'] ) ? $field_value['content'] : '';
				$url     = isset( $field_value['url'] ) ? $field_value['url'] : '';
				$options = array(
					'media_buttons' => true,
					'textarea_name' => 'page-meta[' . $section . '][' . $name . '][' . $field_num . '][content]',
				);
				$id      = $section . '_' . $name . '_' . $field_num;

				$html .= '<div data-num="' . $field_num . '" class="consensus-overlay-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['content'] ) ) {
					$html .= '<button type="button" class="remove-overlay-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][title]" value="' . esc_attr( $title ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL (Leave empty if overlay)</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][url]" value="' . esc_attr( $url ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

				ob_start();
				wp_editor( $content, $id, $options );

				$html .= ob_get_clean();
				$html .= \_WP_Editors::enqueue_scripts();
				$html .= \_WP_Editors::editor_js();
				$html .= '</div>';
				$html .= '</div>';
			} // End foreach().
		} else {
			$options = array(
				'media_buttons' => true,
				'textarea_name' => 'page-meta[' . $section . '][' . $name . '][1][content]',
			);
			$id      = $section . '_' . $name . '_1';

			$html .= '<div data-num="1" class="consensus-overlay-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][title]" value="" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL (Leave empty if overlay)</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][url]" value="" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

			ob_start();
			wp_editor( '', $id, $options );

			$html .= ob_get_clean();
			$html .= \_WP_Editors::enqueue_scripts();
			$html .= \_WP_Editors::editor_js();
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-overlay-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning custom overlay repeater wysiwyg field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_link_repeater_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$url   = isset( $field_value['url'] ) ? $field_value['url'] : '';
				$html  .= '<div data-num="' . $field_num . '" class="consensus-link-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['url'] ) ) {
					$html .= '<button type="button" class="remove-link-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][title]" value="' . esc_attr( $title ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][url]" value="' . esc_attr( $url ) . '" size="60">';
				$html .= '</div>';
				$html .= '</div>';
			}
		} else {
			$html .= '<div data-num="1" class="consensus-link-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][title]" value="" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][url]" value="" size="60">';
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-link-field">+</button>';

		return $html;
	}

	/**
	 * Call back for file uploader field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_file_field_html( $section, $name, $value = array() ) {
		$html = '<div class="consensus-field-field">';

		if ( ! empty( $value ) ) {
			$html .= '<div class="file-name">' . esc_html( $value ) . '</div>';
		}

		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<input type="file" name="page-meta" value="" size="60">';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Call back function for returning custom image repeater field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_image_repeater_field_html( $section, $name, $value ) {
		$count = is_array( $value ) ? intval( count( $value ) ) + 1 : 1;
		$html  = '<div class="consensus-image-field">';
		if ( ! is_array( $value ) ) {
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1]" value="" size="60">';
			$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
			$html .= '</div>';
		} else {
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="consensus-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $count .']" value="" size="60">';
			$html .= '<button class="add-consensus-image">' . esc_html__( 'Add Image', 'consensus-custom' ) . '</button>';
			$html .= '</div>';
			$html .= '<div class="consensus-image-list-wrap" style="display: flex; flex-direction: row;">';

			foreach ( $value as $num => $image_url ) {
				$html .= '<div class="consensus-image">';
				$html .= '<span class="consensus-remove-image" style="font-size: 18px; margin-right: 4px; cursor: pointer;">';
				$html .= 'x';
				$html .= '</span>';
				$html .= '<img width="120px" style="padding-right: 20px;" src="' . $image_url . '">';
				$html .= '<input type="hidden" name="page-meta[' . $section . '][' . $name . '][' . $num . ']" id="consensus-' . $name . '-' . $num . '" value="' . $image_url . '">';
				$html .= '</div>';

				++$count;
			}

			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * AJAX Call Back function to return a new wysiwyg for overlay
	 *
	 * @action wp_ajax_get_overlay_field
	 */
	public function get_overlay_field() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['count'] ) || '' === $_POST['count'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$count   = intval( $_POST['count'] ) + 1; // WPCS: input var ok.
		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][overlay-repeater][' . $count . '][content]',
		);
		$id      = $section . '_overlay-repeater_' . $count;

		wp_editor( '', $id, $options );

		wp_die();
	}

	/**
	 * AJAX Call Back function to return a new wysiwyg.
	 *
	 * @action wp_ajax_get_wysiwyg_field
	 */
	public function get_wysiwyg_field() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['count'] ) || '' === $_POST['count'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$count   = intval( $_POST['count'] ) + 1; // WPCS: input var ok.
		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][wysiwyg-repeater][' . $count . '][content]',
		);
		$id      = $section . '_wysiwyg-repeater_' . $count;

		wp_editor( '', $id, $options );

		wp_die();
	}

	/**
	 * AJAX Call Back function to return a new wysiwyg.
	 *
	 * @action wp_ajax_get_wysiwyg_image_field
	 */
	public function get_wysiwyg_image_field() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['count'] ) || '' === $_POST['count'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$count   = intval( $_POST['count'] ) + 1; // WPCS: input var ok.
		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][history][' . $count . '][content]',
		);
		$id      = $section . '_history_' . $count . '_content';

		wp_editor( '', $id, $options );

		wp_die();
	}

	/**
	 * AJAX Call Back function to return a the overlay content for specified link.
	 *
	 * @action wp_ajax_get_overlay_content
	 */
	public function get_overlay_content() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		$homeid = get_page_by_title( 'Homepage' );

		if ( ! isset( $_POST['section'] ) || '' === $_POST['section'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay content failed' );
		}

		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.
		$number  = sanitize_text_field( wp_unslash( $_POST['number'] ) ); // WPCS: input var ok.
		$postid  = isset( $_POST['footer'] ) ? $homeid->ID : (int) $_POST['postid'];

		$post_meta = get_post_meta( $postid, 'page-meta', true );
		$title     = isset( $post_meta[ $section ]['overlay-repeater'][ $number ]['title'] ) ? $post_meta[ $section ]['overlay-repeater'][ $number ]['title'] : '';
		$content   = isset( $post_meta[ $section ]['overlay-repeater'][ $number ]['content'] ) ? $post_meta[ $section ]['overlay-repeater'][ $number ]['content'] : '';

		$html = '<div class="overlay-title">';
		$html .= $title;
		$html .= '</div>';
		$html .= '<div class="overlay-content">';
		$html .= $content;
		$html .= '</div>';

		wp_send_json_success( $html );
	}

	/**
	 * AJAX Call back to get articles based on search query.
	 *
	 * @action wp_ajax_get_articles
	 * @action wp_ajax_nopriv_get_articles
	 */
	public function get_articles() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['query'] ) ) { // WPCS: input var ok.
			wp_send_json_error( 'Article return failed.' );
		}

		$query = sanitize_text_field( wp_unslash( $_POST['query'] ) ); // WPCS: input var ok.
		$type  = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : ''; // WPCS: input var ok.

		if ( 'white' === $type ) {
			$type = 'white-paper';
		}

		if ( 'education' === $type ) {
			$type = 'education-center';
		}

		$sort = isset( $_POST['sort'] ) ? $_POST['sort'] : 'ASC';
		$args = array(
			's'              => $query,
			'post_type'      => 'post',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'order'          => $sort,
			'orderby'        => 'publish_date',
			'tax_query'      => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => array( $type ),
				),
			),
		);

		$results = get_posts( $args );
		$html    = $this->get_result_html( $results, $type );

		wp_send_json_success( $html );
	}

	/**
	 * Helper function to build html of article results.
	 *
	 * @param array $articles The array of results.
	 * @param string $type The type of html to return
	 */
	private function get_result_html( $articles, $type ) {
		$html = '';

		if ( is_array( $articles ) ) {
			switch ( $type ) {
				case 'white-paper' :
					foreach ( $articles as $white ) {
						ob_start();
						include( get_template_directory() . '/single-templates/white-paper.php' );
						$html .= ob_get_clean();
					}
					break;
				case 'education-center' :
					foreach ( $articles as $education ) {
						ob_start();
						include( get_template_directory() . '/single-templates/education-center.php' );
						$html .= ob_get_clean();
					}
					break;
				case 'media' :
					foreach ( $articles as $media ) {
						ob_start();
						include( get_template_directory() . '/single-templates/media.php' );
						$html .= ob_get_clean();
					}
					break;
			}
		}

		return $html;
	}
}
