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
    , "text!templates/app/tab-dropdown.html"

], function($, _, Backbone,
            _tabDropdownTemplate){
    return Backbone.View.extend({
        tagName: "div"
        , className: "tab-pane"
        , initialize: function(options) {
            this.options = options;
            this.id = this.options.title.toLowerCase().replace(/\W/g,'');
            this.tabDropdownTemplate = _.template(_tabDropdownTemplate);
            this.render();
        }
        , render: function(){
            // Render & append nav for tab
            $("#formtabs").append(this.tabDropdownTemplate({title: this.options.title, links: this.options.links, id: this.id}))
        }
    });
});