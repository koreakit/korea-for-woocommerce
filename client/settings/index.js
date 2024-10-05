import jQuery from 'jquery';

jQuery(function ($) {
    'use strict';

    var $body = $(document.body),
        postcode = 'woocommerce_korea_postcode_yn',
        kakaochannel = 'woocommerce_korea_kakaochannel_yn',
        navertalktalk = 'woocommerce_korea_navertalktalk_yn';

    // Function to toggle visibility based on the checkbox state
    var toggleFeature = function (checkboxName, className) {
        $body.on('change', `input[name="${checkboxName}"]`, function () {
            $(`.show_if_${className}`).closest('tr').toggle($(this).is(':checked'));
        });

        // Trigger the change event to set the initial state
        $(`input[name="${checkboxName}"]`).trigger('change');
    };

    // Bind events for each feature
    toggleFeature(postcode, 'postcode');
    toggleFeature(kakaochannel, 'kakaochannel');
    toggleFeature(navertalktalk, 'navertalktalk');
});
