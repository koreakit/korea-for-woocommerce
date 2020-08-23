jQuery(function($) {
	"use strict";

	var wc_korea_postcode = {
		$body: $( document.body ),
		$country: $('#billing_country, #shipping_country'),
		$postcode: $('input#billing_postcode, input#shipping_postcode'),
		$postcodeForm: $('#postcode_form'),
		$postcodeClose: $('#postcode_close'),
		$address1: $('input#billing_address_1, input#shipping_address_1'),
		$address2: $('input#billing_address_2, input#shipping_address_2'),
		$city: $('input#billing_city, input#shipping_city'),

		init: function() {
			wc_korea_postcode.$country.on('change', this.onChange).trigger('change');
			wc_korea_postcode.$postcode.on('click', this.onClick);
			wc_korea_postcode.$body.on('click', '#postcode_close', this.onClose);
		},

		onChange: function() {
			wc_korea_postcode.country = $(this).val();

			if ( 'KR' !== wc_korea_postcode.country ) {
				wc_korea_postcode.$postcode.removeAttr('readonly onkeypress');
				wc_korea_postcode.$body.find('#postcode_form').remove();
				return;
			}

			wc_korea_postcode.$postcode.attr({
				'readonly': 'readonly',
				'onkeypress': 'return false;'
			});

			wc_korea_postcode.$postcode.closest('p').append('<div id="postcode_form" class="'+ _postcode.theme.displaymode +'" style="display: none;"><img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="postcode_close" style="cursor:pointer; position:absolute; right:0px; top:-1px; z-index:1" alt="접기 버튼"></div>');
		},

		onClick: function() {
			if ( 'KR' !== wc_korea_postcode.country ) {
				return;
			}

			var daumPostcode = new daum.Postcode({
				alwaysShowEngAddr: true,
				hideEngBtn: false,
				theme: {
					bgColor          : _postcode.theme.bgcolor,
					searchBgColor    : _postcode.theme.searchbgcolor,
					contentBgColor   : _postcode.theme.contentbgcolor,
					pageBgColor      : _postcode.theme.pagebgcolor,
					textColor        : _postcode.theme.textcolor,
					queryTextColor   : _postcode.theme.querytextcolor,
					postcodeTextColor: _postcode.theme.postcodetextcolor,
					emphTextColor    : _postcode.theme.emphtextcolor,
					outlineColor     : _postcode.theme.outlinecolor
				},
				oncomplete: function(data) {
					$(document.body).find('#billing_postcode').val(data.zonecode);
					$(document.body).find('#billing_address_1').val(data.address);
					$(document.body).find('#billing_address_2').focus();
					$(document.body).find('#billing_city').val(data.sido);

					wc_korea_postcode.$body.find('#postcode_form').hide();
				},
				width : '100%',
				height: '100%'
			}).embed('postcode_form');

			wc_korea_postcode.$body.find('#postcode_form').show();
		},

		onClose: function() {
			wc_korea_postcode.$body.find('#postcode_form').hide();
		}

	};

	wc_korea_postcode.init();
});
