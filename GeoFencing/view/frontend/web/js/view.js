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
                zoom: 8,
                center: {lat: -34.397, lng: 150.644} // Default center
            });

            geocoder.geocode({'address': this.geoLocation}, function(results, status) {
                if (status === 'OK') {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                } else {
                    console.error('Geocode was not successful for the following reason: ' + status);
                }
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
