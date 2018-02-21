/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

// Set end point
var endPoint = options.endPoint;
// Set form id
var formID = options.formID;
// Set iframe id
var iID = options.iframe;
// Set iframe height
var iH = options.iHeight;
// Boolean iframe is in the DOM
var iExists = false;

/**
 * When a no one theme is selected
 *
 * @param e
 * @returns {boolean}
 */
var previewUnselected = function (e) {
    e.preventDefault();
    // Hide container
    $("#preview-container").hide();
    // Remove iframe
    $('#'+iID).remove();
    return iExists = false;
};

/**
 * When a theme is selected
 *
 * @param e
 * @returns {boolean}
 */
var previewSelected = function(e) {
    e.preventDefault();
    // Show container
    $("#preview-container").show();

    // Load iframe
    var themeID = $(e.currentTarget).val();
    var prefix = ( endPoint.indexOf('?') >= 0 ? '&' : '?' );
    var src = endPoint + prefix + $.param({
            id: formID,
            theme_id: themeID
        }, true );
    if( iExists === true ) {
        // If iframe exists, only change its src
        $('#'+iID).attr("src", src);
    } else {
        // Create iframe
        var i = $('<iframe></iframe>').attr({
            src: src,
            id: iID,
            frameborder: 0,
            width: '100%',
            height: iH
        });
        // Add iframe to div preview
        $("#preview").html(i);
        // Flag to true
        return iExists = true;
    }
};

/**
 * Resize iframe
 */
$("#resizeFull").click(function(e) {
    e.preventDefault();
    if(iExists) {
        // To expand
        var iEl = $("#"+iID);
        iEl.height( iEl.contents().find("html").height() );
        $(".toogleButton").toggle();
    }
});
$("#resizeSmall").click(function(e) {
    e.preventDefault();
    if(iExists) {
        // To contract
        $("#"+iID).height( iH );
        $(".toogleButton").toggle();
    }
});

$( document ).ready(function() {

    /**
     * Show Wysiwyg editor
     */
    var CustomHelpButton = function (context) {
        var ui = $.summernote.ui;

        // create button
        var button = ui.button({
            contents: '<i class="note-icon-question"/>',
            tooltip: 'Help',
            click: function () {
                var ui = $.summernote.ui;
                var $container = $(document.body);
                var contextOptions = context.options;
                var lang = contextOptions.langInfo;
                var isMac = navigator.platform.toUpperCase().indexOf('MAC')>=0;

                this.createShortCutList = function () {
                    var keyMap = contextOptions.keyMap[isMac ? 'mac' : 'pc'];
                    return Object.keys(keyMap).map(function (key) {
                        var command = keyMap[key];
                        var $row = $('<div><div class="help-list-item"/></div>');
                        $row.append($('<label><kbd>' + key + '</kdb></label>').css({
                            'width': 180,
                            'margin-right': 10
                        })).append($('<span/>').html(context.memo('help.' + command) || command));
                        return $row.html();
                    }).join('');
                };

                this.$dialog = ui.dialog({
                    title: lang.options.help,
                    fade: contextOptions.dialogsFade,
                    body: this.createShortCutList(),
                    footer: ' ',
                    callback: function ($node) {
                        $node.find('.modal-body').css({
                            'max-height': 300,
                            'overflow-y': 'scroll'
                        });
                    }
                }).render().appendTo($container);

                ui.showDialog(this.$dialog);
            }
        });

        return button.render();
    };
    $('#formconfirmation-mail_message, #formemail-message').summernote({
        height: 300,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['style']],
            ['do', ['undo', 'redo']],
            ['font_style', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['font', ['color']],
            ['para', ['ol', 'ul', 'paragraph']],
            ['table', ['table']],
            ['insert', ['picture', 'link', 'video', 'hr']],
            ['misc', ['codeview', 'customHelp']]
        ],
        buttons: {
            customHelp: CustomHelpButton
        }
    });

    /**
     * Show/Hide Forms fields Events Handlers
     */

    // Handlers
    toggleSchedule = function (e) {
        if(e.val() === "0") {
            $('.field-form-schedule_start_date').hide();
            $('.field-form-schedule_end_date').hide();
        } else {
            $('.field-form-schedule_start_date').show();
            $('.field-form-schedule_end_date').show();
        }
    };
    togglePassword = function (e) {
        if($("#form-use_password").is(":checked") === false) {
            $('.field-form-password').hide();
        } else {
            $('.field-form-password').show();
        }
    };
    toggleUrls = function (e) {
        if($("#form-authorized_urls").is(":checked") === false) {
            $('.field-form-urls').hide();
        } else {
            $('.field-form-urls').show();
        }
    };
    toggleTotalLimit = function (e) {
        if(e.val() === "0") {
            $('.field-form-total_limit_number').hide();
            $('.field-form-total_limit_period').hide();
        } else {
            $('.field-form-total_limit_number').show();
            $('.field-form-total_limit_period').show();
        }
    };
    toggleIPLimit = function (e) {
        if(e.val() === "0") {
            $('.field-form-ip_limit_number').hide();
            $('.field-form-ip_limit_period').hide();
        } else {
            $('.field-form-ip_limit_number').show();
            $('.field-form-ip_limit_period').show();
        }
    };
    toggleFormConfirmationFields = function (e) {
        if(e.val() === "0" || e.val() === "1") {
            $('.field-formconfirmation-message').show();
            $('.field-formconfirmation-url').hide();
        } else if (e.val() === "2") {
            $('.field-formconfirmation-message').hide();
            $('.field-formconfirmation-url').show();
        }
    };
    toggleFormConfirmationEmailFields = function (e) {
        if(e.val() === "0") {
            $('.field-formconfirmation-mail_to').hide();
            $('.field-formconfirmation-mail_from').hide();
            $('.field-formconfirmation-mail_from_name').hide();
            $('.field-formconfirmation-mail_subject').hide();
            $('.field-formconfirmation-mail_message').hide();
            $('.field-formconfirmation-mail_receipt_copy').hide();
        } else if (e.val() === "1") {
            $('.field-formconfirmation-mail_to').show();
            $('.field-formconfirmation-mail_from').show();
            $('.field-formconfirmation-mail_from_name').show();
            $('.field-formconfirmation-mail_subject').show();
            $('.field-formconfirmation-mail_message').show();
            $('.field-formconfirmation-mail_receipt_copy').show();
        }
    };
    toggleFormEmailFields = function (e) {
        if(e.val() === "2") {
            $('.field-formemail-message').show();
        } else {
            $('.field-formemail-message').hide();
        }
    };

    // Events
    $('#form-schedule').find( ".btn" ).on('click', function(e) {
        toggleSchedule($(this).children());
    });
    $('#form-total_limit').find( ".btn" ).on('click', function(e) {
        toggleTotalLimit($(this).children());
    });
    $('#form-ip_limit').find( ".btn" ).on('click', function(e) {
        toggleIPLimit($(this).children());
    });
    $('#formconfirmation-type').find( ".btn" ).on('click', function(e) {
        toggleFormConfirmationFields($(this).children());
    });
    $('#formconfirmation-send_email').find( ".btn" ).on('click', function(e) {
        toggleFormConfirmationEmailFields($(this).children());
    });
    $('#formemail-type').find( ".btn" ).on('click', function(e) {
        toggleFormEmailFields($(this).children());
    });

    // Init
    toggleSchedule($('[name$="Form[schedule]"]:checked'));
    togglePassword();
    toggleUrls();
    toggleTotalLimit($('[name$="Form[total_limit]"]:checked'));
    toggleIPLimit($('[name$="Form[ip_limit]"]:checked'));
    toggleFormConfirmationFields($('[name$="FormConfirmation[type]"]:checked'));
    toggleFormConfirmationEmailFields($('[name$="FormConfirmation[send_email]"]:checked'));
    toggleFormEmailFields($('[name$="FormEmail[type]"]:checked'));

});
