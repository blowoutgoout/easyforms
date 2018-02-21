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

], function($, _, Backbone){
    return Backbone.View.extend({
        tagName: "div"
        , className: "tab-pane"
        , initialize: function(options) {
            this.options = options;
            this.id = this.options.id;
            this.render();
        }
        , render: function(){
            // Render Widgets Views
            var that = this;
            if (that.collection !== undefined) {
                _.each(this.collection.renderAllAsWidgets(), function(widget){
                    that.$el.append(widget);
                });
            } else if (that.options.content){
                that.$el.append(that.options.content);
            }
            // Render tab
            this.$el.attr("id", this.id);
            this.$el.attr("role", "tabpanel");
            this.$el.appendTo(".tab-content");
            this.delegateEvents();
        }
    });
});