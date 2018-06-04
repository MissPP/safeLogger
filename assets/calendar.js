var calendar = {};
calendar.isLeapYear = function (year) {
    var leap = (((year % 4)==0) && ((year % 100)!=0) || ((year % 400)==0)) ? 28 : 29;
    return leap;
};

var now = new Date();
var now_year = now.getFullYear();
var now_month = now.getMonth();
var dayPerMonth = [31, calendar.isLeapYear(now_year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

//º∆À„»’∆⁄
calendar.dayStart = function (month, year) {
    var tmpDate = new Date(year, month, 1);
    return (tmpDate.getDay());
};

calendar.render = function () {
    var day = $('.day');
    day.html('');
    $('.year').html(now_year);
    $('.month').html(now_month);

    html = '';
    for (var x = 0; x < calendar.dayStart(now_month, now_year); x++) {
        html += '<li style="color:#ccc;"  >&nbsp</li>';
    }
    for (var x = 1; x <= dayPerMonth[now_month - 1]; x++) {
        html += '<li class="select-day">' + x + '</li>';
    }
    day.append(html);
    $('.select-day').hover(function(){
        $(this).css('background-color','#ccc');

    },function () {
        $(this).css('background-color','initial');
    });

    $('.select-day').mousedown(function (e) {
        var select_year;
        var select_month;
        var select_day;
        select_year = $('.calendar .year').html();
        select_month = $('.calendar .month').html();
        select_day = $(this).html();
        $('.selectDate').val(select_year + '-' + select_month + '-' + select_day);
        $('.calendar').hide(100)
    });

};

$('.prevYear').mousedown(function (e) {
    e.preventDefault();
    now_year > 0 ? (now_year--) : false;
    calendar.render();
});

$('.prevMonth').mousedown(function (e) {
    e.preventDefault();
    if (now_month <= 1) {
        now_month = 12;
        now_year--;
    } else {
        now_month--;
    }
    calendar.render();
});

$('.nextYear').mousedown(function (e) {
    e.preventDefault();
    now_year++;
    calendar.render();
});

$('.nextMonth').mousedown(function (e) {
    e.preventDefault();
    if (now_month >= 12) {
        now_year++;
        now_month = 1;
    } else {
        now_month++;
    }
    calendar.render();
});


calendar.render();