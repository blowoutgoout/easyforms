$( document ).ready(function() {
    $.when(
        $('head').append('<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/css/select2.min.css" type="text/css" /><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" type="text/css" />'),
        $.getScript( "//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/js/select2.min.js" ),
        $.Deferred(function( deferred ){
            $( deferred.resolve );
        })
    ).done(function(){
        $.fn.select2.defaults.set("theme", "bootstrap");
        $('select').select2({
            width: '99.8%'
        });
    });

    var map;
    $.when(
        $.getScript( "//maps.google.com/maps/api/js?sensor=true" ),
        $.Deferred(function( deferred ){
            $( deferred.resolve );
        })
    ).done(function(){
        $.when(
            $.getScript( "//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.21/gmaps.js" ),
            $.Deferred(function( deferred ){
                $( deferred.resolve );
            })
        ).done(function(){
            map = new GMaps({
                div: '#map',
                lat: 32.78595849999999,
                lng: -79.93684619999999
            });
            map.addMarker({
                lat: 32.78595849999999,
                lng: -79.93684619999999,
                title: 'Francis Marion Hotel',
                infoWindow: {
                    content: '<p>Francis Marion Hotel 387 King St, Charleston, SC 29403, United States </p>'
                }
            });
        });
    });

});
