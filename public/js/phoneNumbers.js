"use strict";
jQuery(function ($) {
    $('input[type="tel"]').intlTelInput({
        defaultCountry: 'us',
        onlyCountries: ['us'],
    });
});
