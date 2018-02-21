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
    "jquery", "underscore", "backbone",
    "views/component",
    "helper/pubsub"
], function(
    $, _, Backbone,
    ComponentView,
    PubSub
    ){
    return ComponentView.extend({
        events:{
            "click"   : "preventPropagation" //stops checkbox / radio reacting.
            , "mousedown" : "mouseDownHandler"
            , "mouseup"   : "mouseUpHandler"
        }

        , mouseDownHandler : function(mouseDownEvent){
            mouseDownEvent.stopPropagation();
            mouseDownEvent.preventDefault();
            var that = this;
            // Popover
            $(".popover").remove();
            this.$el.popover("show");
            $(".popover #save").on("click", this.saveHandler(that));
            $(".popover #delete").on("click", this.deleteHandler(that));
            $(".popover #cancel").on("click", this.cancelHandler(that));
            // Add drag event for all
                $("body").on("mousemove", function(mouseMoveEvent){
                    if ( Math.abs(mouseDownEvent.pageX - mouseMoveEvent.pageX) > 10 ||
                         Math.abs(mouseDownEvent.pageY - mouseMoveEvent.pageY) > 10 )
                    {
                        that.$el.popover('destroy');
                        PubSub.trigger("myComponentDrag", mouseDownEvent, that.model);
                        that.mouseUpHandler();
                    }
                });
        }

        , preventPropagation: function(e) {
            e.stopPropagation();
            e.preventDefault();
        }

        , mouseUpHandler : function(mouseUpEvent) {
            $("body").off("mousemove");
        }

        , saveHandler : function(boundContext) {
            return function(mouseEvent) {
                mouseEvent.preventDefault();
                var fields = $(".popover .field");
                _.each(fields, function(e){

                    var $e = $(e)
                        , type = $e.attr("data-type")
                        , name = $e.attr("id");

                    switch(type) {
                        case "checkbox":
                            boundContext.model.setField(name, $e.is(":checked"));
                            break;
                        case "input":
                            boundContext.model.setField(name, $e.val());
                            break;
                        case "textarea":
                            boundContext.model.setField(name, $e.val());
                            break;
                        case "textarea-split":
                            boundContext.model.setField(name,
                                _.chain($e.val().split("\n"))
                                    .map(function(t){return $.trim(t)})
                                    .filter(function(t){return t.length > 0})
                                    .value()
                            );
                            break;
                        case "select":
                            var valarr = _.map($e.find("option"), function(e){
                                return {value: e.value, selected: e.selected, label:$(e).text()};
                            });
                            boundContext.model.setField(name, valarr);
                            break;
                    }
                });
                boundContext.model.trigger("change");
                $(".popover").remove();
            }
        }

        , deleteHandler : function (boundContext) {
            return function(mouseEvent) {
                mouseEvent.preventDefault();
                if (confirm(polyglot.t('alert.confirmToDeleteField'))) {
                    $(".popover").remove();
                    boundContext.model.trigger("remove");
                    PubSub.trigger("myComponentDelete", boundContext.model);
                }
            };
        }

        , cancelHandler : function(boundContext) {
            return function(mouseEvent) {
                mouseEvent.preventDefault();
                $(".popover").remove();
                boundContext.model.trigger("change");
            };
        }

    });
});