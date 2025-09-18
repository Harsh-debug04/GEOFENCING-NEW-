define(['jquery', 'uiComponent', 'mage/url'], function ($, Component, url) {
    'use strict';

    return Component.extend({
        defaults: {
            geoLocation: '',
            checkPincodeUrl: '',
            isMapEnabled: false
        },

        initialize: function (config) {
            this._super();
            this.geoLocation = config.geoLocation;
            this.checkPincodeUrl = config.checkPincodeUrl;
            this.isMapEnabled = config.isMapEnabled;

            var self = this;

            if (this.isMapEnabled) {
                // Wait for the Google Maps script to be loaded
                var checkGoogle = setInterval(function() {
                    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                        clearInterval(checkGoogle);
                        self.initMap();
                    }
                }, 100);

                $('#toggle-map').on('click', function (e) {
                    e.preventDefault();
                    $('#map-container').toggle();
                });
            }


            $('#check-pincode-btn').on('click', function () {
                self.checkPincode();
            });
        },

        initMap: function () {
            var geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById('map-container'), {
                zoom: 4,
                center: {lat: 20.5937, lng: 78.9629} // Default center (India)
            });

            var locations = this.geoLocation.split(/[\r\n]+/).filter(function(el) { return el.trim() !== ''; });
            if (locations.length === 0) {
                return;
            }

            var bounds = new google.maps.LatLngBounds();
            var geocodedCount = 0;
            var markersCount = 0;

            locations.forEach(function (location) {
                geocoder.geocode({'address': location.trim()}, function(results, status) {
                    geocodedCount++;
                    if (status === 'OK' && results[0]) {
                        markersCount++;
                        var marker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                            title: location
                        });
                        bounds.extend(marker.getPosition());
                    } else {
                        console.error('Geocode was not successful for "' + location + '" for the following reason: ' + status);
                    }

                    // When all geocoding requests are done
                    if (geocodedCount === locations.length) {
                        if (markersCount > 0) {
                            map.fitBounds(bounds);
                            // If there is only one marker, fitBounds may zoom in too much.
                            if (markersCount === 1) {
                                google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                                    if (this.getZoom() > 10) {
                                        this.setZoom(10);
                                    }
                                });
                            }
                        }
                    }
                });
            });
        },

        checkPincode: function () {
            var pincode = $('#pincode').val();
            if (!pincode) {
                $('#pincode-result').text('Please enter a pincode.');
                return;
            }

            $.ajax({
                url: this.checkPincodeUrl,
                type: 'POST',
                data: {pincode: pincode, geo_location: this.geoLocation},
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#pincode-result').text(response.message).css('color', 'green');
                    } else {
                        $('#pincode-result').text(response.message).css('color', 'red');
                    }
                },
                error: function () {
                    $('#pincode-result').text('An error occurred. Please try again.').css('color', 'red');
                }
            });
        }
    });
});
