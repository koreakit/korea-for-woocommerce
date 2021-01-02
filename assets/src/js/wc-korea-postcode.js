jQuery(function($) {
	"use strict";

	var wc_korea_postcode = {
		init: function() {
			var $body = $( document.body );

			/**
			 * Korean Address Autocomplete
			 */
			$body.find( '#billing_country' ).on( 'change', this.onCountryChange ).trigger( 'change' );
			$body.find( '#shipping_country' ).on( 'change', this.onCountryChange ).trigger( 'change' );
			$body.find( '#billing_postcode' ).on( 'click', this.onPostcodeClick );
			$body.find( '#shipping_postcode' ).on( 'click', this.onPostcodeClick );
			$body.find( '.address-autocomplete-close' ).on( 'click', function() {
				$( this ).parent().hide();
			});
		},

		onCountryChange: function() {
			var $this     = $( this ),
				$body     = $( document.body ),
				type      = ( $this.attr( 'name' ).indexOf( 'billing' ) !== -1 ) ? 'billing' : 'shipping',
				country   = $( '#' + type + '_country' ).val(),
				$postcode = $( '#' + type + '_postcode' );

			if ( 'KR' !== country ) {
				$postcode.removeAttr( 'readonly onkeypress' );
				$body.find( '#' + type + '-address-autocomplete' ).remove();
				return;
			}

			$postcode.attr({
				'readonly': 'readonly',
				'onkeypress': 'return false;'
			});

			$postcode.closest( 'p' ).append( '<div id="' + type + '-address-autocomplete" class="'+ type + '-address-autocomplete ' + _postcode.displaymode + '" style="display: none;"><img src="//t1.daumcdn.net/postcode/resource/images/close.png" class="address-autocomplete-close" style="cursor:pointer; position:absolute; right:0px; top:-1px; z-index:1" alt="접기 버튼"></div>' );
		},

		onPostcodeClick: function() {
			var $this   = $( this ),
				$body   = $( document.body ),
				type    = ( $this.attr( 'name' ).indexOf( 'billing' ) !== -1 ) ? 'billing' : 'shipping',
				country = $( '#' + type + '_country' ).val();

			if ( 'KR' !== country ) {
				return;
			}

			var daumPostcode = new daum.Postcode({
				alwaysShowEngAddr: true,
				hideEngBtn: false,
				theme: _postcode.theme,
				oncomplete: function(data) {
					$body.find( '#' + type + '_postcode').val( data.zonecode );
					$body.find( '#' + type + '_address_1').val( data.address );
					$body.find( '#' + type + '_address_2').focus();
					$body.find( '#' + type + '_city').val( data.sido );
					$body.find( '#' + type + '-address-autocomplete' ).hide();
				},
				width : '100%',
				height: '100%'
			}).embed( type + '-address-autocomplete' );

			$body.find( '#' + type + '-address-autocomplete' ).show();
		}

	};

	wc_korea_postcode.init();
});
