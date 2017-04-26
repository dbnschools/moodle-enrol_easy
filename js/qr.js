function main() {
    $(document).ready(function() {

        $('#reader').html5_qrcode(function(data){

                window.opener.postMessage({code: data}, document.referrer);

                $('#reader').html5_qrcode_stop();

                window.close();

            },
            function(error){
                console.log(error);
                //show read errors
            }, function(videoError){
                console.log(videoError);
                //the video stream could be opened
            }
        );
    });
}

if (typeof jQuery == 'undefined') {
    var elem = document.createElement('script');

    elem.onload = function() {
        main();
    };

    elem.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js';

    document.getElementsByTagName('body')[0].appendChild(elem);
}
else {
    main();
}



