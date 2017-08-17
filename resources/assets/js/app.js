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
    $('#charnum span').text(140 - len);
});
