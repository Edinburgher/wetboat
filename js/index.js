//livestream img
$(document).ready(function () {
    //var delayMS = getDelay(); // 5 seconds
    getDelay(function (delayMS) {
        //console.log("Delay is: " + delayMS / 1000 + " seconds");

        setInterval(function () {
            $("#liveImage").prop("src", "img/0.jpg" + "?" + new Date().getTime());
        }, delayMS);

        /*setInterval(function () {
            $("#liveVideo").html('<source src="video.mp4' + "?" + new Date().getTime() + '" type="video/mp4"></source>' );
            $("#liveVideo")[0].load();
        }, 600000);*/
    });

    $('input[type="submit"]').mousedown(function () {
        $(this).css('background', '#2ecc71');
    });
    $('input[type="submit"]').mouseup(function () {
        $(this).css('background', '#1abc9c');
    });

    $('#btnLogin').click(function () {
        $('.login').fadeToggle('slow');
        $(this).toggleClass('green');
    });

    /* Youtube iframe fluid width
    // Find all YouTube videos
    var $allVideos = $("iframe"),

        // The element that is fluid width
        $fluidEl = $(".col-sm-4");

    // Figure out and save aspect ratio for each video
    $allVideos.each(function () {

        $(this)
            .data('aspectRatio', this.height / this.width)

        // and remove the hard coded width/height
        .removeAttr('height')
            .removeAttr('width');

    });

    // When the window is resized
    $(window).resize(function () {

        var newWidth = $fluidEl.width();

        // Resize all videos according to their own aspect ratio
        $allVideos.each(function () {

            var $el = $(this);
            $el
                .width(newWidth)
                .height(newWidth * $el.data('aspectRatio'));

        });

        // Kick off one resize to fix all videos on page load
    }).resize();*/

    /*
        //did not use login pop up
        $(document).mouseup(function (e) {
            var container = $(".login");

            if (!container.is(e.target) // if the target of the click isn't the container...
                &&
                container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.hide();
                $('#btnLogin').removeClass('green');
            }
        });*/

});