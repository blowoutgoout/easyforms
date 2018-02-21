/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.3.7
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */
$( document ).ready(function() {

    /**
     * Adds a flag dropdown to any 'tel' input and displays a relevant placeholder.
     * The user types their national number and the plugin send the full standardized international number
     *
     * @link http://jackocnr.com/intl-tel-input.html
     */

    $.when(
        $('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/9.0.7/css/intlTelInput.css" type="text/css" />'),
        $.getScript("//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/9.0.7/js/intlTelInput.min.js"),
        $.getScript("//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/9.0.7/js/utils.js"),
        $.Deferred(function( deferred ){
            $( deferred.resolve );
        })
    ).done(function(){
        $('input[type=tel]').each(function () {
            var that = this;
            var altID = this.id + '_alt';
            $(this).after(
                $(this).clone().attr('id', altID).attr('name', altID)
            ).hide();
            $("#" + altID).change(function () {
                $(that).val($(this).intlTelInput("getNumber"));
            }).intlTelInput();
        });
        // CSS Fixes
        $('body').css('padding-bottom', '140px'); // Set up min bottom padding for show the select list
        $('.intl-tel-input').css('display', 'inherit');
    });

});
