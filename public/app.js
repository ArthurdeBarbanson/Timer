$(document).ready(function(){
    // Contenu du script qui doit être exécuté
    let timer = 0,
        bestTimer = 0;
    $.get("/getTimer", function (data){
        timer = data.timer;
    });

    const second = 1,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

    $('#stop').click(function (){
        timer = 0;
        $.get("/restartTimer", function (data){
            bestTimer = data.timer;
        });
    });

    let x = setInterval(function() {
        timer ++;
        if (timer > bestTimer){
            bestTimer = timer;
        }
        let days = Math.floor(timer / (day)),
            hours = Math.floor((timer % (day)) / (hour)),
            minutes = Math.floor((timer % (hour)) / (minute)),
            seconds = Math.floor((timer % (minute)) / second);

        let daysBest = Math.floor(bestTimer / (day)),
            hoursBest = Math.floor((bestTimer % (day)) / (hour)),
            minutesBest = Math.floor((bestTimer % (hour)) / (minute)),
            secondsBest = Math.floor((bestTimer % (minute)) / second);

        $('#days').text(days);
        $('#hours').text(hours);
        $('#minutes').text(minutes);
        $('#seconds').text(seconds);

        $('#daysBest').text(daysBest);
        $('#hoursBest').text(hoursBest);
        $('#minutesBest').text(minutesBest);
        $('#secondsBest').text(secondsBest);

        }, 1000);
});



