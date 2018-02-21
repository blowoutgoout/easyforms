({
    name: "../main",
    out: "../main-built.js"
    , shim: {
        underscoreBase: {
            exports: '_'
        },
        'underscore': {
            deps: ['underscoreBase'],
            exports: '_'
        },
        'backbone': {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        'bootstrap': {
            deps: ['jquery']
        },
        'popover-extra-placements': {
            deps: ['jquery', 'bootstrap']
        },
        'jquery.cookie': {
            deps: ['jquery']
        },
        'jquery.bsAlerts': {
            deps: ['jquery']
        },
        'polyglot': {
            exports: 'Polyglot'
        },
        'prism': {
            exports: 'Prism'
        }
    }
    , paths: {
        app         : ".."
        , collections : "../collections"
        , data        : "../data"
        , models      : "../models"
        , helper      : "../helper"
        , templates   : "../templates"
        , views       : "../views"
    }
})
