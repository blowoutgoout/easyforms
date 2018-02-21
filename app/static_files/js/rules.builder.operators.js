/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

/**
 * Rule Builder
 * Operators by type of component
 */

var textOperators = emailOperators = textAreaOperators = [
    {
        "name": "isPresent",
        "label": options.i18n.isPresent,
        "fieldType": "none"
    },
    {
        "name": "isBlank",
        "label": options.i18n.isBlank,
        "fieldType": "none"
    },
    {
        "name": "equalTo",
        "label": options.i18n.is,
        "fieldType": "text"
    },
    {
        "name": "notEqualTo",
        "label": options.i18n.isNot,
        "fieldType": "text"
    },
    {
        "name": "isIn",
        "label": options.i18n.contains,
        "fieldType": "text"
    },
    {
        "name": "isNotIn",
        "label": options.i18n.doesNotContains,
        "fieldType": "text"
    },
    {
        "name": "startsWith",
        "label": options.i18n.startsWith,
        "fieldType": "text"
    },
    {
        "name": "endsWith",
        "label": options.i18n.endsWith,
        "fieldType": "text"
    }
];

var colorOperators = [
    {
        "name": "isPresent",
        "label": options.i18n.isPresent,
        "fieldType": "none"
    },
    {
        "name": "isBlank",
        "label": options.i18n.isBlank,
        "fieldType": "none"
    },
    {
        "name": "equalTo",
        "label": options.i18n.is,
        "fieldType": "color"
    },
    {
        "name": "notEqualTo",
        "label": options.i18n.isNot,
        "fieldType": "color"
    },
    {
        "name": "isIn",
        "label": options.i18n.contains,
        "fieldType": "text"
    },
    {
        "name": "isNotIn",
        "label": options.i18n.doesNotContains,
        "fieldType": "text"
    },
    {
        "name": "startsWith",
        "label": options.i18n.startsWith,
        "fieldType": "text"
    },
    {
        "name": "endsWith",
        "label": options.i18n.endsWith,
        "fieldType": "text"
    }
];

var numberOperators = [
    {
        "name": "isPresent",
        "label": options.i18n.isPresent,
        "fieldType": "none"
    },
    {
        "name": "isBlank",
        "label": options.i18n.isBlank,
        "fieldType": "none"
    },
    {
        "name": "equalTo",
        "label": options.i18n.isEqualTo,
        "fieldType": "number"
    },
    {
        "name": "greaterThan",
        "label": options.i18n.isGreaterThan,
        "fieldType": "number"
    },
    {
        "name": "greaterThanEqual",
        "label": options.i18n.isGreaterThanOrEqual,
        "fieldType": "number"
    },
    {
        "name": "lessThan",
        "label": options.i18n.isLessThan,
        "fieldType": "number"
    },
    {
        "name": "lessThanEqual",
        "label": options.i18n.isLessThanOrEqual,
        "fieldType": "number"
    }
];

var dateOperators = [
    {
        "name": "isPresent",
        "label": options.i18n.isPresent,
        "fieldType": "none"
    },
    {
        "name": "isBlank",
        "label": options.i18n.isBlank,
        "fieldType": "none"
    },
    {
        "name": "equalTo",
        "label": options.i18n.is,
        "fieldType": "text"
    },
    {
        "name": "isBefore",
        "label": options.i18n.isBefore,
        "fieldType": "text"
    },
    {
        "name": "isAfter",
        "label": options.i18n.isAfter,
        "fieldType": "text"
    }
];

var checkboxOperators = [
    {
        "name": "isChecked",
        "label": options.i18n.isChecked,
        "fieldType": "none"
    },
    {
        "name": "isNotChecked",
        "label": options.i18n.isNotChecked,
        "fieldType": "none"
    }
];

var radioOperators = selectOperators = [
    {
        "name": "isPresent",
        "label": options.i18n.hasOptionSelected,
        "fieldType": "none"
    },
    {
        "name": "isBlank",
        "label": options.i18n.hasNoOptionSelected,
        "fieldType": "none"
    },
    {
        "name": "equalTo",
        "label": options.i18n.is,
        "fieldType": "select"
    },
    {
        "name": "notEqualTo",
        "label": options.i18n.isNot,
        "fieldType": "select"
    },
    {
        "name": "isIn",
        "label": options.i18n.contains,
        "fieldType": "text"
    },
    {
        "name": "isNotIn",
        "label": options.i18n.doesNotContains,
        "fieldType": "text"
    },
    {
        "name": "startsWith",
        "label": options.i18n.startsWith,
        "fieldType": "text"
    },
    {
        "name": "endsWith",
        "label": options.i18n.endsWith,
        "fieldType": "text"
    }
];

var hiddenOperators = [
    {
        "name": "isPresent",
        "label": options.i18n.hasAValue,
        "fieldType": "none"
    },
    {
        "name": "isBlank",
        "label": options.i18n.hasNoValue,
        "fieldType": "none"
    },
    {
        "name": "equalTo",
        "label": options.i18n.is,
        "fieldType": "text"
    },
    {
        "name": "notEqualTo",
        "label": options.i18n.isNot,
        "fieldType": "text"
    },
    {
        "name": "isIn",
        "label": options.i18n.contains,
        "fieldType": "text"
    },
    {
        "name": "isNotIn",
        "label": options.i18n.doesNotContains,
        "fieldType": "text"
    },
    {
        "name": "startsWith",
        "label": options.i18n.startsWith,
        "fieldType": "text"
    },
    {
        "name": "endsWith",
        "label": options.i18n.endsWith,
        "fieldType": "text"
    },
    {
        "name": "greaterThan",
        "label": options.i18n.isGreaterThan,
        "fieldType": "number"
    },
    {
        "name": "greaterThanEqual",
        "label": options.i18n.isGreaterThanOrEqual,
        "fieldType": "number"
    },
    {
        "name": "lessThan",
        "label": options.i18n.isLessThan,
        "fieldType": "number"
    },
    {
        "name": "lessThanEqual",
        "label": options.i18n.isLessThanOrEqual,
        "fieldType": "number"
    }
];

var fileOperators = [
    {
        "name": "hasFileSelected",
        "label": options.i18n.hasFileSelected,
        "fieldType": "none"
    },
    {
        "name": "hasNoFileSelected",
        "label": options.i18n.hasNoFileSelected,
        "fieldType": "none"
    }
];

var buttonOperators = [
    {
        "name": "hasBeenClicked",
        "label": options.i18n.hasBeenClicked,
        "fieldType": "none"
    }
];

var formOperators = [
    {
        "name": "hasBeenSubmitted",
        "label": options.i18n.hasBeenSubmitted,
        "fieldType": "none"
    }
];
