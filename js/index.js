$(document).ready(function () {

    getDelay(function (delayMS) {

        $("#pop").on("click", function () {
            $("#imagepreview").attr("src", $("#liveImage").attr("src")); // here asign the image to the modal when the user click the enlarge link
            $("#imagemodal").modal("show"); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
        });

        //check for new images
        let interval = setInterval(getNewLiveImg, delayMS);
        const liveImg = $("#liveImage");

        //get new image, if not new -> do nothing and poll again
        function getNewLiveImg() {
            //get timestamp when the loaded img was last modified
            const lastModified = liveImg.attr("src").split("?").pop();
            userAction({
                data: `lastModified=${lastModified}&action=getNewLiveImg`,
                success: function (newModified) {
                    if (newModified !== "") {
                        //new img found --> start timer to measure time until loaded
                        const startTime = new Date().getTime();
                        //no more polling needed atm
                        clearInterval(interval);

                        liveImg.prop("src", `img/0.jpg?${newModified}`);

                        //when new image ready
                        liveImg.on("load", function () {
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
                    //no new img --> go back to checking every $delayMS
                    clearInterval(interval);
                    liveImg.off();
                    interval = setInterval(getNewLiveImg, delayMS);
                }
            });
        }
    });
});