//needs to be global so that getNewestMeasurements only has to be called once in diagramm.js
var setNewestBoatMarker;

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

        var boatMarker = new google.maps.Marker();
        var infowindow = new google.maps.InfoWindow();
        setNewestBoatMarker = function (x, lat, lon) {
            var datestring = (x.getDate() < 10 ? '0' : '') + x.getDate() + "." +
                (x.getMonth() + 1 < 10 ? '0' : '') + (x.getMonth() + 1) + "." +
                x.getFullYear() + " " +
                (x.getHours() < 10 ? '0' : '') + x.getHours() + ":" +
                (x.getMinutes() < 10 ? '0' : '') + x.getMinutes() + ":" +
                (x.getSeconds() < 10 ? '0' : '') + x.getSeconds();

            var boatCoords = new google.maps.LatLng(lat, lon);

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
    });

}