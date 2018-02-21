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
    'jquery', 'underscore', 'backbone'
], function($, _, Backbone) {
    return Backbone.Model.extend({
        initialize: function() {
            // Only to new components
            if(!this.has("fresh")) {
                this.set("fresh", true);
            }
        }
        , getValues: function(){
            return _.reduce(this.get("fields"), function(o, v, k){
                if (v["type"] == "select") {
                    o[k] = _.find(v["value"], function(o){return o.selected})["value"];
                } else {
                    o[k]  = v["value"];
                }
                return o;
            }, {});
        }
        , setField: function(name, value) {
            var fields = this.get("fields");
            fields[name]["value"] = value;
            this.set("fields", fields);
        }
        , getField: function(name) {
            var fields = this.get("fields");
            return fields[name]["value"];
        }
    });
});