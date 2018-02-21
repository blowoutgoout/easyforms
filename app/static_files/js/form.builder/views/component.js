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
    "jquery", "underscore", "backbone"
    , "text!templates/app/recaptcha.html"
    , "text!templates/popover/popover-main.html"
    , "text!templates/popover/popover-input.html"
    , "text!templates/popover/popover-select.html"
    , "text!templates/popover/popover-textarea.html"
    , "text!templates/popover/popover-textarea-split.html"
    , "text!templates/popover/popover-checkbox.html"
    , "templates/component/templates"
    , "bootstrap"
    , "popover-extra-placements"
], function(
    $, _, Backbone
    , _reCAPTCHA
    , _PopoverMain
    , _PopoverInput
    , _PopoverSelect
    , _PopoverTextArea
    , _PopoverTextAreaSplit
    , _PopoverCheckbox
    , _componentTemplates
    ){
    return Backbone.View.extend({
        tagName: "div"
        , className: "component"
        , initialize: function(){
            this.template = _.template(_componentTemplates[this.model.get("name")]);
            this.recaptchaTemplate = _.template(_reCAPTCHA);
            this.popoverTemplates = {
                "input" : _.template(_PopoverInput)
                , "select" : _.template(_PopoverSelect)
                , "textarea" : _.template(_PopoverTextArea)
                , "textarea-split" : _.template(_PopoverTextAreaSplit)
                , "checkbox" : _.template(_PopoverCheckbox)
            }
        }
        , render: function(withAttributes) {
            var that = this;

            // Split fields in basic and advanced
            var basicFields = {};
            var advancedFields = {};
            _.map( that.model.get("fields"), function( field, key ) {
                if ( field.advanced === true ) {
                    advancedFields[key] = field;
                } else {
                    basicFields[key] = field;
                }
            });

            // HTML of the basic and advanced fields
            var basicFieldsHtml =  _.reduce(basicFields, function(str, v, k){
                v["name"] = k;
                return str + that.popoverTemplates[v["type"]](v);
            }, "");
            var advancedFieldsHtml =  _.reduce(advancedFields, function(str, v, k){
                v["name"] = k;
                return str + that.popoverTemplates[v["type"]](v);
            }, "");

            // Get the HTML of the popover
            var content = _.template(_PopoverMain)({
                "title": polyglot.t(that.model.get("title")), // i18n
                "basicFields" : basicFieldsHtml,
                "advancedFields" : advancedFieldsHtml
            });

            // Return the Component HTML
            if (withAttributes) { // For builder preview
                return this.$el.html(
                        that.template({field: that.model.getValues()})
                    ).attr({
                        "class"             : "component " + that.model.get("name")
                        , "data-content"    : content
                        , "data-title"      : polyglot.t(that.model.get("title")) // i18n
                        , "data-trigger"    : "manual"
                        , "data-placement"  : "rightTop"
                        , "data-html"       : true
                    });
            } else { // For source code
                // If is a reCAPTCHA component return the html required for Google reCAPTCHA
                // See https://developers.google.com/recaptcha/docs/display
                if (that.model.get("name") === "recaptcha") {
                    var values = that.model.getValues();
                    values.siteKey = options.reCaptchaSiteKey;
                    return this.$el.html(
                        that.recaptchaTemplate(values)
                    );
                }
                // If not, parse the component with the component data
                return this.$el.html(
                    that.template({field: that.model.getValues()})
                )
            }
        }
    });
});