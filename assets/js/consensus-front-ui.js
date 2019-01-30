/**
 * Consensus Front UI scripts.
 *
 * @package ConsensusCustom
 */

/* exported ConsensusFrontUI */
var ConsensusFrontUI = ( function( $, wp ) {
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
			var self = this;
			this.$container = $( '.page' );
			this.listen();

			//$('html,body').animate({ scrollTop: 0 }, 1);

			//setTimeout(function() { self.homeAnimate(); }, 1000);
		},

		/**
		 * Initiate listeners.
		 */
		listen: function() {
			var self = this;

			// Select service type.
			this.$container.on( 'click', '.service-type .service-name', function() {
				var type = $( this ).attr( 'data-type' );

				$( '.service-name' ).removeClass( 'selected' );
				$( '.service-brands' ).removeClass( 'selected' );
				$( '.service-desc' ).slideUp();


				$( this ).addClass( 'selected' ).siblings( '.service-desc' ).slideDown();
				$( '.service-brands[data-type="' + type + '"]' ).addClass( 'selected' );
			} );

			// Get leadership person info.
			this.$container.on( 'click', '.leadership-item', function() {
				var leaderid = $( this ).attr( 'data-leader' );

				self.getLeader( leaderid );
			} );

			// Close leadership popover.
			this.$container.on( 'click', '.leader-close', function() {
				$( '.leadership-popover' ).removeClass( 'open' ).html( '' );
			} );

			// Get brand photos for case studies section.
			this.$container.on( 'click', '.case-study-brand', function() {
				var brandid = $( this ).attr( 'data-brand' );

				self.getBrandPhotos( brandid );
			} );

			// Get brands for case study section.
			this.$container.on( 'click', '.case-study-type', function() {
				var termid = $( this ).attr( 'data-cs-type' ).replace( '-type', '' );

				self.getBrands( termid );
			} );

			// Trigger home anmiate scroll.
			this.$container.one( 'click', '.home-animation-scroll, .home-phrase-3', function() {
				if ( ! $( '.section-logo svg' ).hasClass('disengage')) {
					self.scrollAnimate();
				}
			} );
		},

		/**
		 * Get selected leader to display in popup.
		 *
		 * @param leaderid
		 */
		getLeader: function( leaderid ) {
			wp.ajax.post( 'get_leader', {
				id: leaderid,
				nonce: this.data.nonce
			} ).always( function( results ) {
				$( '.leadership-popover' ).addClass( 'open' ).html( results );
			} );
		},

		/**
		 * Get brand photos by id.
		 *
		 * @param brandid
		 */
		getBrandPhotos: function( brandid ) {
			wp.ajax.post( 'get_brand_photos', {
				id: brandid,
				nonce: this.data.nonce
			} ).always( function( results ) {
				$( '.case-study-brand-photos' ).html( results );
			} );
		},

		/**
		 * Get brands by id.
		 *
		 * @param termid
		 */
		getBrands: function( termid ) {
			wp.ajax.post( 'get_brands', {
				id: termid,
				nonce: this.data.nonce
			} ).always( function( results ) {
				$( '.case-study-left' ).html( results );
			} );
		},

		/**
		 * Home animation.
		 */
		homeAnimate: function() {
			var self = this;

			$( '.section-image' ).addClass( 'show' );
			$( '.section-image-right' ).addClass( 'show' );
			$( '.section-image-top' ).addClass( 'show' );
			$( '.section-logo svg' ).addClass( 'engage' );
			setTimeout(function() { $( '.section-logo .home-animation-scroll' ).addClass( 'engage' ); }, 3500);

			// On first scroll.
			$( document ).one('mousewheel', function() {
				if ( ! $( '.section-logo svg' ).hasClass('disengage')) {
					self.scrollAnimate();
				}
			} );
		},

		/**
		 * Home scroll animation.
		 */
		scrollAnimate: function() {
			$( '.section-logo svg' ).addClass( 'disengage' );
			$( 'body.home' ).addClass( 'scroll-animate' );
			$( '.section-logo .home-animation-scroll' ).removeClass( 'engage' );

			setTimeout(function() { $( '.home-phrase-1' ).addClass( 'engage' ); }, 500);
			setTimeout(function() { $( '.home-phrase-1' ).removeClass( 'engage' ); $( '.home-phrase-2' ).addClass( 'engage' ); }, 4000);
			setTimeout(function() { $( '.home-phrase-2' ).removeClass( 'engage' ); $( '.home-phrase-3' ).addClass( 'engage' ); }, 7000);
			setTimeout(function() {
				$( '.home-phrase-3' ).removeClass( 'engage' );
				$( 'body.home' ).css('overflow', 'unset');

				// Scroll to section 2.
				$('html,body').animate({
					scrollTop: $( '#home-section-2' ).offset().top
				}, 3500);

				$( '.section-image' ).removeClass( 'scroll-animate' );
				$( '.section-image-right' ).removeClass( 'scroll-animate' );
				$( '.section-image-top' ).removeClass( 'scroll-animate' );
				$( '.home-phrase-3' ).addClass( 'engage' );

				}, 10200);
			setTimeout(function() { $( '#page' ).animate({ marginTop:'100px' }, 3500); }, 10000);

			$( '.section-image' ).addClass( 'scroll-animate' );
			$( '.section-image-right' ).addClass( 'scroll-animate' );
			$( '.section-image-top' ).addClass( 'scroll-animate' );
		}
	};
} )( window.jQuery, window.wp );