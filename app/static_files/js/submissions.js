/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

// Patch Model and Collection so they emit a 'fetch' event when starting to fetch data
_.each(["Model", "Collection"], function(name) {
    // Cache Backbone constructor.
    var ctor = Backbone[name];
    // Cache original fetch.
    var fetch = ctor.prototype.fetch;

    // Override the fetch method to emit a fetch event.
    ctor.prototype.fetch = function() {
        // Trigger the fetch event on the instance.
        this.trigger("fetch", this);

        // Pass through to original fetch.
        return fetch.apply(this, arguments);
    };
});

// Patch View, add a close method
Backbone.View.prototype.close = function(){
    this.remove();
    this.unbind();
    if (this.onClose){
        this.onClose();
    }
};

//******************
// Underscore
//******************

// Override template settings
_.templateSettings = {
    evaluate: /\{\{(.+?)\}\}/g,
    interpolate: /\{\{=(.+?)\}\}/g,
    escape: /\{\{-(.+?)\}\}/g
};

//******************
// App
//******************

var App = App || {};

App.Options = {
    sort_attribute: '-created_at', // {attr}: ASC, -{attr}: DESC
    resizeColumns: false,
    showColumns: true,
    minimumCountColumns: 2,
    columns: {}
};

//******************
// localStorage / Cookies
//******************

App.set = function(key, value) {
    // Store data locally by default
    if(typeof(Storage) !== "undefined") {
        localStorage.setItem(key, JSON.stringify(value));
    } else {
        $.cookie(key, JSON.stringify(value), {
            expires: 365
        });
    }
};

App.get = function(key) {
    if(!_.isNull(localStorage.getItem(key))){
        return JSON.parse(localStorage.getItem(key));
    } else if(!_.isUndefined($.cookie(key))){
        return JSON.parse($.cookie(key));
    }
    return {};
};

App.remove = function(key) {
    localStorage.removeItem(key);
    $.cookie(key, null);
};

//******************
// App Views
//******************

App.SubmissionsView = Backbone.View.extend({
    className: 'submissions',
    subViews: {},
    template: _.template($('#submissionsTemplate').html()),
    initialize: function(){
        // Event Listeners
        this.listenTo(this.collection, 'fetch', this.showProgress);
        this.listenTo(this.collection, 'sync', this.onSync);
    },
    events: {
        "keyup input.searchTxt" : function(event) {
            event.preventDefault();
            if(event.keyCode == 13){
                this.collection.searchPage(event.currentTarget.value);
            }
            return false;
        },
        'click button#refreshBtn': function(event) {
            event.preventDefault();
            // Delete Form Options saved in localstorage
            App.remove('form_' + options.formID + '_options');
            // Refresh the page
            location.reload();
        },
        'click button.searchBtn': function(event) {
            event.preventDefault();
            this.collection.searchPage($("input.searchTxt").val());
            return false;
        },
        'click a#filter-link': function(event) {
            event.preventDefault();
            this.collection.filterPage($(event.currentTarget).attr('data-start'), $(event.currentTarget).attr('data-end'));
            return false;
        },
        'click input.column': function (event) {
            event.stopImmediatePropagation();
            // Get Index
            var checkbox = this.$(event.currentTarget);
            var index = checkbox.val();
            // Show/Hide columns
            this.$('tr.submission div:nth-child('+index+'),th:nth-child('+index+')').toggle();
            // Save the column visibility
            var key = checkbox.data('key');
            App.Options.columns[key] = checkbox.is(':checked');
            // Save in localstorage
            App.set('form_' + options.formID + '_options', App.Options);
            // Enable/disable Checkboxes
            this.checkMinimumCountColumns();
        },
        'click button.resizeColumns': function(event) {
            event.preventDefault();
            // Toggle Buttons
            this.$('button.resizeColumns').toggle();
            // Toggle Table class
            this.$('table.table').toggleClass('table-fullsize');

            App.Options.resizeColumns = !App.Options.resizeColumns;
            App.set('form_' + options.formID + '_options', App.Options);
            return false;
        },
        'click input#allRows': function (event){
            event.stopImmediatePropagation();
            // Toggle between check/uncheck
            var isChecked = !this.$('input.row').prop('checked');
            this.$('input.row').prop('checked', isChecked);
        },
        'click a#markAsRead': function (event){
            event.preventDefault();
            // Get all checkbox checked Ids
            var ids = [];
            var checkboxes = this.$("input.row:checked");
            _.map(checkboxes, function(checkbox){
                ids.push($(checkbox).data("id"))
            });
            // Update models by Ids
            if( ids.length > 0 ){
                this.collection.updateModelsByIds(ids, { "new": 0 });
            }
        },
        'click a#deleteSelectedRows': function (event){
            event.preventDefault();
            // Get all checkbox checked Ids
            var ids = [];
            var checkboxes = this.$("input.row:checked");
            _.map(checkboxes, function(checkbox){
                ids.push($(checkbox).data("id"))
            });
            // Destroy models by Ids
            if( ids.length > 0 ){
                if(confirm(options.i18n.areYouSureDeleteItems)) {
                    this.collection.destroyModelsByIds(ids);
                }
            }
        },
        'click a#submitted_at': function (event){
            event.preventDefault();
            var sort_attribute = this.collection.sort_attribute == '-created_at' ? 'created_at' : '-created_at';
            this.collection.sortPage( sort_attribute );
            return false;
        }
    },
    onSync: function(model_or_collection, resp, options){
        // Only for Collections
        if(_.isObject(model_or_collection.models)){
            this.render();
        }
    },
    addSubview: function( model, collection, options ){
        // Check if submission.data has a value
        if(model.get('data')){
            this.subViews[model.cid] = new App.SubmissionView({ model: model });
            this.$('tbody').append(this.subViews[model.cid].render().el);
        } else {
            // Remove this submission and reload the page
            this.collection.destroyModelsByIds([ model.get('id') ])
        }
    },
    showProgress: function(){
        this.$("#loading").show();
    },
    hideProgress: function(){
        this.$("#loading").hide();
    },
    closeSubviews: function() {
        // Call destroy method for each view
        _.invoke(this.subViews, 'close');
    },
    onClose: function() {
        // Destroy subViews
        this.closeSubviews();
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    checkMinimumCountColumns: function(){
        // Enable/disable Checkboxes
        if (this.$( "input.column:checked" ).length <= App.Options.minimumCountColumns) {
            this.$( "input.column:checked" ).prop('disabled', true);
        } else {
            this.$( "input.column:checked" ).prop('disabled', false);
        }
    },
    toggleColumns: function(){
        if (App.Options.showColumns) {
            var self = this;
            var keys = _.map(options.fields, function(field){
                return field['name'];
            });
            var columns = App.Options.columns;
            // Verify if columns is empty
            if( _.isEmpty( columns ) || ((_.keys(columns)).length < keys.length) ){
                // Set true each column visibility
                _.each(keys, function(key){
                    columns[key] = true;
                });
            } else {
                // Show/Hide each column
                _.each(keys, function(key){
                    var checkbox = self.$('input[data-key="'+key+'"]');
                    var index = checkbox.val();
                    var isVisible = columns[key];
                    if(isVisible) {
                        checkbox.prop( "checked", isVisible );
                    } else {
                        self.$('tr.submission div:nth-child('+index+'),th:nth-child('+index+')').hide();
                        checkbox.prop( "checked", isVisible );
                    }
                });
                // Enable/disable Checkboxes
                this.checkMinimumCountColumns();
            }
        } else {
            this.$( "input.column:checked" ).prop('disabled', true);
        }
    },
    resizeColumns: function(){
        if (App.Options.resizeColumns) {
            // Hide/Show Buttons
            this.$('button.resizeColumns').eq(0).hide();
            this.$('button.resizeColumns').eq(1).show();
            // Toggle Table class
            this.$('table.table').toggleClass('table-fullsize');
        }
    },
    initDatePicker: function () {
        var self = this;
        var format = 'MM/DD/YYYY';
        var start = this.collection.startDate;
        var end = this.collection.endDate;

        if (_.isEmpty(start) || _.isEmpty(end)) {
            start = moment().subtract(29, 'days');
            end = moment();
        } else {
            start = moment(start, format);
            end = moment(end, format);
        }

        function cb(start, end) {
            self.$('#date-range').text(start.format(format) + ' - ' + end.format(format));
            self.$('#filter-link').attr('data-start', start.format(format));
            self.$('#filter-link').attr('data-end', end.format(format));
            var params = $.param({
                start: start.format(format),
                end: end.format(format)
            });
            self.$("#csv-link").attr("href", options.csvEndPoint + '&' + params);
            self.$("#xlsx-link").attr("href", options.xlsxEndPoint + '&' + params);
        }

        var ranges = [];
        ranges[options.i18n.today] = [moment(), moment()];
        ranges[options.i18n.yesterday] = [moment().subtract(1, 'days'), moment().subtract(1, 'days')];
        ranges[options.i18n.last7Days] = [moment().subtract(6, 'days'), moment()];
        ranges[options.i18n.last30Days] = [moment().subtract(29, 'days'), moment()];
        ranges[options.i18n.thisMonth] = [moment().startOf('month'), moment().endOf('month')];
        ranges[options.i18n.lastMonth] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];

        this.$('#range').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                customRangeLabel: options.i18n.customRange,
                "applyLabel": options.i18n.apply,
                cancelLabel: options.i18n.clear
            },
            ranges: ranges
        }, cb);

        this.$('#range').on('cancel.daterangepicker', function(ev, picker) {
            self.$('#date-range').text('');
            self.$("#csv-link").attr("href", options.csvEndPoint);
            self.$("#xlsx-link").attr("href", options.xlsxEndPoint);
        });

        cb(start, end);
    },
    render: function(){
        // clean views before rendering new ones
        this.closeSubviews();
        this.hideProgress();
        this.el.innerHTML = this.template(this.collection.getPage());
        this.$("input.searchTxt").val(this.collection.keywords);
        this.collection.each(this.addSubview.bind(this));
        this.subViews['paginationView'] = new App.PaginationView({ collection: this.collection });
        this.$("#pagination").append(this.subViews['paginationView'].render().el);
        this.toggleColumns();
        this.resizeColumns();
        this.initDatePicker();
        return this;
    }
});

App.SubmissionView = Backbone.View.extend({
    template: _.template($('#submissionTemplate').html()),
    tagName: 'tr',
    className: 'submission',
    events: {
        'click .view': 'viewModel',
        'click .edit': 'editModel',
        'click .remove': 'removeModel'
    },
    viewModel: function(event){
        event.preventDefault();
        App.Router.navigate('view/' + this.model.id,
            {trigger: true});
    },
    editModel: function(event){
        event.preventDefault();
        App.Router.navigate('edit/' + this.model.id,
            {trigger: true});
    },
    removeModel: function(event){
        event.preventDefault();
        if(confirm(options.i18n.areYouSureDeleteItem)) {
            // To wait for the server to respond
            // before removing the model from the collection
            this.model.destroy({wait: true});
        }
        return false;
    },
    onClose: function() {
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    render: function(){
        var id = this.model.get("id");
        var isNew = this.model.get("new");
        var created_at = this.model.get("submitted");
        var data = this.model.get("data"); // Submission Data
        if(!_.isObject(data)) {
            data = JSON.parse(data);
        }
        this.el.innerHTML = this.template({
            id: id,
            isNew: isNew,
            data: data,
            created_at: created_at
        });
        // If a checkbox or select list data, add a space after the comma
        var that = this;
        that.$('td[data-key*="selectlist"], td[data-key*="checkbox"]')
            .each(function(index, value){
                that.$(value).text(function(i, val) {
                    return val.replace(/,/g, ", ");
                });
            });
        return this;
    }
});

App.DetailView = Backbone.View.extend({
    tagName: "div",
    className: "detailView",
    template: _.template($('#detailTemplate').html()),
    initialize: function(opts){
        // Check if the model exist
        if (_.isObject(this.model)) {
            this.modelExists = true;
        } else {
            var self = this;
            this.model = new Submission();
            this.model.id = opts.id;
            this.model.url = function () {
                return options.hasPrettyUrls ? options.endPoint + '/' + opts.id : options.deleteEndPoint + "&id=" + opts.id;
            };
            this.model.fetch({
                success: function (model, response) {
                    self.modelExists = true;
                    self.render();
                }
            });
        }
    },
    events: {
        'click .edit': 'editModel',
        'click .remove': 'removeModel',
        'click .removeFile': 'removeFileModel',
        'click #addComment': 'addCommentModel',
        'click .deleteComment': 'deleteCommentModel',
        'click #showEmptyFields' : 'showEmptyFields'
    },
    editModel: function(event){
        event.preventDefault();
        App.Router.navigate('edit/' + this.model.id,
            {trigger: true});
    },
    removeModel: function(event){
        event.preventDefault();
        if(confirm(options.i18n.areYouSureDeleteItem)) {
            // To wait for the server to respond
            // before removing the model from the collection
            this.model.destroy({
                wait: true,
                success: function(){
                    App.Router.navigate('',
                        {trigger: true});
                }
            });
        }
        return false;
    },
    removeFileModel: function(event){
        event.preventDefault();
        if(confirm(options.i18n.areYouSureDeleteFileItem)) {
            var fileID = $(event.currentTarget).data('id');
            var that = this;
            // Send ajax request with the ids attached
            $.ajax({
                //url: options.endPoint + '/updateall',
                url: options.deleteFileEndPoint,
                type: 'POST',
                data: {
                    submission_id: this.model.get('id'),
                    file_id: fileID
                }
            }).done(function(resp){
                if (resp.success) {
                    $("tr[data-file='" + resp.fileID + "']").remove();
                    that.model.fetch();
                } else {
                    alert(options.i18n.errorOnDelete);
                }
            }).fail(function( jqXHR, textStatus ) {
                alert(options.i18n.errorOnDelete);
            });
        }
        return false;
    },
    addCommentModel: function(event) {
        event.preventDefault();
        var self = this;
        // Save comment on server
        $.ajax({
            //url: options.endPoint + '/add-comment',
            url: options.addCommentEndPoint,
            type: 'POST',
            data: {
                submission_id: this.model.get('id'),
                comment: $("#commentContent").val()
            }
        }).done(function(comment){
            if (comment.id) {
                // Update comments in the model
                var oldComments = self.model.get('comments');
                oldComments.push(comment);
                var newComments = _.uniq(oldComments, function(oldComment) {
                    return oldComment.id;
                });
                // Refresh comment list on Submission Model
                self.model.set('comments', newComments);
                // Reload this page
                self.render();
            } else {
                alert(options.i18n.errorOnUpdate);
            }
        }).fail(function( jqXHR, textStatus ) {
            alert(options.i18n.errorOnUpdate);
        });
    },
    deleteCommentModel: function (event) {
        event.preventDefault();
        var self = this;
        if(confirm(options.i18n.areYouSureDeleteCommentItem)) {
            var commentID = $(event.currentTarget).data('id');
            // Send ajax request with the ids attached
            $.ajax({
                //url: options.endPoint + '/delete-comment',
                url: options.deleteCommentEndPoint,
                type: 'POST',
                data: {
                    submission_id: self.model.get('id'),
                    comment_id: commentID
                }
            }).done(function(resp){
                if (resp.success) {
                    $("li[data-comment-id='" + resp.commentID + "']").remove();
                    var oldComments = self.model.get('comments');
                    var newComments = _.reject(oldComments, function(comment){ return comment.id == resp.commentID; });
                    // Refresh comment list on Submission Model
                    self.model.set('comments', newComments);
                    // Reload this page
                    self.render();
                } else {
                    alert(options.i18n.errorOnDelete);
                }
            }).fail(function( jqXHR, textStatus ) {
                alert(options.i18n.errorOnDelete);
            });
        }
        return false;
    },
    showEmptyFields: function() {
        this.render();
    },
    onClose: function() {
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    afterAppend: function(){
        // Refresh Google Map
        if (_.isObject(this.map) && typeof google !== "undefined") {
            var center = this.map.getCenter();
            google.maps.event.trigger(this.map, 'resize');
            this.map.setCenter(center);
            this.map.setZoom(12);
        }
        // Scroll comments
        var commentList = this.$(".commentList");
        this.$(commentList).scrollTop(commentList[0].scrollHeight);
    },
    onRender: function(){
        // if the submission is new, mark as false
        if( this.model.get("new") ){
            this.model.save({ "new": 0 }, { silent:true });
        }
        // Scroll comments
        var commentList = this.$(".commentList");
        this.$(commentList).scrollTop(commentList[0].scrollHeight);
    },
    render: function(){
        if (this.modelExists) {
            // Submission
            var id = this.model.get("id");
            var author = this.model.get("authorName");
            var lastEditor = this.model.get("lastEditorName");
            var created_at = this.model.get("submitted");
            var updated_at = this.model.get("updated");
            var ip = this.model.get("ip");
            var sender = this.model.get("sender");
            var data = this.model.get("data"); // Submission Data
            var showEmptyFields = this.$('#showEmptyFields').is(':checked') ? 'checked' : '';
            // Files
            var files = this.model.get("files"); // Submission Files
            // Comments
            var comments = this.model.get("comments"); // Submission Comments
            if (!_.isEmpty(files)) {
                var sorting = _.keys(options.fileFields);
                var sortedFiles = [];
                sorting.forEach(function(key) {
                    var found = false;
                    files = files.filter(function(file) {
                        if(!found && file.field == key) {
                            sortedFiles.push(file);
                            found = true;
                            return false;
                        } else {
                            return true;
                        }
                    });
                });
                files = sortedFiles.concat(files);
            }
            // Sender
            if(!_.isObject(sender)) {
                sender = JSON.parse(sender);
            }
            if(!_.isObject(data)) {
                data = JSON.parse(data);
            }
            this.el.innerHTML = this.template({
                id: id,
                form_name: options.formName,
                data: data,
                files: files,
                comments: comments,
                author: author,
                lastEditor: lastEditor,
                created_at: created_at,
                updated_at: updated_at,
                sender: sender,
                ip: ip,
                showEmptyFields: showEmptyFields
            });
            // If a checkbox or select list data, add a space after the comma
            var that = this;
            that.$('.table-detail').find('td[data-key*="selectlist"], td[data-key*="checkbox"]')
                .each(function(index, value){
                    that.$(value).text(function(i, val) {
                        return val.replace(/,/g, ", ");
                    });
                });

            // Draw Google Map
            if (_.isNumber(sender.latitude) && _.isNumber(sender.longitude) && typeof google !== "undefined" ) {
                this.$el.find('#map').css({"width": "100%", "height": "300px"});
                var latlng = new google.maps.LatLng(sender.latitude, sender.longitude);
                var mapOptions = {
                    center: latlng,
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                this.map = new google.maps.Map(this.$el.find('#map')[0], mapOptions);
                this.marker = new google.maps.Marker({
                    position: latlng,
                    map: this.map,
                    title: 'Sender Location'
                });
            }
            this.onRender();
        }

        return this;
    }
});

App.BulkView = Backbone.View.extend({
    tagName: "div",
    template: _.template($('#bulkTemplate').html()),
    onClose: function() {
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    render: function(){
        this.el.innerHTML = this.template();
        return this;
    }
});

App.FormView = Backbone.View.extend({
    modelExists: false,
    tagName: "div",
    template: _.template($('#formTemplate').html()),
    events: {
        'submit #form-app': 'saveModel'
    },
    initialize: function(opts){
        this.subtitle = opts.subtitle;
        // Check if the model exist
        if( _.isObject( this.model ) ){
            this.modelExists = true;
        } else {
            if (!_.isUndefined(opts.id)) { // Only for edit submission
                var self = this;
                this.model = new Submission();
                this.model.id = opts.id;
                this.model.url = function () {
                    return options.hasPrettyUrls ? options.endPoint + '/' + opts.id : options.deleteEndPoint + "&id=" + opts.id;
                };
                this.model.fetch({
                    success: function (model, response) {
                        self.modelExists = true;
                        self.render();
                    }
                });
            }
        }
    },
    saveModel: function(event) {
        event.preventDefault();
        var files = $('[type="file"]');
        var data = {};
        var keys = _.map(options.fields, function(field){
            return field['name'];
        });
        _.each(keys, function(key){
            var componentType = key.split("_", 1);
            var values = [];
            if(componentType == "selectlist") {
                $('select[name="'+key+'[]"] option:selected').each( function() {
                    values.push(this.value);
                });
                data[key] = values;
            } else if(componentType == "checkbox"){
                $('input[name="'+key+'[]"]:checked').each( function() {
                    values.push(this.value);
                });
                data[key] = values;
            } else if(componentType == "radio"){
                $('input[name="'+key+'"]:checked').each( function() {
                    data[key] = this.value;
                });
            } else {
                data[key] = $('#'+key).val();
            }
        });

        // Save
        if (!_.isEmpty(data) || files.size() > 0) {
            // Create a new model if not exist
            var that = this;
            if (this.modelExists) {
                files.each(function () {
                    // Upload file
                    var field = $(this).attr('name');
                    var FileUpload = Backbone.Model.extend({ url: options.uploadEndPoint + "&s_id=" + that.model.get('id'), fileAttribute: field });
                    var fileUpload = new FileUpload();
                    var fileObject = $(this)[0].files[0];
                    if (!_.isUndefined(fileObject)) {
                        // Prevent that user lost his data
                        $(window).off('beforeunload').on('beforeunload', function(){
                            return options.i18n.uploadingFile;
                        });
                        fileUpload.set(field, fileObject);
                        fileUpload.save({}, {
                            success: function (fileModel, response) {
                                // Update files in the model
                                var oldFiles = that.model.get('files');
                                oldFiles.unshift(response);
                                var newFiles = _.uniq(oldFiles, function(oldFile) {
                                    return oldFile.id;
                                });
                                that.model.set('files', newFiles);
                            }
                        });
                        fileUpload.on('progress', function(percentComplete) {
                            if (percentComplete == 1) {
                                // Remove prevention
                                $(window).off('beforeunload');
                            }
                        });
                    }
                });
                this.model.save({
                    data: data
                }, {
                    success: function() {
                        // Redirect to Index Page
                        App.Router.navigate('',
                            {trigger: true});
                    },
                    error: function(model, response){
                        alert(response.statusText);
                    }
                });
            } else {
                // After creation, will be redirected to Index by the collection
                this.collection.create({
                    form_id: options.formID,
                    data: data
                },{
                    wait: true,
                    error: function(model, response){
                        alert(response.statusText);
                    },
                    success: function (model) {
                        files.each(function () {
                            // Upload file
                            var field = $(this).attr('name');
                            var FileUpload = Backbone.Model.extend({ url: options.uploadEndPoint + "&s_id=" + model.get('id'), fileAttribute: field });
                            var fileUpload = new FileUpload();
                            var fileObject = $(this)[0].files[0];
                            if (typeof fileObject !== 'undefined') {
                                // Prevent that user lost his data
                                $(window).off('beforeunload').on('beforeunload', function(){
                                    return options.i18n.uploadingFile;
                                });
                                fileUpload.set(field, fileObject);
                                fileUpload.save({}, {
                                    success: function (fileModel, response){
                                        // Refresh collection
                                        that.collection.firstPage();
                                    }
                                });
                                fileUpload.on('progress', function(percentComplete){
                                    if (percentComplete == 1) {
                                        // Remove prevention
                                        $(window).off('beforeunload');
                                    }
                                });
                            }
                        });
                    }
                });
            }
        }
    },
    onClose: function() {
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    render: function(){
        this.el.innerHTML = this.template({form_name: options.formName, subtitle: this.subtitle});
        // Populate Data in Form if model exist
        if( this.modelExists ){
            this.populateDataInForm();
        }
        // Remove client side validation for file upload fields
        var fileFields = this.$('[type="file"]');
        fileFields.removeAttr('required');
        fileFields.parents(".form-group").removeClass("required-control");
        return this;
    },
    populateDataInForm: function(){
        var that = this; // View
        var data = that.model.get("data"); // Submission Data
        if(!_.isObject(data)) {
            data = JSON.parse(data);
        }
        // Form population
        var keys = _.map(options.fields, function(field){
            return field['name'];
        });
        _.each(keys, function(key){
            var componentType = key.split("_", 1);
            if(componentType == "selectlist") {
                var selected = data[key];
                that.$('select[name="'+key+'[]"]').each( function() {
                    var elem = $(this);
                    _.each(selected, function(val, k, list){
                        that.$('select[name="'+key+'[]"]' + " option[value=\"" + val + "\"]").prop("selected", true);
                    });
                });
            } else if(componentType == "checkbox"){
                var checks = data[key];
                that.$('input[name="'+key+'[]"]').each( function() {
                    var elem = $(this);
                    _.each(checks, function(val, k, list){
                        if ( elem.val() ==  val ) {
                            elem.prop('checked', true);
                        }
                    });
                });
            } else if(componentType == "radio"){
                that.$('input[name="'+key+'"]').each( function(i, val) {
                    var elem = $(val);
                    if ( elem.val() ==  data[key] ) {
                        elem.prop('checked', true);
                    }
                });
            } else {
                that.$('#'+key).val(data[key]);
            }
        });

        return this;
    }
});

App.NavView = Backbone.View.extend({
    template: _.template($("#navTemplate").html()),
    page: 'Submissions',
    initialize: function(opts){
        this.page = opts.page;
    },
    onClose: function() {
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    render: function () {
        this.el.innerHTML = this.template({
            page: this.page
        });
        // this.$('#' + this.tab).addClass("active"); // Activate Tab
        return this;
    }
});

App.PaginationView = Backbone.View.extend({
    template: _.template($('#paginationTemplate').html()),
    initialize: function() {
        _.bindAll(this, 'previous', 'next');
        this.listenTo(this.collection, 'sync:page', this.render);
    },
    events: {
        'click a.first': 'first',
        'click a.prev': 'previous',
        'click a.next': 'next',
        'click a.last': 'last'
    },
    onClose: function() {
        // Destroy this view
        this.undelegateEvents();
        this.$el.removeData().unbind();
        // Remove view from DOM
        this.remove();
        Backbone.View.prototype.remove.call(this);
    },
    render: function() {
        this.el.innerHTML = this.template( this.collection.getPage() );
        return this;
    },
    isDisabled: function(event){
        return this.$(event.currentTarget).parent().hasClass('disabled');
    },
    first: function(event) {
        event.preventDefault();
        if ( !this.isDisabled(event) ) {
            this.collection.firstPage();
        }
        return false;
    },
    previous: function(event) {
        event.preventDefault();
        if ( !this.isDisabled(event) ) {
            this.collection.previousPage();
        }
        return false;
    },
    next: function(event) {
        event.preventDefault();
        if ( !this.isDisabled(event) ) {
            this.collection.nextPage();
        }
        return false;
    },
    last: function(event) {
        event.preventDefault();
        if ( !this.isDisabled(event) ) {
            this.collection.lastPage();
        }
        return false;
    }
});

//******************
// App Collections
//******************

var Submission = Backbone.Model.extend({
    url: function() {
        var base = _.result(this, 'urlRoot') || _.result(this.collection, 'url') || urlError();
        if (this.isNew()) return base;
        return base + (base.charAt(base.length - 1) === '/' ? '' : '&id=') + encodeURIComponent(this.id);
    },
    methodUrl: function(method){
        if (method == "delete") {
            return options.hasPrettyUrls ? options.endPoint + '/' + this.attributes.id : options.deleteEndPoint + "&id=" + this.attributes.id;
        } else if (method == "update") {
            return options.hasPrettyUrls ? options.endPoint + '/' + this.attributes.id : options.updateEndPoint + "&id=" + this.attributes.id;
        } else if (method == "create") {
            return options.hasPrettyUrls ? options.endPoint + '/' + this.attributes.id : options.createEndPoint + "&id=" + this.attributes.id;
        }
        return false;
    },
    sync: function(method, model, options) {
        if (model.methodUrl && model.methodUrl(method.toLowerCase())) {
            options = options || {};
            options.url = model.methodUrl(method.toLowerCase());
        }
        Backbone.sync(method, model, options);
    },
    initialize: function(){
    }
});

var Submissions = Backbone.Collection.extend({
    url: options.endPoint,
    model: Submission,
    pager: {},
    sort_attribute: App.Options.sort_attribute, // {attr}: ASC, -{attr}: DESC
    initialize: function(){
        _.bindAll(this, 'parse', 'destroyModelsByIds', 'loadPager','getPage',
            'reloadPage', 'showPage', 'firstPage', 'lastPage', 'nextPage', 'previousPage', 'searchPage');
        if (options.hasPrettyUrls) {
            this.model = Backbone.Model;
        }
        this.pager.currentPage = 1;
        this.keywords = "";
        this.startDate = "";
        this.endDate = "";
        this.sort_attribute = App.Options.sort_attribute; // refresh
        this.listenTo(this, 'destroy', this.onDestroy);
        this.listenTo(this, 'sync', this.onSync);
    },
    onSync: function(model_or_collection, resp, options) {
        if(!_.isObject(model_or_collection.models)) {
            // Check if the sync is after model creation or edition
            if((resp.updated_at > resp.created_at) || (!_.isNull(resp.new) && !resp.new)) {
            } else {
                // Reload the collection
                this.firstPage().then(function(){
                    App.Router.navigate('',
                        {trigger: true});
                });
            }
            return false;
        }
    },
    onDestroy: function(model) {
        this.fetchPage();
    },
    parse: function(resp) {
        this.pager = resp._meta;
        return resp.items;
    },
    updateModelsByIds: function(ids, attributes){
        var self = this;
        // Send ajax request with the ids attached
        $.ajax({
            //url: options.endPoint + '/updateall',
            url: options.updateAllEndPoint,
            type: 'POST',
            data: {
                id: options.formID,
                ids: ids,
                attributes: attributes
            }
        }).done(function(resp){
            if( resp.success && (resp.itemsUpdated > 0) ){
                // Reload this page
                self.reloadPage();
            } else {
                alert(options.i18n.errorOnUpdate);
            }
        }).fail(function( jqXHR, textStatus ) {
            alert(options.i18n.errorOnUpdate);
        });
    },
    destroyModelsByIds: function(ids){
        var self = this;
        // Send ajax request with the ids attached
        $.ajax({
            url: options.deleteAllEndPoint,
            type: 'POST',
            data: {
                id: options.formID,
                ids: ids
            }
        }).done(function( resp ) {
            if( resp.success && (resp.itemsDeleted > 0) ){
                // Reload this page or the new last page (if currentPage == pageCount)
                if(self.pager.currentPage == self.pager.pageCount) {
                    self.previousPage();
                } else {
                    self.reloadPage();
                }
            } else {
                alert(options.i18n.errorOnDelete);
            }
        }).fail(function(jqXHR, textStatus) {
            alert(options.i18n.errorOnDelete);
        });
    },
    loadPager: function() {
        // Default
        this.pager.pageCount = Math.ceil(this.pager.totalCount / this.pager.perPage);
        this.pager.prev = false;
        this.pager.next = false;
        // Range
        this.pager.range = {
            min: this.pager.totalCount ? ((this.pager.currentPage - 1) * this.pager.perPage + 1) : this.pager.totalCount,
            max: Math.min(this.pager.totalCount, this.pager.currentPage * this.pager.perPage)
        };
        // Prev
        if (this.pager.currentPage > 1) {
            this.pager.prev = this.pager.currentPage - 1;
        }
        // Next
        if (this.pager.currentPage < this.pager.pageCount) {
            this.pager.next = this.pager.currentPage + 1;
        }
        return this;
    },
    getPage: function() {
        this.loadPager();
        return this.pager;
    },
    reloadPage: function(){
        return this.fetchPage();
    },
    showPage: function(pageNumber) {
        this.pager.currentPage = pageNumber;
        return this.fetchPage();
    },
    nextPage: function() {
        ++this.pager.currentPage;
        return this.fetchPage();
    },
    previousPage: function() {
        --this.pager.currentPage;
        return this.fetchPage();
    },
    firstPage: function(){
        this.pager.currentPage = 1;
        return this.fetchPage();
    },
    lastPage: function(){
        this.pager.currentPage = this.pager.pageCount;
        return this.fetchPage();
    },
    searchPage: function(keywords){
        this.keywords = keywords;
        this.pager.currentPage = 1;
        return this.fetchPage();
    },
    filterPage: function (startDate, endDate) {
        this.startDate = startDate;
        this.endDate = endDate;
        this.pager.currentPage = 1;
        return this.fetchPage();
    },
    sortPage: function(sort_attribute){
        this.sort_attribute = sort_attribute;
        App.Options.sort_attribute = sort_attribute;
        App.set('form_' + options.formID + '_options', App.Options);
        this.pager.currentPage = 1;
        return this.fetchPage();
    },
    fetchPage: function(){
        var self = this;
        return this.fetch({
            data: $.param({ id: options.formID, q: this.keywords, sort: this.sort_attribute, page: this.pager.currentPage, start: this.startDate, end: this.endDate }),
            reset: true,
            success:function(){
                self.trigger("sync:page")
            }
        });
    }
});

//******************
// App Router
//******************

var Router = Backbone.Router.extend({
    views:{},
    initialize: function(opts){
        this.main = opts.main;
        this.submissions = opts.submissions;
        this.routesHit = 0;
        //keep count of number of routes handled by your application
        Backbone.history.on('route', function() { this.routesHit++; }, this);
    },
    routes: {
        '': 'index',
        'back': 'back',
        //'bulk': 'bulk',
        'add': 'add',
        'edit/:id': 'edit',
        'view/:id': 'view'
    },
    closeViews: function() {
        // Call close method for each view
        _.invoke(this.views, 'close');
    },
    index: function(){
        this.closeViews();
        this.views["navView"] = new App.NavView({ page: options.i18n.index, collection: this.submissions });
        this.main.html(this.views["navView"].render().el);
        this.views['submissionsView'] = new App.SubmissionsView({ collection: this.submissions });
        this.main.append(this.views['submissionsView'].render().el);
    },
    back: function() {
        if(this.routesHit > 1) {
            //more than one route hit -> user did not land to current page directly
            this.routesHit = this.routesHit - 2; //Added line: read below
            window.history.back();
        } else {
            //otherwise go to the home page. Use replaceState if available so
            //the navigation doesn't create an extra history entry
            if(Backbone.history.getFragment() != '/')
                this.routesHit = 0;
            this.navigate('', {trigger:true, replace:true});
        }
    },
    bulk: function(){
        this.closeViews();
        this.views["navView"] = new App.NavView({ page: options.i18n.bulkActions });
        this.main.html(this.views["navView"].render().el);
        this.views['bulkView'] = new App.BulkView();
        this.main.append(this.views['bulkView'].render().el);
    },
    add: function(){
        this.closeViews();
        this.views["navView"] = new App.NavView({ page: options.i18n.addSubmission, collection: this.submissions });
        this.main.html(this.views["navView"].render().el);
        this.views['formView'] = new App.FormView({ subtitle: options.i18n.addSubmission, collection: this.submissions });
        this.main.append(this.views['formView'].render().el);
    },
    edit: function(id){
        this.closeViews();
        this.views["navView"] = new App.NavView({ page: options.i18n.editSubmission, collection: this.submissions });
        this.main.html(this.views["navView"].render().el);
        this.views['formView'] = new App.FormView({ id: id, subtitle: options.i18n.editSubmission, model: this.submissions.get(id) });
        this.main.append(this.views['formView'].render().el);
    },
    view: function(id){
        this.closeViews();
        this.views["navView"] = new App.NavView({ page: options.i18n.submissionDetails, collection: this.submissions });
        this.main.html(this.views["navView"].render().el);
        this.views['detailView'] = new App.DetailView({ id: id, model: this.submissions.get(id) });
        this.main.append(this.views['detailView'].render().el);
        this.views['detailView'].afterAppend();
    }
});

//******************
// App Init
//******************

App.init = function(){

    // Local Data
    var opts = App.get('form_' + options.formID + '_options');
    if ( !_.isEmpty(opts) ){
        App.Options = opts;
    }
    // Server Data
    App.Submissions = new Submissions();
    return App.Submissions.fetchPage().then(function(){
        App.Router = new Router({
            main: $("#main"),
            submissions: App.Submissions
        });
        Backbone.history.start();
    });
};

//******************
// App run
//******************

$(function() {

    App.init();

});

