$.datetimepicker.setLocale('it');

var dates = new Array();
$('.box[data-time-schedule]').each(function() {
    var info = [
        $(this).attr('data-time-schedule'),
        $(this).find('.content p').text()
    ];
    dates.push(info.join(','));
});

$('#datetimepicker').datetimepicker({
    inline: true,
    weeks: false,
    format: 'Y-m-d H:i:s',
    highlightedDates: dates
});

$('textarea[name=content]').keyup(function() {
    var len = $(this).val().length;
    $('#charnum span').text(280 - len);
});

$('body').on('click', '.media-preview ,button', function() {
    $(this).closest('.media-preview').remove();
});

$('body').on('change', '.file input:file', function() {
    var input = this;

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var cell = $('.media-preview.is-invisible').clone().removeClass('is-invisible').beforeTo('.file');
            cell.find('input:file').val(input.val());
            cell.find('img').attr('src', e.target.result);
            input.val('');
        }

        reader.readAsDataURL(input.files[0]);
    }
});
