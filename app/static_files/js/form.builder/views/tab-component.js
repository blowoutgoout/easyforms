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
    , "models/component"
    , "views/component", "views/temp-widget"
    , "helper/pubsub"
], function(
    $, _, Backbone
    , ComponentModel
    , ComponentView, TempWidgetView
    , PubSub
    ){
    return ComponentView.extend({
        events:{
            "mousedown" : "mouseDownHandler"
        }
        , mouseDownHandler: function(mouseDownEvent){
            mouseDownEvent.preventDefault();
            mouseDownEvent.stopPropagation();
            // Hide all popovers
            $(".popover").hide();
            $("body").append(new TempWidgetView({model: new ComponentModel($.extend(true,{},this.model.attributes))}).render());
            PubSub.trigger("newTempPostRender", mouseDownEvent);
        }
    });
});