function myMap() {
    //load userPolygonCoords from database
    getUserCoords(function (data) {
        var polygonPoints = data;
        var polygonPointsSplined = bspline(polygonPoints);

        //Map init
        var mapCanvas = document.getElementById("map");
        //https://trulycode.com/bytes/disable-google-maps-drag-zoom-mobile-iphone/
        var isDraggable = !('ontouchstart' in document.documentElement);
        var mapOptions = {
            center: polygonPointsSplined[0],
            zoom: 16,

            scrollwheel: false,
            navigationControl: false,
            scaleControl: false,
            draggable: isDraggable,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            //gestureHandling: 'none'
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);

        var courseBoat = new google.maps.Polygon({
            path: polygonPointsSplined,
            strokeColor: "#0000FF",
            strokeOpacity: 1,
            strokeWeight: 4,
            fillOpacity: 0,
            editable: false
        });

        courseBoat.setMap(map);

        var lastMeasurement = new Date(0);
        var boatMarker = new google.maps.Marker();
        var infowindow = new google.maps.InfoWindow();
        //set newest boat location marker every $delayMS seconds
        getDelay(function (delayMS) {
            setInterval(function setNewestBoatMarker() {
                $.ajax({
                    type: 'POST',
                    url: 'php/getNewestMeasurement.php',
                    data: "",
                    async: true,
                    dataType: 'json',
                    success: function (data) {
                        //data is {}
                        var timestamp = new Date(Date.parse(data['time_measured']));
                        if (timestamp > lastMeasurement) {
                            lastMeasurement = timestamp;
                            var datestring = (timestamp.getDate() < 10 ? '0' : '') + timestamp.getDate() + "." +
                                (timestamp.getMonth() + 1 < 10 ? '0' : '') + (timestamp.getMonth() + 1) + "." +
                                timestamp.getFullYear() + " " +
                                (timestamp.getHours() < 10 ? '0' : '') + timestamp.getHours() + ":" +
                                (timestamp.getMinutes() < 10 ? '0' : '') + timestamp.getMinutes() + ":" +
                                (timestamp.getSeconds() < 10 ? '0' : '') + timestamp.getSeconds();

                            var boatCoords = new google.maps.LatLng(data['lat_boat'], data['lon_boat']);

                            var contentString = '<div id="content">' +
                                '<div id="siteNotice">' +
                                '</div>' +
                                '<h1 id="firstHeading" class="firstHeading">Position Boot</h1>' +
                                '<div>' + datestring + '</div>';

                            infowindow.setOptions({
                                content: contentString
                            });
                            boatMarker.setMap(null);
                            boatMarker.setOptions({
                                position: boatCoords,
                                map: map
                            });
                            boatMarker.addListener('click', function () {
                                infowindow.open(map, boatMarker);
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });

            }, delayMS);
        });
    });

}