function main() {
    $(document).ready(function() {
        var qr_button = $('.enrolform_easy .qr-button');

        if (qr_button.length) {
            qr_button.click(function() {
                var qr_reader = PopupCenter($(this).data('url'), "QRReader", 400, 400);

                window.addEventListener('message',function(event) {
                    $('#enrolform_course_code').val(event.data.code);
                    $('#enrolform_easy').submit();
                });

            });
        }

        var coursecodes = $('#enrol_easy_settings input[data-type="enroleasycode"]');

        if (coursecodes.length) {
            coursecodes.each(function(i, e) {

                    $('body').append('<div id="qrcode"></div>');

                    $(e).parent().addClass('input-group');
                    $(e).parent().css({'max-width': '15em'});
                    var qr_button = $('<div class="input-group-addon qr-button"><a href="" style="color: #55595c;"><i class="fa fa-qrcode" aria-hidden="true"></i></a></div>');
                    $(e).after(qr_button);

                    var qr_button_text = $(qr_button).find('a');

                    $('#qrcode').qrcode($(e).val());
                    var canvas = $('#qrcode canvas');

                    var qr_data = canvas[0].toDataURL();

                    if (qr_data) {
                        qr_button_text.attr('href', qr_data);
                        var dlname = 'QR';

                        if ($(e).data('coursename')) {
                            dlname += ' - ' + $(e).data('coursename');
                        }
                        if ($(e).data('groupname')) {
                            dlname += ' - ' + $(e).data('groupname');
                        }
                        dlname += ' - ' + $(e).val();
                        qr_button_text.attr('download', dlname + '.png');
                    }

                    $('#qrcode').remove();

                });

        }

    });
}

if (typeof jQuery == 'undefined') {
    var elem = document.createElement('script');

    elem.onload = function() {
        main();
    };

    elem.src = 'js/jquery-3.2.0.min.js';

    document.getElementsByTagName('body')[0].appendChild(elem);
}
else {
    main();
}

/*
 * https://stackoverflow.com/questions/4068373/center-a-popup-window-on-screen
 */
function PopupCenter(url, title, w, h) {

    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    if (window.focus) {
        newWindow.focus();
    }

	return newWindow;
}
