//needs to be global so that getNewestMeasurements only has to be called once in diagramm.js
let setNewestBoatMarker;

function myMap() {
    //load userPolygonCoords from database
    getUserCoords(function (polygonPoints) {
        const polygonPointsSplined = bspline(polygonPoints);

        //Map init
        const mapCanvas = document.getElementById("map");
        //https://trulycode.com/bytes/disable-google-maps-drag-zoom-mobile-iphone/
        const isDraggable = !("ontouchstart" in document.documentElement);
        const mapOptions = {
            center: polygonPointsSplined[0],
            zoom: 16,

            scrollwheel: false,
            navigationControl: false,
            scaleControl: false,
            draggable: isDraggable,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        const map = new google.maps.Map(mapCanvas, mapOptions);

        const courseBoat = new google.maps.Polygon({
            path: polygonPointsSplined,
            strokeColor: "#0000FF",
            strokeOpacity: 1,
            strokeWeight: 4,
            fillOpacity: 0,
            editable: false
        });

        courseBoat.setMap(map);

        const boatMarker = new google.maps.Marker();
        const infowindow = new google.maps.InfoWindow();

        setNewestBoatMarker = function (datestring, lat, lon) {

            const boatCoords = new google.maps.LatLng(lat, lon);

            const contentString = '<div id="content">' +
                '<div id="siteNotice">' +
                "</div>" +
                '<h1 id="firstHeading" class="firstHeading">Position Boot</h1>' +
                "<div>" + datestring + "</div>";

            infowindow.setOptions({
                content: contentString
            });
            boatMarker.setMap(null);
            boatMarker.setOptions({
                position: boatCoords,
                map: map
            });
            boatMarker.addListener("click", function () {
                infowindow.open(map, boatMarker);
            });
        }
    });

}