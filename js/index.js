$(document).ready(function () {

    getDelay(function (delayMS) {

        //check for new images
        let interval = setInterval(getNewLiveImg, delayMS);
        const liveImg = $("#liveImage");

        //get new image, if not new -> do nothing and poll again
        function getNewLiveImg() {
            //get timestamp when the loaded img was last modified
            const lastModified = liveImg.attr("src").split('?').pop();
            $.ajax({
                type: 'POST',
                url: "php/getNewLiveImg.php",
                data: {
                    lastModified: lastModified
                },
                success: function (newModified) {
                    if (newModified !== "") {
                        //new img found --> start timer to measure time until loaded
                        const startTime = new Date().getTime();
                        //no more polling needed atm
                        clearInterval(interval);

                        liveImg.prop("src", "img/0.jpg?" + newModified);

                        //when new image ready
                        liveImg.on('load', function () {
                            const loadtime = new Date().getTime() - startTime;

                            //waited for $loadtime already --> time between refreshs at least $delayMS
                            setTimeout(getNewLiveImg, delayMS - loadtime);
                            $("#liveImage").off();
                        });

                    } else {
                        //no new img --> go back to checking every $delayMS
                        clearInterval(interval);
                        liveImg.off();
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