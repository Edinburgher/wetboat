$(document).ready(function () {
    getDelay(function (delayMS) {
        //console.log("Delay is: " + delayMS / 1000 + " seconds");
        //reloads the image every $delayMS ms
        setInterval(function () {
            $("#liveImage").prop("src", "img/0.jpg" + "?" + new Date().getTime());
        }, delayMS);
    });
});