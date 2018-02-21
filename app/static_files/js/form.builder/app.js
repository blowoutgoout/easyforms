/*!
 * Bootstrap 2.3.1 Form Builder
 * Copyright (C) 2012 Adam Moore
 * Licensed under MIT (https://github.com/minikomi/Bootstrap-Form-Builder/blob/gh-pages/LICENSE)
 */

/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @description JavaScript Form Builder for Easy Forms
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

define([
    "jquery" , "underscore" , "backbone", "polyglot"
    , "collections/components" , "collections/my-form-components"
    , "views/tab" , "views/tab-content" , "views/my-form"
    , "text!data/components.json", "text!data/i18n.json"
    , "text!data/init-form.json"
    , "text!templates/app/render.html"
    , "helper/pubsub"
    , "jquery.cookie", "jquery.bsAlerts"
], function(
    $, _, Backbone, Polyglot
    , ComponentsCollection, MyFormComponentsCollection
    , TabView, TabContentView, MyFormView
    , components, i18n
    , initForm
    , renderTab
    , PubSub
    ){
    return {

        csrfParam: '',
        csrfToken: '',

        touchHandler: function (event)
        {
            var touches = event.changedTouches,
                first = touches[0],
                type = "";
            switch(event.type)
            {
                case "touchstart": type = "mousedown"; break;
                case "touchmove":  type="mousemove"; break;
                case "touchend":   type="mouseup"; break;
                default: return;
            }

            var simulatedEvent = document.createEvent("MouseEvent");
            simulatedEvent.initMouseEvent(type, true, true, window, 1,
                first.screenX, first.screenY,
                first.clientX, first.clientY, false,
                false, false, false, 0/*left*/, null);
            first.target.dispatchEvent(simulatedEvent);
        },

        initialize: function(){

            // Global variables
            window.FormBuilder = {
                data: "",
                html: ""
            };

            // Touch Screen Support
            document.addEventListener("touchstart", this.touchHandler, true);
            document.addEventListener("touchmove", this.touchHandler, true);
            document.addEventListener("touchend", this.touchHandler, true);
            document.addEventListener("touchcancel", this.touchHandler, true);

            // Default values
            window.polyglot = new Polyglot(JSON.parse(i18n));
            initForm = JSON.parse(initForm);
            components = JSON.parse(components);

            // CSRF configuration
            this.csrfParam = $('meta[name="csrf-param"]').attr('content');
            this.csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Local variables
            var that = this;

            /**
             * Server Requests
             */

            $.when( $.ajax( options.i18nUrl ), $.ajax( options.componentsUrl ), $.ajax( options.initPoint ) )
                .then(function (i18nData, componentsData, formData) {
                    // Replace default values
                    polyglot.replace( i18nData[0].phrases );
                    components = componentsData[0];
                    initForm = formData[0];
                    that.render();
                }, function (){});
        },

        render: function(){

            // Local variables
            var that = this;

            /**
             * Tabs
             */

            new TabView({
                id: "fields"
                , title: polyglot.t('tab.fields')
                , collection: new ComponentsCollection(components)
            });
            new TabView({
                id: "settings"
                , title: polyglot.t('tab.settings')
                , isForm: true
                , settings: initForm.settings
            });
            new TabView({
                id: "code"
                , title: polyglot.t('tab.code')
                , content: renderTab
            });

            // Make the first tab active
            var formTabs = $("#formtabs");
            formTabs.find("li").first().addClass("active");
            formTabs.find("li .dropdown-menu li").first().addClass("active");
            $("#widgets").find(".tab-pane").first().addClass("active");

            /**
             * Hide Alert
             */

            // Grab your button (based on your posted html)
            $('.close').click(function( e ){
                // Do not perform default action when button is clicked
                e.preventDefault();
                // If you just want the cookie for a session don't provide an expires
                // Set the path as root, so the cookie will be valid across the whole site
                $.cookie('alert-box', 'closed');
            });

            // Check if alert has been closed
            if( $.cookie('alert-box') !== 'closed' ){
                $('.alert').addClass("in").show();
            } else {
                $('.alert').removeClass("in").hide();
            }

            /**
             * Logout link
             */

            $("ul").find("[data-method='post']").click(function(event){
                event.preventDefault();
                var _csrfToken = {};
                _csrfToken[that.csrfParam] = that.csrfToken;
                $.ajax({
                    method: "POST",
                    url: $(this).attr('href'),
                    dataType: 'json',
                    data: _csrfToken
                }).always(function () {
                    location.reload();
                });
            });

            /**
             * Build Init Form
             */

            // New component collection
            var componentsCollection = new MyFormComponentsCollection();
            // Add each field to the collection (Component Model)
            componentsCollection.add(initForm.initForm);
            // Render "My Form View" with the collection of components.
            var formView = new MyFormView({
                settings: initForm.settings,
                collection: componentsCollection
            });

            /**
             * Save Form
             */

            PubSub.on("tempDrop", function () {
                // Prevent that user lost his data
                $(window).off('beforeunload').on('beforeunload', function(){
                    return polyglot.t('alert.unsavedChanges');
                });
            });

            $('#actions').find('.saveForm').click(function( e ){
                // Do not perform default action when button is clicked
                e.preventDefault();

                // Replace default values
                var target = $(e.target);
                options.endPoint = typeof target.data('endpoint') !== 'undefined' &&
                    target.data('endpoint') !== '' ? target.data('endpoint') : options.endPoint;
                options.afterSave = typeof target.data('aftersave') !== 'undefined' &&
                    target.data('aftersave') !== '' ? target.data('aftersave') : options.afterSave;
                options.redirectTo = typeof target.data('redirectto') !== 'undefined' &&
                    target.data('redirectto') !== '' ? target.data('redirectto') : options.redirectTo;

                // Remove prevention
                $(window).off('beforeunload');

                // Save data in FormBuilder
                FormBuilder.data = {
                    settings: formView.options.settings,
                    initForm: componentsCollection.toJSON(),
                    height: $("#my-form").find("fieldset").height()
                };

                // Prepare FormBuilder to POST as JSON
                var data = {
                    FormBuilder: JSON.stringify(FormBuilder)
                };
                data[that.csrfParam] = that.csrfToken; // Add csrf token

                // Send Form Data
                $.ajax({
                    method: "POST",
                    url: options.endPoint, // From external file configuration
                    dataType: 'json',
                    data: data
                }).done(function( data ) {

                    if( data.success && data.id > 0) {

                        // Redirect to another page
                        if(options.afterSave == 'redirect' && typeof options.redirectTo !== 'undefined') {

                            window.location.href = options.redirectTo;

                        } else { // Show a success message

                            // Set id in hidden field
                            $("#formId").val(data.id);

                            // If the action is create
                            if(data.action == "create") {
                                // Set form link
                                var toUpdate = $('#toUpdate');
                                var toUpdateUrl = toUpdate.attr("href");
                                var prefix = ( toUpdateUrl.indexOf('?') >= 0 ? '&' : '?' );
                                var toUpdateNewUrl = toUpdateUrl + prefix + "id=" + data.id;
                                toUpdate.attr("href", toUpdateNewUrl);
                                // Set form config link
                                var toSettings = $('#toSettings');
                                var toSettingsUrl = toSettings.attr("href");
                                    prefix = ( toSettingsUrl.indexOf('?') >= 0 ? '&' : '?' );
                                var toSettingsNewUrl = toSettingsUrl + prefix + "id=" + data.id;
                                toSettings.attr("href", toSettingsNewUrl);
                            }

                            // Show success message
                            $('#saved').modal('show');
                        }
                    } else {

                        // Show error message
                        $(document).trigger("add-alerts", [
                            {
                                'message': "<strong>" + polyglot.t('alert.warning') + "</strong> " + data.message,
                                'priority': 'warning'
                            }
                        ]);

                    }
                }).fail(function(msg){

                    // Show error message
                    $(document).trigger("add-alerts", [
                        {
                            'message': "<strong>" + polyglot.t('alert.warning') + "</strong> " + polyglot.t('alert.errorSavingData'),
                            'priority': 'warning'
                        }
                    ]);

                }).always(function(){
                });

            });

        }
    }
});