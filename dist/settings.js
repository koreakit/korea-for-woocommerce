(()=>{"use strict";var e={n:o=>{var n=o&&o.__esModule?()=>o.default:()=>o;return e.d(n,{a:n}),n},d:(o,n)=>{for(var t in n)e.o(n,t)&&!e.o(o,t)&&Object.defineProperty(o,t,{enumerable:!0,get:n[t]})},o:(e,o)=>Object.prototype.hasOwnProperty.call(e,o)};const o=window.jQuery;e.n(o)()((function(e){var o=e(document.body),n=function(n,t){o.on("change",`input[name="${n}"]`,(function(){e(`.show_if_${t}`).closest("tr").toggle(e(this).is(":checked"))})),e(`input[name="${n}"]`).trigger("change")};n("woocommerce_korea_postcode_yn","postcode"),n("woocommerce_korea_kakaochannel_yn","kakaochannel"),n("woocommerce_korea_navertalktalk_yn","navertalktalk")}))})();