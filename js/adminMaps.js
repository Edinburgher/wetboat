function myMap() {

    //load userPolygonCoords from database
    getUserCoords(function (polygonPoints) {

        //local functions
        function setSplinePath(path) {
            courseBoat.setPath(bspline(path.getArray()));
        }

        google.maps.Polygon.prototype.splineAndSetPath = function (path) {
            var arr = (Array.isArray(path)) ? (path) : (path.getArray());
            this.setPath(bspline(arr));
        }

        google.maps.Polygon.prototype.changePath = function (path) {
            //http://stackoverflow.com/questions/4775722/check-if-object-is-array
            google.maps.event.clearInstanceListeners(this.getPath());
            this.setPath(path);
            google.maps.event.addListener(this.getPath(), 'set_at', function () {
                courseBoat.splineAndSetPath(this);
            });
            google.maps.event.addListener(this.getPath(), 'insert_at', function () {
                courseBoat.splineAndSetPath(this);
            });
        }

        var polygonPointsSplined = bspline(polygonPoints);

        //DEBUG
        /*polygonPointsSplined.forEach(function (entry) {
            console.log(entry.lat().toFixed(6) + "," + entry.lng().toFixed(6));
        });*/

        //Map init
        var mapCanvas = document.getElementById("map");

        //https://trulycode.com/bytes/disable-google-maps-drag-zoom-mobile-iphone/
        var isDraggable = !('ontouchstart' in document.documentElement);
        var mapOptions = {
            center: polygonPointsSplined[0],
            zoom: 16,

            draggable: isDraggable,
            scrollwheel: isDraggable,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            gestureHandling: 'auto'
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
            drawingControl: false,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON
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

        //set initial listeners to drag polygon in edit mode
        google.maps.event.addListener(userPolygon.getPath(), 'set_at', function () {
            courseBoat.splineAndSetPath(this);
        });
        google.maps.event.addListener(userPolygon.getPath(), 'insert_at', function () {
            courseBoat.splineAndSetPath(this);
        });

        //fires when new polygon is finished (Neue Route)
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (newPolygon) {
            //exit drawing mode and delete drawn polygon
            drawingManager.setDrawingMode(null);
            newPolygon.setMap(null);
            userPolygon.setMap(null);

            //updateUserPolygonPath(newPolygon.getPath());
            userPolygon.changePath(newPolygon.getPath());

            courseBoat.splineAndSetPath(userPolygon.getPath());
            userPolygon.setMap(map);
            courseBoat.setMap(map);
        });

        userPolygon.setMap(null);
        courseBoat.setMap(map);
        drawingManager.setMap(map);


        //Buttons above maps
        $(document).ready(function () {

            //hides old course and prepares drawing mode
            $("#btnNew").click(function () {
                //show the save and cancel buttons
                $(".drawOption").removeClass("hidden");

                //hide the old course
                userPolygon.setMap(null);
                courseBoat.setMap(null);

                drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
                map.setOptions({
                    draggable: true,
                    gestureHandling: 'greedy'
                });
            });

            //shows editable polygon
            $("#btnEdit").click(function () {
                $(".drawOption").removeClass("hidden");
                getUserCoords(function (userCoords) {
                    //set listeners for new (old) path
                    //updateUserPolygonPath(userCoords);
                    userPolygon.setMap(null);
                    userPolygon.changePath(userCoords);
                    courseBoat.splineAndSetPath(userCoords);
                    userPolygon.setMap(map);
                    map.setOptions({
                        draggable: true,
                        gestureHandling: 'greedy'
                    });
                });
            });

            //saves new coords to splineCoords.txt and DB
            $("#btnSave").click(function () {
                var userCoords = userPolygon.getPath().getArray();
                var splinedCoords = bspline(userCoords);
                writeCoords(userCoords, splinedCoords);

                //hide save and cancel buttons
                $(".drawOption").addClass("hidden");
                map.setOptions({
                    draggable: isDraggable,
                    gestureHandling: 'auto'
                });
                drawingManager.setDrawingMode(null);
                userPolygon.setMap(null);
            });

            $("#btnCancel").click(function () {
                $(".drawOption").addClass("hidden");
                getUserCoords(function (userCoords) {
                    //set listeners for new (old) path
                    //updateUserPolygonPath(userCoords);
                    userPolygon.changePath(userCoords);
                    courseBoat.splineAndSetPath(userCoords);
                    map.setOptions({
                        draggable: isDraggable,
                        gestureHandling: 'auto'
                    });
                    //hide editable polygon and update splined one
                    userPolygon.setMap(null);
                    courseBoat.setMap(map);
                    drawingManager.setDrawingMode(null);
                });

            });
        });
    });


}