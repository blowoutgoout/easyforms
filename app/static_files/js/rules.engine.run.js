/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

jQuery(document).ready(function(){
    Function.prototype.getHashCode = (function(id) {
        return function() {
            if (!this.hashCode) {
                this.hashCode = '<hash|#' + (id++) + '>';
            }
            return this.hashCode;
        }
    }(0));
    /**
     * Find and return a element (jQuery object)
     *
     * @param selector
     * @returns {*|jQuery|HTMLElement}
     */
    var findElement = function(selector) {
        return $(selector);
    };

    /**
     * Find and return a field (jQuery object)
     *
     * @param fieldName
     * @returns {*}
     */
    var findField = function( fieldName ) {
        var field; // jQuery object
        var componentType = fieldName.split("_", 1);
        if( componentType == "checkbox" ){
            field = $( "input[name*='"+fieldName+"[]']" );
        } else if ( componentType == "radio" ){
            field = $( "input[name*='"+fieldName+"']" );
        } else {
            field = $("#" + fieldName);
        }
        return field;
    };

    /**
     * Returns a active jQuery object by name of the component
     *
     * @param name
     * @returns {*}
     */
    var activeField = function ( name ) {
        var field; // jQuery object
        var componentType = name.split("_", 1);
        if( componentType == "radio" ) {
            // Get checked radio buttons
            field = $("input[name='"+name+"']:checked");
        } else if( componentType == "checkbox" ) {
            // Get checked checkbox fields
            field = $( "input[name*='"+name+"[]']:checked" );
        } else {
            // Get other fields
            field = $("#" + name);
        }
        return field;
    };

    /**
     * Returns a active field or element
     *
     * @param data
     * @returns {*|jQuery|HTMLElement}
     */
    var getOriginalElement = function (data) {
        return  ( data.find("original") == "field" ) ?
            activeField(data.find("original", "originalField")) : findElement(data.find("original", "originalElement"));
    };

    /**
     * Set value of target element
     *
     * @param data
     * @param originalElement
     */
    var setTargetElement = function(data, originalElement) {

        var target = ( data.find("target") == "field" ) ?
            findField(data.find("target", "targetField")) : findElement(data.find("target", "targetElement")),
            value = (originalElement.is(':input')) ? originalElement.val() : originalElement.html();

        // Replace default value, if original element has several elements
        if (originalElement.size() > 1) {
            value = originalElement.map(function() {
                return $( this).is(':input') ? $( this ).val() : $( this ).text();
            }).get().join( ", " );
        }

        // If target has several elements
        if (target.size() > 1) {
            if(target.is(':radio, :checkbox')){
                if (originalElement.size() > 1) {
                    $(":checkbox[name*='"+target.attr("name")+"']").prop('checked', false);
                    $.each(originalElement, function(){
                        if($(this).is(':checkbox')){
                            value = $(this).val();
                            $(":checkbox[name*='"+target.attr("name")+"'][value*='"+value+"']").prop('checked', true);
                        }
                    });
                } else {
                    $(":input[name*='"+target.attr("name")+"']").prop('checked', false);
                    $(":input[name*='"+target.attr("name")+"'][value*='"+value+"']").prop('checked', true);
                }
            } else if ( target.is( ":input" ) ) {
                target.val(value);
            } else {
                target.text( value );
            }
        } else {
            if(target.is(':radio, :checkbox')){
                $(":input[name*='"+target.attr("name")+"']").prop('checked', false);
                $(":input[name*='"+target.attr("name")+"'][value*='"+value+"']").prop('checked', true);
            } else if ( target.is( ":input" ) ) {
                target.val(value);
            } else {
                target.html(value);
            }
        }

    };

    /**
     * Set value in target field or element
     *
     * @param data
     * @param value
     */
    var setTargetValue = function ( data, value ) {
        if( data.find("target") == "field" ) {
            findField(data.find("target", "targetField")).val(value);
        } else if ( data.find("target") == "element" ) {
            var target = findElement(data.find("target", "targetElement"));
            if (target.size() > 1) {
                $.each(target, function(){
                    if ( $(this).is( ":input" ) ) {
                        $(this).val(value);
                    } else {
                        $(this).text(value);
                    }
                })
            } else {
                if ( target.is( ":input" ) ) {
                    target.val(value);
                } else {
                    target.text(value);
                }
            }
        }
    };

    /**
     * Used to perform arithmetic operations on fields values
     * and displays the result in a target field or element
     *
     * @param data
     * @returns {number}
     */
    var performArithmeticOperations = function(data) {
        var operator = data.find("operator");
        var operands = data.find("operands");
        var result = NaN; // Initial value
        if( !!operator ){
            if( $.isArray( operands ) ) {
                $.each( operands, function(index, operand){
                    var component = activeField(operand);
                    if (component.size() > 1) { // Used by checkbox components
                        $.each(component, function( subindex, _element ) {
                            var number = parseFloat($(_element).val());
                            if(!isNaN(number)){
                                if( isNaN(result) ) { // First number
                                    result = number
                                } else {
                                    // Assignment Operators
                                    switch(operator){
                                        case '+':
                                            result += number;
                                            break;
                                        case '-':
                                            result -= number;
                                            break;
                                        case '*':
                                            result *= number;
                                            break;
                                        case '/':
                                            result /= number;
                                            break;
                                        case '%':
                                            result %= number;
                                            break;
                                    }
                                }
                            }
                        });
                    } else if (component.size() == 1) { // All components
                        var number = parseFloat(component.val());
                        // Check if a valid number
                        if(!isNaN(number)){
                            if( isNaN(result) ) { // First number
                                result = number;
                            } else {
                                // Assignment Operators
                                switch(operator){
                                    case '+':
                                        result += number;
                                        break;
                                    case '-':
                                        result -= number;
                                        break;
                                    case '*':
                                        result *= number;
                                        break;
                                    case '/':
                                        result /= number;
                                        break;
                                    case '%':
                                        result %= number;
                                        break;
                                }
                            }
                        }
                    }
                });
            }
            // Replace NaN result
            if( isNaN(result) ){
                result = 0;
            }
            // Show result in target field or element
            setTargetValue(data, result);
        }
        return 0;
    };

    /**
     * Executes each rule,
     * first when the page is loaded,
     * then when the user change any value to the form elements
     */
    var rules = function() {
        var conditionsAdapter = {},
            body = $("body"),
            oldOuterHeight = body.outerHeight(true);
        $.each(options.fieldIds, function (index, fieldID) {
            var componentType = fieldID.split("_", 1);
            if( componentType == "checkbox" ) {
                conditionsAdapter[fieldID] = $("#" + fieldID).is(":checked");
            } else if ( componentType == "radio" ) {
                var fieldName = fieldID.split("_", 2)[0] + '_' + fieldID.split("_", 2)[1];
                conditionsAdapter[fieldName] = $("input[name='"+fieldName+"']:checked").val();
            }  else if ( componentType == "button" ) {
                conditionsAdapter[fieldID] = $("#" + fieldID).data('clicked');
            } else {
                conditionsAdapter[fieldID] = $("#" + fieldID).val();
            }
        });
        var actionsAdapter = {
            toShow: function(data) {
                if( data.find("target") == "field" ) {
                    var field = findField(data.find("target", "targetField")).show(); // Show field
                    field.parent().closest('div.form-group').show(); // Show label
                } else if ( data.find("target") == "element" ) {
                    findElement(data.find("target", "targetElement")).show();
                }
            },
            toHide: function(data) {
                if( data.find("target") == "field" ) {
                    var field = findField(data.find("target", "targetField")).hide(); // Hide field
                        field.parent().closest('div.form-group').hide(); // Hide label
                } else if ( data.find("target") == "element" ) {
                    findElement(data.find("target", "targetElement")).hide();
                }
            },
            toEnable: function(data) {
                if( data.find("target") == "field" ) {
                    var field = findField(data.find("target", "targetField")).prop('disabled', false); // Enable field
                } else if ( data.find("target") == "element" ) {
                    findElement(data.find("target", "targetElement")).prop('disabled', false);
                }
            },
            toDisable: function(data) {
                if( data.find("target") == "field" ) {
                    var field = findField(data.find("target", "targetField")).prop('disabled', true); // Disable field
                } else if ( data.find("target") == "element" ) {
                    findElement(data.find("target", "targetElement")).prop('disabled', true);
                }
            },
            performArithmeticOperations: function(data) {
                performArithmeticOperations(data);
            },
            resetResult: function(data) {
                // Don't reset the result, if the form has been submitted
                if (options.submitted) return false;
                setTargetValue(data, 0);
            },
            copy: function (data) {
                var fields = getOriginalElement(data);
                setTargetElement(data, fields);
            },
            skip: function (data, ruleID) {
                var skip = $.grep(options.skips, function(e){ return e.id == ruleID; });
                if (skip.length == 0) {
                    options.skips.push({
                        id: ruleID,
                        from: null,
                        to: parseInt(data.find("step"))
                    });
                }
            },
            resetSkip: function (data, ruleID) {
                options.skips = $.grep(options.skips, function(e){ return e.id != ruleID; });
            },
            formatNumber: function (data) {
                if( data.find("target") == "field" ) {
                    var field = findField(data.find("target", "targetField")); // field
                    var fieldValue = numeral(field.val()).format(data.find("format"));
                    field.val(fieldValue);
                } else if ( data.find("target") == "element" ) {
                    var element = findElement(data.find("target", "targetElement"));
                    var elementValue = numeral(element.text()).format(data.find("format"));
                    element.text(elementValue);
                }
            }
        };
        $.each( options.rules, function( index, rule ){
            var engine = new RuleEngine({
                conditions: JSON.parse(rule.conditions),
                actions: JSON.parse(rule.actions)
            });
            var oppositeAdapter = rule.opposite ? {
                toShow: "toHide",
                toHide: "toShow",
                toEnable: "toDisable",
                toDisable: "toEnable",
                performArithmeticOperations: "resetResult",
                skip: "resetSkip"
            } : {};
            engine.run(conditionsAdapter, actionsAdapter, oppositeAdapter);
        });
        // After run, send the new form height to the parent window
        var newOuterHeight = body.outerHeight(true);
        if (oldOuterHeight != newOuterHeight) {
            Utils.postMessage({
                height: newOuterHeight
            });
        }
    };

    $( window ).load(function() {
        rules();
    });

    var formEl = $("form");

    formEl.find(":input").on('keyup change click input', function() {
        rules();
    });

    formEl.on("success", function(){
        rules();
    });

    formEl.find(":button").mousedown(function()
    {
        $(this).data('clicked', true);
    });
});