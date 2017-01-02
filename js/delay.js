function getDelay(callback) {
    var ret;
    if (isNaN(getDelay.delayMS)) {
        $.ajax({
            type: 'POST',
            url: 'php/getDelay.php',
            data: "",
            async: true,
            dataType: 'json',
            success: function (delay) {
                //delay is an int
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