jQuery( function( $ ) {
	'use strict';

	if ( typeof wc_korea_addons_params === 'undefined' ) {
		return false;
	}

	var wc_korea_addons = {
		/**
		 * Initialize
		 */
		init: function() {
			$('<a href="'+ wc_korea_addons_params.link +'" class="nav-tab wc-korea">'+ wc_korea_addons_params.title +'</a>').appendTo('nav.nav-tab-wrapper');

			if ( wc_korea_addons_params.is_active ) {
				$( 'nav.nav-tab-wrapper a' ).each( function() {
					$( this ).removeClass( 'nav-tab-active' );
				});
				$( 'a.wc-korea' ).addClass( 'nav-tab-active' );
				$( 'div.wrap.wc_addons_wrap p' ).first().remove();
				$( 'div.wrap.wc_addons_wrap br.clear' ).first().remove();

				$( 'div.wrap.wc_addons_wrap' ).append( $('div.wc-korea.container' ) );
			}
		}
	};

	wc_korea_addons.init();
} );
