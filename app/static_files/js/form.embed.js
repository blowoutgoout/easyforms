/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

jQuery(document).ready(function(){

    window.formEl = $(options.name); // Get the form element
    var progressEl = $('#progress');
    var barEl = $('#bar');
    var percentEl = $('#percent');
    var fieldset = $("fieldset");
    var current_fs, next_fs, previous_fs; // Used in form multi steps

    formEl.attr("role", "form"); // Add bootstrap role to form

    // Add csrf token to form
    $('<input>').attr({
        type: 'hidden',
        id: '_csrf',
        name: '_csrf',
        value: options._csrf
    }).appendTo(formEl);

    // Enable / Disable browser autocomplete
    if( options.autocomplete ){
        formEl.attr("autocomplete", "on");
    } else {
        formEl.attr("autocomplete", "off");
    }

    // Enable / Disable browser validation
    if( options.novalidate ){
        formEl.attr("novalidate", "novalidate");
    } else {
        if (formEl.attr("novalidate") !== "novalidate") {
            formEl.removeAttr("novalidate");
        }
    }

    // Enable Save & Resume Later
    if ( options.resume ) {
        formEl.resume({
            key: 'form_app_' + options.id
        });
    }

    // Fire resize event only when resizing is finished
    var resizeID;
    jQuery(window).resize(function() {
        clearTimeout(resizeID);
        resizeID = setTimeout(doneResizing, 50); // 500 for better performance
    });

    function doneResizing(){
        // Send the new height to the parent window
        Utils.postMessage({
            height: $("body").outerHeight(true)
        });
    }

    // After the form page loaded
    jQuery(window).load(function(){

        // Send the new height to the parent window
        Utils.postMessage({
            height: $("body").outerHeight(true)
        });

        // Preview mode
        if (options.mode === "preview") {
            return false;
        }

        // Pre-fill default values
        if (options.defaultValues) {
            options.defaultValues = JSON.parse(options.defaultValues);
            $.each(options.defaultValues, function(field, value) {
                var fieldType = field.split("_", 1);
                if (fieldType == "checkbox" || fieldType == "radio"){
                    $("#" + field).prop('checked', value);
                } else {
                    $("#" + field).val(value);
                }
            });
        }

        // Trigger event
        formEl.trigger( "view" );
    });

    /**
     * One Change handler
     * @type {boolean}
     */
    var beganFilling = false;
    var startTime = 0;
    formEl.find(':input').each(function() {
        $( this ).one( "change", function() {
            if ( beganFilling === false ) {

                // Preview mode
                if( options.mode === "preview" ){
                    return false;
                }

                // Start timing
                startTime = (new Date()).getTime();

                // Trigger event
                formEl.trigger( "fill" );
            }
            beganFilling = true;
        });
    });

    /**
     * Pagination handlers
     */
    window.nextStep = function(e){
        e.preventDefault();
        var that = this;
        var hasError = false; // Flag to prevent go to next page
        next_fs = $(e.currentTarget).parents("fieldset").next();
        current_fs = $(that).parents("fieldset");
        if (options.skips.length > 0) {
            for(var i = 0; i < options.skips.length; i++) {
                if (fieldset.index(current_fs) < options.skips[i].to) {
                    var tmp_next_fs = fieldset.eq(options.skips[i].to);
                    if (fieldset.index(current_fs) < fieldset.index(tmp_next_fs)) {
                        if (options.skips[i].from == null || options.skips[i].from == fieldset.index(current_fs)) {
                            options.skips[i].from = fieldset.index(current_fs);
                            next_fs = tmp_next_fs;
                        }
                        break;
                    }
                }
            }
        }
        // Check if next step exists
        if (next_fs.is('fieldset')) {
            // Perform validations
            $.ajax({
                url: options.validationUrl,
                type: 'post',
                data: formEl.serialize(),
                complete: function (jqXHR, textStatus) {
                },
                beforeSend: function (jqXHR, settings) {
                    var requiredFile = current_fs.find('input:file[required]');
                    if (requiredFile.size() > 0) {
                        $.each(requiredFile, function() {
                            if ($(this).val()) {
                                settings.data = settings.data + '&' + $(this).attr('name') + '=1';
                            }
                        });
                    }
                    settings.data = settings.data + '&current_page=' + fieldset.index(current_fs);
                },
                success: function (errors) {
                    if (errors !== null && typeof errors === 'object') {

                        // Clean previous errors and messages of te current field set
                        current_fs.find(".error-block").remove();
                        current_fs.find(".form-group").removeClass('has-error');

                        // Show validation errors
                        $.each(errors, function (key, error) {
                            var fieldGroup = current_fs.find("#" + key).parent(".form-group");
                            if(fieldGroup.size() > 0) {
                                hasError = true;
                                fieldGroup.addClass("has-error");
                                // add the actual error message under the input
                                var errorBlock = $("<div>", {"class": "help-block error-block", "html": error});
                                fieldGroup.append(errorBlock);
                            }
                        });

                    }

                    if( !hasError ) {
                        current_fs.hide();
                        var steps = $(".steps");
                        if( steps.size() > 0 ){
                            // De-activate current step
                            steps.find(".step").eq(fieldset.index(current_fs)).removeClass("current");
                            // Add success to current and previous steps
                            steps.find(".step").eq(fieldset.index(next_fs)).prevAll().addClass("success");
                            // Activate next step
                            steps.find(".step").eq(fieldset.index(next_fs)).addClass("current");
                        } else {
                            // Find progress bar elements
                            var progress = $(".progress").first();
                            var progressBar = progress.find(".progress-bar");
                            var percent = progressBar.find(".percent");
                            var title = progressBar.find(".title");
                            // Update title
                            var titles = progressBar.data("titles");
                            if (typeof titles !== "undefined") {
                                titles = titles.split(",");
                                var next_title = titles[fieldset.index(next_fs)];
                                title.html(next_title);
                            }
                            // Update percent
                            var new_percent = Math.round(100 / fieldset.size() * fieldset.index(next_fs)) + "%";
                            percent.text( new_percent);
                            // Update bar
                            progressBar.width(new_percent);
                        }
                        // Show next fieldset
                        next_fs.show();
                        // Save previous_fs in cache
                        previous_fs = current_fs;
                    }

                    // Trigger event
                    formEl.trigger("nextStep");
                    // Send new height to parent window
                    Utils.postMessage({
                        height: $("body").outerHeight(true)
                    });
                    // Scroll to Top of the form container
                    Utils.postMessage({
                        scrollToTop: 'container'
                    });
                },
                error: function () {
                    // Show error message
                    Utils.showMessage( '#messages',
                        options.i18n.unexpectedError,
                        'danger' );
                    // Hide form
                    formEl.hide();
                    // Send new height to parent window
                    Utils.postMessage({
                        height: $("body").outerHeight(true)
                    });
                    // Scroll to Top in the parent window
                    Utils.postMessage({
                        scrollToTop: 'container'
                    });
                }
            });
        }
    };

    window.previousStep = function(e){
        e.preventDefault();
        // Check if previous step exists
        if (previous_fs.is('fieldset')) {
            current_fs = $(this).parents("fieldset");
            for(var i = 0; i < options.skips.length; i++) {
                if (fieldset.index(current_fs) == options.skips[i].to) {
                    previous_fs = fieldset.eq(options.skips[i].from);
                    break;
                }
            }
            var steps = $(".steps");
            if( steps.size() > 0 ) {
                // Remove success to all steps
                steps.find(".step").removeClass("success");
                // De-activate current step
                steps.find(".step").eq(fieldset.index(current_fs)).removeClass("current");
                // Add success to previous steps
                steps.find(".step").eq(fieldset.index(previous_fs)).prevAll().addClass("success");
                // Activate previous step
                steps.find(".step").eq(fieldset.index(previous_fs)).addClass("current");
            } else {
                // Find progress bar elements
                var progress = $(".progress").first();
                var progressBar = progress.find(".progress-bar");
                var percent = progressBar.find(".percent");
                var title = progressBar.find(".title");
                // Update title
                var titles = progressBar.data("titles");
                if (typeof titles !== "undefined") {
                    titles = titles.split(",");
                    var previous_title = titles[fieldset.index(previous_fs)];
                    title.html(previous_title);
                }
                // Update percent
                var new_percent = Math.round(100 / fieldset.size() * fieldset.index(previous_fs)) + "%";
                percent.text( new_percent);
                // Update bar
                progressBar.width(new_percent);
            }
            // Show previous fieldset
            current_fs.hide();
            previous_fs.show();
            previous_fs = previous_fs.prev();
            // Trigger event
            formEl.trigger("previousStep");
            // Send new height to parent window
            Utils.postMessage({
                height: $("body").outerHeight(true)
            });
            // Scroll to Top in the parent window
            Utils.postMessage({
                scrollToTop: 'container'
            });
        }
    };

    $('input').on("keypress", function(e) {
        // Enter pressed
        if (e.keyCode == 13) {
            // Check if is a multi-step form
            var next = $(e.currentTarget).parents('fieldset').find(".next");
            // var next = $(".next").filter(":visible");
            if (next.size() > 0) {
                e.preventDefault();
                next.click();
                return false;
            }
        }
    });

    $(".next").click(nextStep);

    $(".previous").click(previousStep);

    /**
     * Submit form
     */
    formEl.ajaxForm({
        url: options.actionUrl,
        type: "post",
        beforeSubmit: function(formData, jqForm, opts) {

            // Preview mode
            if( options.mode === "preview" ){
                return false;
            }

            // Show progress bar
            if( jqForm.find(':file').val() ) {
                progressEl.show();
                var percentVal = '0%';
                barEl.css("width", percentVal);
                percentEl.html(percentVal + " " + options.i18n.complete);

                // Send the new height to the parent window
                Utils.postMessage({
                    height: $("body").outerHeight(true)
                });
            }

            formEl.find(':submit').attr("disabled", true); // Disable submit buttons
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            barEl.css("width", percentVal);
            percentEl.html(percentVal + " " + options.i18n.complete);
        },
        success: function(data) {
            if( data.success ) {
                // Reset for to init values
                cleanFormUI();
                successHandler(data);
            } else {
                errorHandler(data);
            }
        },
        error:function(jqXHR, textStatus, errorThrown ) {

            // Show error message
            Utils.showMessage( '#messages',
                options.i18n.unexpectedError,
                'danger' );
            // Hide the form
            formEl.hide();
            // Send the new height to the parent window
            Utils.postMessage({
                height: $("body").outerHeight(true)
            });
            // Scroll to Top in the parent window
            Utils.postMessage({
                scrollToTop: 'container'
            });

        }
    });

    /**
     * Clean ui from error messages
     */
    function removeErrorMessages() {
        // Remove all error blocks
        $(".error-block").remove();
        // Remove css class
        $(".form-group").removeClass('has-error');
    }

    /**
     * Clean form UI
     */
    function cleanFormUI() {
        removeErrorMessages();
        // Enable submit buttons
        formEl.find(':submit').attr("disabled", false);
        // Reset form fields
        formEl.resetForm();
        // Hide progress bar
        progressEl.hide();
    }

    /**
     * Execute when the form was successfully sent
     */
    function successHandler(data) {

        // Change Flag
        options.submitted = true;

        // Completion time
        var endTime = (new Date()).getTime();
        var completionTime = endTime - startTime; // In miliseconds

        // Trigger event
        formEl.trigger( "success", [ data, completionTime ] );

        // Form Steps
        if( fieldset.size() > 1 ) {
            var steps = $(".steps");
            if( steps.size() > 0 ) {
                // Add success to all steps
                steps.find(".step").addClass("success");
            } else {
                // Find progress bar elements
                var progress = $(".progress").first();
                var progressBar = progress.find(".progress-bar");
                var percent = progressBar.find(".percent");
                var title = progressBar.find(".title");
                // Update title
                title.html("Complete");
                // Update percent
                percent.text("100%");
                // Update bar
                progressBar.width("100%");
            }
        }

        // Performs an action according to the indications
        if (typeof data.addon !== 'undefined') {
            if (typeof data.addon.redirectTo !== 'undefined') {
                // Redirect to URL
                Utils.postMessage({
                    url: data.addon.redirectTo
                });
            }
        }

        // Performs an action according to type of confirmation
        if ( options.confirmationType === options.redirectToUrl ) {

            // Redirect to URL
            Utils.postMessage({
                url: typeof data.confirmationUrl === 'undefined' ? options.confirmationUrl : data.confirmationUrl
            });

        } else {

            // Show confirmation message
            var confirmationMessage = options.confirmationMessage ? options.confirmationMessage : data.message;

            // Hide old messages
            Utils.hideMessage('#messages');

            // Show success message
            Utils.showMessage( '#messages',
                confirmationMessage,
                'success' );

            // Hide the form according to type of confirmation
            if ( options.confirmationType === options.showOnlyMessage ) {
                // Hide the form
                formEl.hide();
            }

            // Send the new height to the parent window
            Utils.postMessage({
                height: $("body").outerHeight(true)
            });

            // Scroll to Top in the parent window
            Utils.postMessage({
                scrollToTop: 'container'
            });
        }
    }


    /**
     * Execute each time the form has errors
     *
     * @param data
     */
    function errorHandler(data) {

        // Hide old messages
        Utils.hideMessage('#messages');

        // Show error message
        if( typeof data.message !== 'undefined') {
            Utils.showMessage('#messages',
                data.message,
                'danger');
        }

        // Scroll to Top in the parent window
        Utils.postMessage({
            scrollToTop: 'container'
        });

        // Hide old validation errors
        removeErrorMessages();

        // Show validation errors
        if( typeof data.errors !== 'undefined' && data.errors.length > 0 ) {
            var errors = data.errors;
            for (k = 0; k < errors.length; k++) {
                var fieldGroup = $("#" + errors[k].field).parent(".form-group");
                fieldGroup.addClass("has-error");
                for (i = 0; i < errors[k].messages.length; i++) {
                    // add the actual error message under the input
                    var errorBlock = $("<div>", {"class": "help-block error-block", "html": errors[k].messages[i]});
                    fieldGroup.append(errorBlock);
                }
            }
        }

        // Enable submit buttons
        formEl.find(":submit").removeAttr("disabled");

        // Send the new height to the parent window
        Utils.postMessage({
            height: $("body").outerHeight(true)
        });

        // Trigger event
        formEl.trigger("error", data);

    }

    /**
     * Form Tracker
     */

    if( options.analytics ) {
        // Init
        (function(p,l,o,w,i,n,g){if(!p[i]){p.FA=p.FA||[];
            p.FA.push(i);p[i]=function(){(p[i].q=p[i].q||[]).push(arguments)
            };p[i].q=p[i].q||[];n=l.createElement(o);g=l.getElementsByTagName(o)[0];n.async=1;
            n.src=w;g.parentNode.insertBefore(n,g)}}(window,document,"script",options.tracker,"tracker"));

        window.tracker('newTracker', 't'+options.id, options.app, {
            encodeBase64: false,
            appId: options.id
        });

        // Track form page view
        formEl.on('view', function(event){
            window.tracker('setCustomUrl', decodeURIComponent(Utils.getUrlVars()["url"]));  // Override the page URL
            window.tracker('setReferrerUrl', decodeURIComponent(Utils.getUrlVars()["referrer"]));  // Override the referrer URL
            window.tracker('trackPageView', decodeURIComponent(Utils.getUrlVars()["title"]) ); // Track the page view with custom title
        });

        // Track fill
        formEl.on('fill', function(event){
            window.tracker('trackStructEvent', 'form', 'fill', 'start', null, null);
        });

        // Track validation error
        formEl.on('error', function(event){
            window.tracker('trackStructEvent', 'form', 'error', null, null, null);
        });

        // Track submit with success
        formEl.on('success', function(event, submission, completionTime){
            window.tracker('trackStructEvent', 'form', 'submit', submission.id, 'time', completionTime);
        });

    }

});
