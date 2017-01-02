function myMap() {

    //load userPolygonCoords from database
    getUserCoords(function (data) {

        //local functions
        function setSplinePath(path) {
            courseBoat.setPath(bspline(path.getArray()));
        }

        function updateUserPolygonPath(newPath) {
            //delete listeners from old path
            google.maps.event.clearInstanceListeners(userPolygon.getPath());

            //userPolygon is the one we can edit, so we need to just get the coordinates of the drawn polygon
            userPolygon.setPath(newPath);

            //new listeners because they trigger on path, not on polygon object
            google.maps.event.addListener(userPolygon.getPath(), 'set_at', function () {
                return setSplinePath(userPolygon.getPath())
            });
            google.maps.event.addListener(userPolygon.getPath(), 'insert_at', function () {
                return setSplinePath(userPolygon.getPath())
            });

        }

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
        updateUserPolygonPath(polygonPoints);

        //fires when new polygon is finished (Neue Route)
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
            //exit drawing mode and delete drawn polygon
            drawingManager.setDrawingMode(null);
            polygon.setMap(null);

            updateUserPolygonPath(polygon.getPath());

            setSplinePath(userPolygon.getPath());
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

                drawingManager.setDrawingMode('polygon');
            });

            //shows editable polygon
            $("#btnEdit").click(function () {
                $(".drawOption").removeClass("hidden");
                userPolygon.setMap(map);
            });

            //saves new coords to splineCoords.txt and DB
            $("#btnSave").click(function () {
                var userCoords = userPolygon.getPath().getArray();
                var splinedCoords = bspline(userCoords);
                writeCoords(userCoords, splinedCoords);

                //hide save and cancel buttons
                $(".drawOption").addClass("hidden");

                drawingManager.setDrawingMode(null);
                userPolygon.setMap(null);
            });

            $("#btnCancel").click(function () {
                $(".drawOption").addClass("hidden");
                getUserCoords(function (data) {
                    var userCoords = data;
                    //set listeners for new (old) path
                    updateUserPolygonPath(userCoords);

                    courseBoat.setPath(bspline(userCoords));

                    //hide editable polygon and update splined one
                    userPolygon.setMap(null);
                    courseBoat.setMap(map);
                    drawingManager.setDrawingMode(null);
                });

            });
        });
    });




}