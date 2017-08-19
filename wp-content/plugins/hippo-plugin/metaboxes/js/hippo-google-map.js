(function ( $ ) {

    $.fn.HippoMapForMeta = function( options ) {


        var settings = $.extend({}, options );


        return this.each(function(){

            var elem = $( this);
            var name = $( this ).prop('name');
            var latvalue = $( this ).data('lat');
            var lngvalue = $( this ).data('lng');
            var wrapper = $(this).parent();
            var Latlng = new google.maps.LatLng(latvalue, lngvalue);
            var map = new google.maps.Map($( this ).next()[0], {
                center: Latlng,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            map.controls[google.maps.ControlPosition.TOP_CENTER].push($(this)[0]);

            var marker = new google.maps.Marker({
                map: map,
                position: Latlng,
                draggable:false,
                animation: google.maps.Animation.DROP
            });


            var infowindow = new google.maps.InfoWindow();
            //var geocoder = new google.maps.Geocoder;
            var autocomplete = new google.maps.places.Autocomplete($(this)[0]);
            autocomplete.bindTo('bounds', map);

            autocomplete.addListener('place_changed', function() {

                var place = autocomplete.getPlace();

                if ( ! place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                infowindow.close();
                marker.setVisible(false);

                // If the place has a geometry, then present it on a map.
                if ( place.geometry.viewport ) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(15);  // Why 15? Because it looks good.
                }

                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                infowindow.setContent('<div>' + place.formatted_address + '</div>');
                infowindow.open(map, marker);

               $(wrapper).find('input.map-lat-value').val(place.geometry.location.lat());
               $(wrapper).find('input.map-lng-value').val(place.geometry.location.lng());
               $(wrapper).find('input.hippo-map-autocomplete-input').prop('data-lat', place.geometry.location.lat());
               $(wrapper).find('input.hippo-map-autocomplete-input').prop('data-lng', place.geometry.location.lng());
            });

        });
    };

}( jQuery ));



