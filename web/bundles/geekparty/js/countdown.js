$(function() {
    var targetData = new Date(2013,1,23,12,1);

    var setTimer =  function(){
        var remaining = new Date - targetData,
            milli = 864e5,
            a_days = remaining / milli,
            days = Math.floor(a_days),
            a_hours = (a_days - days) * 24,
            hours = Math.floor(a_hours),
            minutes = Math.floor((a_hours - hours) * 60),
            a_minutes = (a_hours - hours) * 60,
            seconds = Math.floor((a_minutes - minutes) * 60);

        $('#days').html((days < 10) ? '0' + days : days);
        $('#hours').html((hours < 10) ? '0' + hours : hours);
        $('#minutes')html((minutes < 10) ? '0' + minutes : minutes);
    };

    setTimer();

    timer = window.setInterval(setTimer, 1000);
})
