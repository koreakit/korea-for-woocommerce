jQuery(function($) {
	"use strict";

	var wc_korea_postcode = {
		$country: $('select#billing_country, select#shipping_country'),
		$postcode: $('input#billing_postcode, input#shipping_postcode'),
		$address1: $('input#billing_address_1, input#shipping_address_1'),
		$address2: $('input#billing_address_2, input#shipping_address_2'),
		$city: $('input#billing_city, input#shipping_city'),
		init: function() {
			wc_korea_postcode.$country.on('change', this.onChange).trigger('change');
			wc_korea_postcode.$postcode.on('click', this.onClick);
		},
		onChange: function() {
			wc_korea_postcode.country = $(this).val();

			if ( 'KR' !== wc_korea_postcode.country ) {
				wc_korea_postcode.$postcode.removeAttr('readonly onkeypress');
				return;
			}

			wc_korea_postcode.$postcode.attr({
				'readonly': 'readonly',
				'onkeypress': 'return false;'
			});
		},
		onClick: function() {
			if ( 'KR' !== wc_korea_postcode.country ) {
				return;
			}

			daum.postcode.load(function(){
				new daum.Postcode({
					alwaysShowEngAddr: true,
					hideEngBtn: false,
					theme: {
						bgColor: _postcode.theme.bgcolor,
						searchBgColor: _postcode.theme.searchbgcolor,
						contentBgColor: _postcode.theme.contentbgcolor,
						pageBgColor: _postcode.theme.pagebgcolor,
						textColor: _postcode.theme.textcolor,
						queryTextColor: _postcode.theme.querytextcolor,
						postcodeTextColor: _postcode.theme.postcodetextcolor,
						emphTextColor: _postcode.theme.emphtextcolor,
						outlineColor: _postcode.theme.outlinecolor
					},
					oncomplete: function(data) {
						$(document.body).find('#billing_postcode').val(data.zonecode);
						$(document.body).find('#billing_address_1').val(data.address);
						$(document.body).find('#billing_address_2').focus();
						$(document.body).find('#billing_city').val(data.sido);
					}
				}).open({
					left: (window.screen.width / 2) - (500 / 2),
					top: (window.screen.height / 2) - (600 / 2)
				});
			});
		}
	}

	wc_korea_postcode.init();
});