function myMap() {

    //load userPolygonCoords from database
    getUserCoords(function (data) {
        var polygonPoints = data;
        var polygonPointsSplined = bspline(polygonPoints);

        //DEBUG
        /*polygonPointsSplined.forEach(function (entry) {
            console.log(entry.lat().toFixed(6) + "," + entry.lng().toFixed(6));
        });*/

        //Map init
        var mapCanvas = document.getElementById("map");
        var mapOptions = {
            center: polygonPointsSplined[0],
            zoom: 16,

            draggable: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            gestureHandling: "greedy"
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);

        //Set course of the Boat as Polygon

        var courseBoat = new google.maps.Polygon({
            path: polygonPointsSplined,
            strokeColor: "#0000FF",
            strokeOpacity: 1,
            strokeWeight: 4,
            fillOpacity: 0,
            editable: false
        });

        //init drawing overlay
        var drawingManager = new google.maps.drawing.DrawingManager({
            //drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: false,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    //google.maps.drawing.OverlayType.MARKER,
                    //google.maps.drawing.OverlayType.CIRCLE,
                    google.maps.drawing.OverlayType.POLYGON,
                    //google.maps.drawing.OverlayType.POLYLINE,
                    //google.maps.drawing.OverlayType.RECTANGLE
                ]
            }
        });

        //Polygon that isn't splined
        var userPolygon = new google.maps.Polygon({
            path: polygonPoints,
            strokeOpacity: 0.7,
            strokeWeight: 2,
            fillOpacity: 0,
            editable: true,
            draggable: true
        });


        /*
    //Boot Position
    var boatCoords = new google.maps.LatLng(47.785994, 13.037930);
  
            var contentString = '<div id="content">'+
                '<div id="siteNotice">'+
                '</div>'+
                '<h1 id="firstHeading" class="firstHeading">Position Boot</h1>';

            var infowindow = new google.maps.InfoWindow({
              content: contentString
            });
            var boatMarker = new google.maps.Marker({position:myMarker});
            marker.addListener('click', function() {
              infowindow.open(map, boatMarker);
            });
            boatMarker.setMap(map);*/

        //
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {

            drawingManager.setDrawingMode(null);
            userPolygon.setPath(polygon.getPath());
            polygon.setMap(null);

            console.log("Draw complete activated");

            var splinedArray = bspline(userPolygon.getPath().getArray());
            courseBoat.setPath(splinedArray);
            userPolygon.setMap(map);
            courseBoat.setMap(map);
            google.maps.event.addListener(userPolygon.getPath(), 'set_at', setSplinePath);

            google.maps.event.addListener(userPolygon.getPath(), 'insert_at', setSplinePath);

        });

        google.maps.event.addListener(userPolygon.getPath(), 'set_at', setSplinePath);

        google.maps.event.addListener(userPolygon.getPath(), 'insert_at', setSplinePath);

        google.maps.event.addListener(userPolygon, 'dragend', function () {
            console.log("drag activated");
            var splinedArray = bspline(userPolygon.getPath().getArray());
            courseBoat.setPath(splinedArray);

        });


        userPolygon.setMap(null);
        courseBoat.setMap(map);
        drawingManager.setMap(map);

        function setSplinePath() {
            courseBoat.setPath(bspline(userPolygon.getPath().getArray()));
        }
        //Buttons above maps
        $(document).ready(function () {

            $("#btnNew").click(function () {
                $(".drawOption").removeClass("hidden");
                userPolygon.setMap(null);
                courseBoat.setMap(null);
                drawingManager.setOptions({
                    drawingControl: true,
                    drawingMode: google.maps.drawing.OverlayType.POLYGON
                });
            });

            $("#btnEdit").click(function () {
                $(".drawOption").removeClass("hidden");
                userPolygon.setMap(map);
                /*drawingManager.setOptions({
                    drawingControl: true,
                    //drawingMode: google.maps.drawing.OverlayType.POLYGON
                });*/
            });

            $("#btnSave").click(function () {
                var overwrite = true;
                //overwrite = confirm("Wollen Sie wirklich das alte Polygon überschreiben?");
                if (overwrite === true) {
                    var userCoords = userPolygon.getPath().getArray();
                    var splinedCoords = bspline(userCoords);
                    courseBoat.setPath(splinedCoords);
                    writeCoords(userCoords, splinedCoords);
                }
                $(".drawOption").addClass("hidden");

                drawingManager.setOptions({
                    drawingControl: false,
                    drawingMode: null
                });
                userPolygon.setMap(null);
            });

            $("#btnCancel").click(function () {
                $(".drawOption").addClass("hidden");
                getUserCoords(function (data) {
                    var userCoords = data;
                    userPolygon.setPath(userCoords);
                    //set listeners for new (old) path
                    google.maps.event.addListener(userPolygon.getPath(), 'set_at', setSplinePath);

                    google.maps.event.addListener(userPolygon.getPath(), 'insert_at', setSplinePath);

                    courseBoat.setPath(bspline(userCoords));
                    userPolygon.setMap(null);
                    courseBoat.setMap(map);
                    drawingManager.setOptions({
                        drawingControl: false,
                        drawingMode: null
                    });
                });

            });
        });
    });




}