$(function() {
    if (window.targetDate) {
        var arr = window.targetDate.split(/[- :]/)
            , targetDate = new Date(arr[0], arr[1]-1, arr[2], arr[3], arr[4], arr[5])
            , timer
            ;

        arr = window.serverNow.split(/[- :]/);
        var serverNow = new Date(arr[0], arr[1]-1, arr[2], arr[3], arr[4], arr[5]);

        var difference = targetDate - serverNow;

        if (difference >= 0) {
            var setTimer =  function(){
                var remaining = difference,
                    milli = 864e5,
                    a_days = remaining / milli,
                    days = Math.floor(a_days),
                    a_hours = (a_days - days) * 24,
                    hours = Math.floor(a_hours),
                    minutes = Math.floor((a_hours - hours) * 60),
                    a_minutes = (a_hours - hours) * 60,
                    seconds = Math.floor((a_minutes - minutes) * 60);

                if (remaining < 0) {
                    $('#timer').hide();
                    clearInterval(timer);
                    window.location.reload();
                    return;
                }

                var timeNow = "" +
                    ((days < 10) ? '0' + days : days) + ":" +
                    ((hours < 10) ? '0' + hours : hours) + ":" +
                    ((minutes < 10) ? '0' + minutes : minutes);

                $("#big-timer").html(timeNow);

                /*$('#days').html((days < 10) ? '0' + days : days);
                $('#hours').html((hours < 10) ? '0' + hours : hours);
                $('#minutes').html((minutes < 10) ? '0' + minutes : minutes);*/
            };

            setTimer();

            timer = window.setInterval(setTimer, 1000);
        }
    }
});
