$(document).ready(function () {

    getDelay(function (delayMS) {

        //check for new images
        var interval = setInterval(getNewLiveImg, delayMS);

        //get new image, if not new -> do nothing and poll again
        function getNewLiveImg() {
            //get timestamp when the loaded img was last modified
            var lastModified = $("#liveImage").attr("src").split('?').pop();
            $.ajax({
                type: 'POST',
                url: "php/getNewLiveImg.php",
                data: {
                    lastModified: lastModified
                },
                success: function (newModified) {
                    if (newModified !== "") {
                        //new img found --> start timer to measure time until loaded
                        var startTime = new Date().getTime();
                        //no more polling needed atm
                        clearInterval(interval);

                        $("#liveImage").prop("src", "img/0.jpg?" + newModified);

                        //when new image ready
                        $("#liveImage").on('load', function () {
                            var loadtime = new Date().getTime() - startTime;

                            //waited for $loadtime already --> time between refreshs at least $delayMS
                            setTimeout(getNewLiveImg, delayMS - loadtime);
                            $("#liveImage").off();
                        });

                    } else {
                        //no new img --> go back to checking every $delayMS
                        clearInterval(interval);
                        $("#liveImage").off();
                        interval = setInterval(getNewLiveImg, delayMS);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError);
                }
            });
        }
    });
});