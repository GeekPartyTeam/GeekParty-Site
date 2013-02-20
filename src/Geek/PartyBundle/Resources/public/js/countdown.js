$(document).ready( function() {
    var targetData = new Date();
    targetData.setFullYear(2013,1,23);
    targetData.setHours(12,1);

    var setTimer =  function(){

        var now = new Date(),
            target = targetData,
            remaining = target.getTime() - now.getTime(),
            milli = 864e5,
            a_days = remaining / milli,
            days = Math.floor(a_days),
            a_hours = (a_days - days) * 24,
            hours = Math.floor(a_hours),
            minutes = Math.floor((a_hours - hours) * 60),
            a_minutes = (a_hours - hours) * 60,
            seconds = Math.floor((a_minutes - minutes) * 60);

        var daysContainer = $('#days');
        //document.getElementById('days').innerHTML = (days < 10) ? '0' + days : days;
        $('#days').html((days < 10) ? '0' + days : days);
        document.getElementById('hours').innerHTML = (hours < 10) ? '0' + hours : hours;
        document.getElementById('minutes').innerHTML =  (minutes < 10) ? '0' + minutes : minutes;
    };

    setTimer();

    timer = window.setInterval(setTimer, 1000);


} );
