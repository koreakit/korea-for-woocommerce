import jQuery from 'jquery';
import { __ } from '@wordpress/i18n';

jQuery(function ($) {
    'use strict';

    const $body = $(document.body);

    const onCountryChange = (event) => {
        const $target = $(event.target);
        const type = $target.attr('name').indexOf('billing') !== -1 ? 'billing' : 'shipping';
        const country = $(`#${type}_country`).val();
        const $postcode = $(`#${type}_postcode`);

        if (country !== 'KR') {
            $postcode.removeAttr('readonly onkeypress');
            $body.find(`#${type}-address-autocomplete`).remove();
            return;
        }

        $postcode.attr({
            readonly: 'readonly',
            onkeypress: 'return false;',
        });

        const autocompleteHTML = `<div id="${type}-address-autocomplete" class="${type}-address-autocomplete" style="display: none;"><img src="//t1.daumcdn.net/postcode/resource/images/close.png" class="address-autocomplete-close" style="cursor:pointer; position:absolute; right:0px; top:-1px; z-index:1" alt="${__('Collapse button', 'korea-for-woocommerce')}"></div>`;
        $postcode.closest('p').append(autocompleteHTML);
    };

    const onPostcodeClick = (event) => {
        const $target = $(event.target);
        const type = $target.attr('name').indexOf('billing') !== -1 ? 'billing' : 'shipping';
        const country = $(`#${type}_country`).val();

        if (country !== 'KR') {
            return;
        }

        const daumPostcode = new daum.Postcode({
            alwaysShowEngAddr: true,
            hideEngBtn: false,
            theme: _postcode.theme,
            oncomplete: (data) => {
                $body.find(`#${type}_postcode`).val(data.zonecode);
                $body.find(`#${type}_address_1`).val(data.address);
                $body.find(`#${type}_address_2`).focus();
                $body.find(`#${type}_city`).val(data.sido);
                $body.find(`#${type}-address-autocomplete`).hide();
            },
            width: '100%',
            height: '100%',
        }).embed(`${type}-address-autocomplete`);

        $body.find(`#${type}-address-autocomplete`).show();
    };

    // Event bindings
    $body.find('#billing_country, #shipping_country').on('change', onCountryChange).trigger('change');
    $body.find('#billing_postcode, #shipping_postcode').on('click', onPostcodeClick);
    $body.on('click', '.address-autocomplete-close', function () {
        $(this).parent().hide();
    });
});
