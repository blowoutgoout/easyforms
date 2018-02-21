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
    "jquery" , "underscore" , "backbone"
    , "models/component"
    , "collections/components"
    , "views/my-form-component"
], function(
    $, _, Backbone
    , ComponentModel
    , ComponentsCollection
    , MyFormComponentView
    ){
    return ComponentsCollection.extend({
        model: ComponentModel
        , initialize: function() {
            this.counter = {};
            this.bind("add", this.giveUniqueId);
        }
        , giveUniqueId: function(component){

            if(!component.get("fresh")) {
                return;
            }

            var componentName = component.get("name");
            var componentID = componentName + "_" + _.size(this.componentsByName(componentName));

            component.set("fresh", false);
            component.setField("id", componentID);

            // Same component detected
            if (_.size(this.componentsByFieldID(componentID)) > 0) {
                var randomNumber = Math.floor((Math.random() * 1000000) + 1); // Random number between 1 and 1000000
                component.setField("id", componentName + "_" + randomNumber);
            }
        }
        , componentsByFieldID: function (componentID) {
            return this.filter(function(component){
                return component.getField("id") === componentID;
            });
        }
        , componentsByName: function (componentName) {
            return this.filter(function(component){
                return component.get("name") === componentName;
            });
        }
        , pageBreaks: function(){
            return this.filter(function(component){
                return component.get("name") === "pagebreak"
            });
        }
        , containsFileType: function(){
            return !(typeof this.find(function(component){
                return component.get("name") === "file"
            }) === "undefined");
        }
        , containsRecaptcha: function(){
            return !(typeof this.find(function(component){
                return component.get("name") === "recaptcha"
            }) === "undefined");
        }
        , allComponents: {}
        , allCleanComponents: {}
        , renderAll: function(){
            // Remove old components for better performance
            _.map(this.allComponents, function(component, key){
                // Destroy this view
                component.undelegateEvents();
                component.$el.removeData().unbind();
                // Remove view from DOM
                component.remove();
                Backbone.View.prototype.remove.call(component);
            });
            var that = this;
            return this.map(function(component){
                that.allComponents[component.cid] = new MyFormComponentView({model: component});
                return that.allComponents[component.cid].render(true);
            })
        }
        , renderAllClean: function(){
            // Remove old components for better performance
            _.map(this.allCleanComponents, function(component, key){
                // Destroy this view
                component.undelegateEvents();
                component.$el.removeData().unbind();
                // Remove view from DOM
                component.remove();
                Backbone.View.prototype.remove.call(component);
            });
            var that = this;
            return this.map(function(component){
                that.allCleanComponents[component.cid] = new MyFormComponentView({model: component});
                return that.allCleanComponents[component.cid].render(false);
            });
        }
    });
});