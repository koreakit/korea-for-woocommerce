jQuery( function( $ ) {
	'use strict';

	var $body         = $( document.body ),
		postcode      = 'woocommerce_korea_postcode_yn',
		kakaochannel  = 'woocommerce_korea_kakaochannel_yn',
		navertalktalk = 'woocommerce_korea_navertalktalk_yn';

	var wc_korea_settings = {
		/**
		 * Initialize
		 */
		init: function() {
			this.bind();

			$( 'input[name="'+postcode+'"]' ).trigger('change');
			$( 'input[name="'+kakaochannel+'"]' ).trigger('change');
			$( 'input[name="'+navertalktalk+'"]' ).trigger('change');
		},

		bind: function() {
			var self = this;

			/**
			 * Postcode: Activate/Disable
			 */
			$body.on( 'change', 'input[name="'+postcode+'"]', function() {
				if ( $( this ).is( ':checked' ) ) {
					$('.show_if_postcode').closest('tr').show();
				} else {
					$('.show_if_postcode').closest('tr').hide();
				}
			});

			/**
			 * Kakao Channel: Activate/Disable
			 */
			$body.on( 'change', 'input[name="'+kakaochannel+'"]', function() {
				if ( $( this ).is( ':checked' ) ) {
					$('.show_if_kakaochannel').closest('tr').show();
				} else {
					$('.show_if_kakaochannel').closest('tr').hide();
				}
			});

			/**
			 * Naver TalkTalk: Activate/Disable
			 */
			$body.on( 'change', 'input[name="'+navertalktalk+'"]', function() {
				if ( $( this ).is( ':checked' ) ) {
					$('.show_if_navertalktalk').closest('tr').show();
				} else {
					$('.show_if_navertalktalk').closest('tr').hide();
				}
			});

		}
	};

	wc_korea_settings.init();
} );
