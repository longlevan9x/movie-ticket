$(document).ready(function () {
    $('a[href^="#scroll-"]').on('click', function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });
});

function refreshPage($div) {
    if ($div == '' || $div == undefined)
        $div = 'pjax-container';

    $.pjax.reload({container: '#' + $div, timeout: 20000});
    $.hideLoading();
}

function reloadPage() {
    location.reload();
}

function alert($text, $title) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.alert($text, $title);
}

function promt($text, $title) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.promt($text, $title);
}

function confirm($text, $title, confirmCallback, optionalCancelCallback) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.confirm($text, $title).then(confirmCallback, optionalCancelCallback);
}

function iframe($text, $title) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.iframe($text, $title);
}

function ajax($text, $title, ajaxOnLoadCallback) {
    if ($text == null || $text == '' || $text == 'undefined')
        return '';
    eModal.ajax($text, $title).then(ajaxOnLoadCallback);
}
