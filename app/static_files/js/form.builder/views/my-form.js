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
    "jquery", "underscore", "backbone", "prism"
    , "models/steps"
    , "views/my-form-steps"
    , "views/temp-widget"
    , "helper/pubsub"
    , "text!templates/app/renderform.html"
], function(
    $, _, Backbone, Prism
    , StepsModel
    , MyFormStepsView
    , TempWidgetView
    , PubSub
    , _renderForm
    ){
    return Backbone.View.extend({
        tagName: "fieldset"
        , pageHeader: false
        , initialize: function(options) {
            // Init view, one time
            this.options = options;
            // Event Listeners
            // Note: When a component is added, two events are triggered (add and change).
            // But, add is necessary to maintain order components when exchange positions
            this.collection.on("add", this.render, this);
            this.collection.on("remove", this.render, this);
            this.collection.on("change", this.render, this);
            PubSub.on("myComponentDrag", this.handleComponentDrag, this);
            PubSub.on("myComponentDelete", this.handleComponentDelete, this);
            PubSub.on("tempMove", this.handleTempMove, this);
            PubSub.on("tempDrop", this.handleTempDrop, this);
            PubSub.on("changeFormSettings", this.changeFormSettings, this); // Listener from views/tab.js
            PubSub.on("changeFormSteps", this.changeFormSteps, this); // Listener from views/my-form-steps.js
            // Add listeners to document
            $(document).keydown(function(e) {
                // If "ESC" key is pressed, close all popovers
                if (e.keyCode === 27) {
                    $(".popover").remove();
                }
            });
            this.$canvas = $(this.options.settings.canvas);
            this.myForm = $("#my-form");
            this.renderForm = _.template(_renderForm);
            this.hasPages = false;
            this.stepsModel = new StepsModel(this.options.settings.formSteps);
            this.pageHeader = new MyFormStepsView({
                pageBreaks: this.collection.pageBreaks(),
                model: this.stepsModel // Set Pagination Model
            });

            this.render();
        }

        , render: function() {
            var that = this;

            //Render Components Views
            _.each(this.collection.renderAll(), function(component){
                that.$el.append(component); // Add each component to dom
            });

            this.hasPages = ( this.collection.pageBreaks().length > 0 );

            if( this.hasPages ) {
                var steps = this.stepsModel.getField('steps');
                var stepsNumber = this.collection.pageBreaks().length + 1;

                if( stepsNumber > steps.length ) {
                    // If stepsNumber is >, add steps
                    _.times(stepsNumber - steps.length, function(){
                        steps.push('Untitled Step');
                    });
                } else if( stepsNumber < steps.length ) {
                    // If stepsNumber is <, remove steps
                    _.times(steps.length - stepsNumber, function(){
                        steps.pop();
                    });
                }

                // Update steps model
                this.stepsModel.setField('steps', steps);

                // Add page header view
                this.pageHeader.remove();
                this.pageHeader = new MyFormStepsView({
                    pageBreaks: this.collection.pageBreaks(),
                    model: this.stepsModel // Set Pagination Model
                });
            } else {
                // Update steps model with empty steps
                this.stepsModel.setField('steps', []);
            }

            // Source Code
            var formHtml = that.renderForm({ // Render form that shows source code
                cssClass: this.options.settings.layoutSelected,
                disabled: this.options.settings.disabledFieldset,
                multipart: this.collection.containsFileType(),
                pageHeader: this.hasPages ? this.pageHeader.$el.html() : '',
                text: _.map(this.collection.renderAllClean(), function(e){return e.html()}).join("\n")
            });
            $("#render").text(formHtml);
            Prism.highlightElement($('#render')[0]); // Handle syntax highlighting for code

            // Save Form HTML code in FormBuilder
            FormBuilder.html = formHtml;

            // Preview
            if( this.hasPages ) {
                this.pageHeader.$el.appendTo("#my-form"); // Add page header to #my-form
            } else {
                this.pageHeader.remove();
            }
            this.$el.appendTo("#my-form"); // Add fieldset to #my-form
            this.myForm.attr("class", this.options.settings.layoutSelected); // Update form css class
            this.myForm.find("fieldset").attr("disabled", this.options.settings.disabledFieldset); // Form disabled
            this.delegateEvents(); // Events
        }

        // This component will be above
        , getBottomAbove: function(eventY){

            // Make an array of all components that have been added to the form
            var allComponents = $(this.$el.find(".component"));

            // Find a component with specific condition
            var topComponent = _.find(allComponents, function(component) {
                // Vertical position + height > Vertical mouse position
                return ( ($(component).offset().top + $(component).height()) > eventY );
            });

            // If a component was found
            if (topComponent){
                return topComponent;
            } else {
                // Return last component
                return allComponents[allComponents.length - 1];
            }
        }

        // When a component is dragged
        , handleComponentDrag: function(mouseEvent, componentModel) {
            // Used by handleTempDrop
            options.lastIndex = $(this.getBottomAbove(mouseEvent.pageY)).index();

            $("body").append(new TempWidgetView({model: componentModel}).render());
            this.collection.remove(componentModel);
            PubSub.trigger("newTempPostRender", mouseEvent);
        }

        // When a component is deleted
        , handleComponentDelete: function(componentModel) {
            this.collection.remove(componentModel);
        }

        , handleTempMove: function(mouseEvent){

            // Smooth Page Scrolling
            // See: http://codereview.stackexchange.com/questions/13111/smooth-page-scrolling-in-javascript
            smoothScrollTo = (function () {
                var timer, start, factor;

                return function (target, duration) {
                    var offset = window.pageYOffset,
                        delta  = target - window.pageYOffset; // Y-offset difference
                    duration = duration || 100;               // default 100 microseconds animation
                    start = Date.now();                       // get start time
                    factor = 0;

                    if( timer ) {
                        clearInterval(timer); // stop any running animations
                    }

                    function step() {
                        var y;
                        factor = (Date.now() - start) / duration; // get interpolation factor
                        if( factor >= 1 ) {
                            clearInterval(timer); // stop animation
                            factor = 1;           // clip to max 1.0
                        }
                        y = factor * delta + offset;
                        window.scrollBy(0, y - window.pageYOffset);
                    }

                    timer = setInterval(step, 10);
                    return timer;
                };
            }());

            // Scroll the window when component is dragged over window bounds
            var windowHeight = $(window).height();
            var pixelsAboveTheFold = $(window).scrollTop();

            if (mouseEvent.clientY > windowHeight - 50) {
                // ScrollTo Down
                smoothScrollTo(pixelsAboveTheFold + 50);
            } else if ( mouseEvent.clientY < 50 ) {
                // ScrollTo Up
                smoothScrollTo(pixelsAboveTheFold - 50);
            }

            // Paint gray bg in active zone
            var target = $(".target");
            target.removeClass("target");
            if( mouseEvent.pageX >= this.$canvas.offset().left &&
                mouseEvent.pageX < (this.$canvas.width() + this.$canvas.offset().left) &&
                mouseEvent.pageY >= this.$canvas.offset().top &&
                mouseEvent.pageY < (this.$canvas.height() + this.$canvas.offset().top)){
                $(this.getBottomAbove(mouseEvent.pageY)).addClass("target");
            } else {
                target.removeClass("target");
            }
        }

        , handleTempDrop: function(mouseEvent, model, index){
            var target = $(".target");
            // Only one recaptcha component per form
            if( this.collection.containsRecaptcha() ) {
                if( model.get("name") === 'recaptcha') {
                    // console.log("Only one reCaptcha component can be added per form.");
                    target.removeClass("target");
                    return false;
                }
            }
            // Add component in correct place
            if( mouseEvent.pageX >= this.$canvas.offset().left &&
                mouseEvent.pageX < (this.$canvas.width() + this.$canvas.offset().left) &&
                mouseEvent.pageY >= this.$canvas.offset().top &&
                mouseEvent.pageY < (this.$canvas.height() + this.$canvas.offset().top)) {
                var i = target.index();
                target.removeClass("target");
                this.collection.add(model,{at: i+1});
            } else {
                target.removeClass("target");
                var form = $("#formId");
                // Confirmation is required to delete a field when a form is being updated
                if (options.endPoint.indexOf("form") > -1 &&
                    form.size() > 0 && form.val() !== '' &&
                    !confirm(polyglot.t('alert.confirmToDeleteField'))) {
                    this.collection.add(model,{at: options.lastIndex});
                }
            }
        }

        , changeFormSteps: function ( formStepsModel ){
            this.options.settings.formSteps = formStepsModel.toJSON();

            this.render();
        }

        , changeFormSettings: function (formSettings){
            this.options.settings.name = formSettings.name;
            this.options.settings.layoutSelected = formSettings.layoutSelected;
            this.options.settings.disabledFieldset = formSettings.disabledFieldset;

            this.render();
        }

    })
});