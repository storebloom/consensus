/**
 * Consensus Custom Fields.
 *
 * @package ConsensusCustom
 */

/* exported ConsensusCustomFields */
var ConsensusCustomFields = ( function( $, wp ) {
	'use strict';

	return {
		/**
		 * Holds data.
		 */
		data: {},

		/**
		 * Boot plugin.
		 */
		boot: function( data ) {
			this.data = data;

			$( document ).ready( function() {
				this.init();
			}.bind( this ) );
		},

		/**
		 * Initialize plugin.
		 */
		init: function() {
			this.$container = $( '.wp-admin' );
			this.listen();
		},

		/**
		 * Initiate listeners.
		 */
		listen: function() {
			var self = this,
				timer = '',
				file_frame,
				wp_media_post_id = wp.media.model.settings.post.id, // Store the old id
				set_to_post_id = this.data.postid; // Set this

			this.$container.on( 'click', '.add-overlay-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .consensus-overlay-field:last-of-type' ).attr( 'data-num' );

				self.addOverlayField( count, section );
			} );

			// Add new wysiwyg field.
			this.$container.on( 'click', '.add-wysiwyg-repeater-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .consensus-wysiwyg-repeater-field:last-of-type' ).attr( 'data-num' );

				self.addWysiwygField( count, section );
			} );

			// Add new wysiwyg image field.
			this.$container.on( 'click', '.add-wysiwyg-image-repeater-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .consensus-wysiwyg-image-repeater-field:last-of-type' ).attr( 'data-num' );

				self.addWysiwygImageField( count, section );
			} );

			// Add new usecase tab field.
			this.$container.on( 'click', '.add-usecase-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .consensus-usecase-repeater-field:last-of-type' ).attr( 'data-num' );

				self.addUsecaseTabField( count, section );
			} );

			// Add new usecase field.
			this.$container.on( 'click', '.add-usecase-content-field', function() {
				var side = $( this ).attr( 'data-side' ),
					section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.consensus-usecase-repeater-field' ).attr( 'data-num' ),
					sideCount = $( this ).closest( '.' + side + '-repeater-section' ).find( '.consensus-tab-content-overlay:last-of-type' ).attr( 'data-num' );

				self.addUsecaseField( count, section, sideCount, side );
			} );

			// Add new link repeater field.
			this.$container.on( 'click', '.add-link-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .consensus-link-field:last-of-type' ).attr( 'data-num' );

				self.addLinkField( count, section );
			} );

			// Add new image text repeater field.
			this.$container.on( 'click', '.add-image-text-field', function() {
				var section = $( this ).closest( '.postbox' ).attr( 'id' ).replace( '-consensus', '' ),
					count = $( this ).closest( '.postbox' ).find( '.inside .consensus-image-text-field:last-of-type' ).attr( 'data-num' );

				self.addImageTextField( count, section );
			} );

			// Remove overlay field from admin.
			this.$container.on( 'click', '.remove-overlay-field', function() {
				$( this ).parent( '.consensus-overlay-field' ).remove();
			} );

			// Remove wysiwyg field from admin.
			this.$container.on( 'click', '.remove-wysiwyg-repeater-field', function() {
				$( this ).parent( '.consensus-wysiwyg-repeater-field' ).remove();
			} );

			// Remove wysiwyg image field from admin.
			this.$container.on( 'click', '.remove-wysiwyg-image-repeater-field', function() {
				$( this ).parent( '.consensus-wysiwyg-image-repeater-field' ).remove();
			} );

			// Remove link field from admin.
			this.$container.on( 'click', '.remove-link-field', function() {
				$( this ).parent( '.consensus-link-field' ).remove();
			} );

			// Remove tab field from admin.
			this.$container.on( 'click', '.remove-usecase-tab-field', function() {
				$( this ).parent( '.consensus-usecase-repeater-field' ).remove();
			} );

			// Remove image-repeater from admin.
			this.$container.on( 'click', '.consensus-remove-image', function() {
				$( this ).parent( '.consensus-image' ).remove();
			} );

			// Remove image text field from admin.
			this.$container.on( 'click', '.remove-image-text-field', function() {
				$( this ).parent( '.consensus-image-text-field' ).remove();
			} );

			// Custom image field.
			this.$container.on( 'click', '.add-consensus-image', function(e) {
				event.preventDefault();

				var attachment,
					self2 = this;

				// Set the wp.media post id so the uploader grabs the ID we want when initialised
				wp.media.model.settings.post.id = set_to_post_id;

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					//$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( self2 ).siblings( 'input' ).val( attachment.url );
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
				// Finally, open the modal
				file_frame.open();
			});
			// Restore the main ID when the add media button is pressed
			$( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		},

		/**
		 * Add new overlay field to section.
		 *
		 * @param count
		 * @param section
		 */
		addOverlayField: function( count, section ) {
			wp.ajax.post( 'get_overlay_field', {
				count: count,
				section: section,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newCount = parseInt( count ) + 1,
					theId = section + '_overlay-repeater_' + newCount;

				//  Add title and label.
				$( '#' + section + '-consensus .inside .consensus-overlay-field:last-of-type' ).after(
					'<div data-num="' + newCount + '" class="consensus-overlay-field">' +
					'<button type="button" class="remove-overlay-field">-</button>' +
					'<label class="consensus-admin-label">Overlay Repeater Title</label>' +
					'<input type="text" name="page-meta[' + section + '][overlay-repeater][' + newCount + '][title]" value="" size="60">' +
					'<label class="consensus-admin-label">Overlay Repeater URL (Leave empty if overlay)</label>' +
					'<input type="text" name="page-meta[' + section + '][overlay-repeater][' + newCount + '][url]" value="" size="60">' +
					'<label class="consensus-admin-label">Overlay Repeater Content</label>' +
					'</div>'
				);

				// Add new editor to the page.
				$( '#' + section + '-consensus .inside .consensus-overlay-field:last-of-type' ).append( results );

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new use case tab field to section.
		 *
		 * @param count
		 * @param section
		 */
		addUsecaseTabField: function( count, section ) {
			var self = this;

			wp.ajax.post( 'get_usecase_tab_field', {
				count: count,
				side: 'left',
				section: section,
				nonce: this.data.nonce
			} ).always( function( leftresults ) {
				var newCount = parseInt( count ) + 1,
					theLeftId = section + '_usecase-repeater_' + newCount + '_left_1_graph_content';

				$( '#' + section + '-consensus .inside .consensus-usecase-repeater-field:last-of-type' ). after(
					'<div data-num="' + newCount + '" class="consensus-usecase-repeater-field">' +
					'<label class="consensus-admin-label">Tab Name</label>' +
					'<button type="button" class="remove-tab-field">-</button>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][title]" value="" size="60">' +
					'<hr>' +
					'<div data-num="1" class="left-repeater-section">' +
					'<div class="side-title">Left Side</div>' +
					'<div data-num="' + newCount + '" data-side="left" class="consensus-tab-content-overlay">' +
					'<button data-side="left" type="button" class="remove-usecase-field">-</button>' +
					'<label class="consensus-admin-label">First Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-first]" value="">' +
					'<label class="consensus-admin-label">First Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-first-text]" value="" size="60">' +
					'<label class="consensus-admin-label">Second Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-second]" value="">' +
					'<label class="consensus-admin-label">Second Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][left][1][graph-second-text]" value="" size="60">' +
					'<label class="consensus-admin-label">Graph Content (To use for non graph content simply do not fill out any graph numbers above)</label>' +
					leftresults +
					'</div>' +
					'<button data-side="left" type="button" class="add-usecase-content-field">+</button>' +
					'</div>' +
					'<div data-num="1" class="right-repeater-section">' +
					'<div class="side-title">Right Side</div>' +
					'<div data-num="1" data-side="right" class="consensus-tab-content-overlay">' +
					'<button data-side="right" type="button" class="remove-usecase-field">-</button>' +
					'<label class="consensus-admin-label">First Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-first]" value="">' +
					'<label class="consensus-admin-label">First Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-first-text]" value="" size="60">' +
					'<label class="consensus-admin-label">Second Graph Number</label>' +
					'<input type="number" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-second]" value="">' +
					'<label class="consensus-admin-label">Second Graph Text</label>' +
					'<input type="text" name="page-meta[' + section + '][usecase-repeater][' + newCount + '][right][1][graph-second-text]" value="" size="60">' +
					'<label class="consensus-admin-label">Graph Content (To use for non graph content simply do not fill out any graph numbers above)</label>' +
					'</div>' +
					'</div>' +
					'<button data-side="right" type="button" class="add-usecase-content-field">+</button>' +
					'</div>'
				);

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theLeftId);
				quicktags({id : theLeftId});

				wp.ajax.post( 'get_usecase_tab_field', {
					count: count,
					side: 'right',
					section: section,
					nonce: self.data.nonce
				} ).always( function( rightresults ) {
					var newCount2 = parseInt( count ) + 1,
						theRightId = section + '_usecase-repeater_' + newCount2 + '_right_1_graph_content';

					//  Add title and label.
					$( '#' + section + '-consensus .inside .consensus-usecase-repeater-field:last-of-type .right-repeater-section' ).append( rightresults );

					tinymce.execCommand('mceAddEditor', false, theRightId);
					quicktags({id : theRightId});
				} );
			} );
		},

		/**
		 * Add new use case field to section.
		 *
		 * @param count
		 * @param section
		 * @param sideCount
		 * @param side
		 */
		addUsecaseField: function( count, section, sideCount, side ) {
			var self = this;

			wp.ajax.post( 'get_usecase_field', {
				count: count,
				side: side,
				section: section,
				side_count: sideCount,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newSideCount = parseInt( sideCount ) + 1,
					theId = section + '_usecase-repeater_' + count + '_' + side + '_' + newSideCount + '_graph_content';

				$( '#' + section + '-consensus .inside .consensus-usecase-repeater-field[data-num="' + count + '"] .' + side + '-repeater-section .consensus-tab-content-overlay:last-of-type' ). after(
                   '<div data-num="' + newSideCount + '" data-side="' + side + '" class="consensus-tab-content-overlay">' +
                   '<button data-side="' + side + '" type="button" class="remove-usecase-field">-</button>' +
                   '<label class="consensus-admin-label">First Graph Number</label>' +
                   '<input type="number" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-first]" value="">' +
                   '<label class="consensus-admin-label">First Graph Text</label>' +
                   '<input type="text" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-first-text]" value="" size="60">' +
                   '<label class="consensus-admin-label">Second Graph Number</label>' +
                   '<input type="number" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-second]" value="">' +
                   '<label class="consensus-admin-label">Second Graph Text</label>' +
                   '<input type="text" name="page-meta[' + section + '][usecase-repeater][' + count + '][' + side + '][' + newSideCount + '][graph-second-text]" value="" size="60">' +
                   '<label class="consensus-admin-label">Graph Content (To use for non graph content simply do not fill out any graph numbers above)</label>' +
                   results +
                   '</div>'
				);

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new WYSIWYG field to section.
		 *
		 * @param count
		 * @param section
		 */
		addWysiwygField: function( count, section ) {
			wp.ajax.post( 'get_wysiwyg_field', {
				count: count,
				section: section,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newCount = parseInt( count ) + 1,
					theId = section + '_wysiwyg-repeater_' + newCount;

				//  Add title and label.
				$( '#' + section + '-consensus .inside .consensus-wysiwyg-repeater-field:last-of-type' ).after(
					'<div data-num="' + newCount + '" class="consensus-wysiwyg-repeater-field">' +
					'<button type="button" class="remove-wysiwyg-repeater-field">-</button>' +
					'<label class="consensus-admin-label">Locations Content</label>' +
					'</div>'
				);

				// Add new editor to the page.
				$( '#' + section + '-consensus .inside .consensus-wysiwyg-repeater-field:last-of-type' ).append( results );

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new WYSIWYG Image field to section.
		 *
		 * @param count
		 * @param section
		 */
		addWysiwygImageField: function( count, section ) {
			wp.ajax.post( 'get_wysiwyg_image_field', {
				count: count,
				section: section,
				nonce: this.data.nonce
			} ).always( function( results ) {
				var newCount = parseInt( count ) + 1,
					theId = section + '_history_' + newCount + '_content' ;

				//  Add title and label.
				$( '#' + section + '-consensus .inside .consensus-wysiwyg-image-repeater-field:last-of-type' ).after(
					'<div data-num="' + newCount + '" class="consensus-wysiwyg-image-repeater-field">' +
					'<button type="button" class="remove-wysiwyg-image-repeater-field">-</button>' +
					'<label class="consensus-admin-label">New Content</label>' +
					'</div>'
				);

				// Add new editor to the page.
				$( '#' + section + '-consensus .inside .consensus-wysiwyg-image-repeater-field:last-of-type' ).append( results );
				$( '#' + section + '-consensus .inside .consensus-wysiwyg-image-repeater-field:last-of-type' ).append( '<div class="field-label-wrap">' +
				'<label class="consensus-admin-label">New Image</label>' +
				'<input type="text" name="page-meta[' + section + '][history][' + newCount + '][image]" value="" size="60">' +
				'<button class="add-consensus-image">Add Image</button>' +
				'</div>' );

				// Reload scripts/assets for tinymce for new editor.
				tinymce.execCommand('mceAddEditor', false, theId);
				quicktags({id : theId});
			} );
		},

		/**
		 * Add new link field to section.
		 *
		 * @param count
		 * @param section
		 */
		addLinkField: function( count, section ) {
			var newCount = parseInt( count ) + 1;

			//  Add title and label.
			$( '#' + section + '-consensus .inside .consensus-link-field:last-of-type' ).after(
				'<div data-num="' + newCount + '" class="consensus-link-field">' +
				'<label class="consensus-admin-label">Link repeater Title</label>' +
				'<input type="text" name="page-meta[' + section + '][link-repeater][' + newCount + '][title]" value="" size="60">' +
				'<label class="consensus-admin-label">Link repeater URL</label>' +
				'<input type="text" name="page-meta[' + section + '][link-repeater][' + newCount + '][url]" value="" size="60">' +
				'</div>'
			);

			// Add new editor to the page.
			$( '#' + section + '-consensus .inside .consensus-link-field:last-of-type' ).append( results );
		},

		/**
		 * Add new image text field to section.
		 *
		 * @param count
		 * @param section
		 */
		addImageTextField: function( count, section ) {
			var newCount = parseInt( count ) + 1;

			//  Add title and label.
			$( '#' + section + '-consensus .inside .consensus-image-text-field:last-of-type' ).after(
				'<div data-num="' + newCount + '" class="consensus-image-text-field">' +
				'<div class="field-label-wrap">' +
				'<label class="consensus-admin-label">Partners Title</label>' +
				'<input type="text" name="page-meta[' + section + '][partners][' + newCount + '][title]" value="" size="60">' +
				'<button type="button" class="remove-image-text-repeater-field">-</button>' +
				'</div>' +
				'<div class="field-label-wrap">' +
				'<label class="consensus-admin-label">Partners Image</label>' +
				'<input type="text" name="page-meta[' + section + '][partners][' + newCount + '][image]" value="" size="60">' +
				'<button class="add-consensus-image">Add Image</button>' +
				'</div>' +
				'</div>'
			);
		}
	};
} )( window.jQuery, window.wp );
