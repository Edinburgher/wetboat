function getDelay(callback) {
    if (isNaN(getDelay.delayMS)) {
        userAction({
            data: "action=getDelay",
            success: function (delay) {
                //delay is an int
                delay = JSON.parse(delay);
                getDelay.delayMS = delay * 1000;
                return callback(getDelay.delayMS);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    } else {
        return callback(getDelay.delayMS);
    }
}

//http://www.deluxeblogtips.com/missing-way-to-create-function-static/
//function is an object in js
//acts like a static variable in C
getDelay.delayMS = NaN;