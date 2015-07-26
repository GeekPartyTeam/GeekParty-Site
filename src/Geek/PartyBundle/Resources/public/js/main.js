/**
 * Created by kee.real on 07.04.2015.
 */


"use strict";


var GDPAnimations = (function() {
    var api = {};
    api.Start = Start;

    var texts = [
        "GameDevParty - геймдев в квадрате",
        "Свобода грядет через цифру",
        "printf(“Вы управляете миром!”)",
        "Бац бац и в продакшн",
        "Цифра победит уныние",
        "Скучно? - Сделай игру",
        "Взгрустнул? - Сделай игру",
        "Разработка для души",
        "Чистый цифровой отжиг",
        "Мы жжом даже когда спим",
        "Не будь овощем, жги с нами",
        "Художники и музыканты",
        "Программисты и дизайнеры",
        "Студенты и школьники",
        "М и Ж",
        "0 и 1",
        "Всех возрастов",
        "Творят историю прямо сейчас",
        "Игра - это искусство",
        "Игра - это философия"
    ];

    var TRANSITION_DELAY = 200;
    var STAND_BY_DELAY = 5000;
    var index = 0;
    var isRunning = false;

    // ======================================================


    function Start() {
        if (isRunning) { return; }

        index = 0;
        isRunning = true;

        ShowNext();
    }


    function ShowNext() {
        if (index >= texts.length) {
            index = 0;
            texts = texts.sort(function() { return 0.5 - Math.random() });
        }

        var t = texts[index++];
        $("#gdp-animated-text")
            .hide()
            .text(t)
            .fadeIn(TRANSITION_DELAY)
            .delay(STAND_BY_DELAY)
            .fadeOut(TRANSITION_DELAY, function() {
                ShowNext();
            });
    }

    return api;
})();




$(document).ready(function() {
    ResizeButtons();
    GDPAnimations.Start();
    $(window).resize(function() {
        ResizeButtons();
        ResizeTags();
    });

    var timeoutId = setTimeout(function() {
        clearTimeout(timeoutId);
        ResizeTags();

        // activate voting
        $.each($(".gdp-active-voting"), function() { new VotingWidget(this); });
    }, 150);


    function ResizeTags() {
        var container = $(".gdp-js-center");
        var parentWidth = container.parent().width();
        var selfWidth = container.width();
        var offset = (parentWidth - selfWidth) / 2;
        container.css("left", offset);
    }


    function ResizeButtons() {
        var container = $("#gdp-menu-buttons");
        var w = container.parent().width();
        var len = container.children().length;
        var offset = w < 940 ? len * (80 + 10) : len * (121 + 21);
        container.css("left", w / 2 - offset / 2);
    }
});



function VotingWidget(parent) {
    parent = $(parent);
    var back = parent.children(".gdp-rating-back");
    var front = parent.children(".gdp-rating-front");
    var label = parent.find("span");
    var value = -1;
    var projectId = parent.attr("gdp-project-id") || -1;
    if (projectId === -1) { return; }

    var prevWidth = front.width();
    var prevLabel = label.text();

    var NUM_STARS = 5;
    var pw = parent.width();
    var STAR_STEP = pw / NUM_STARS;
    var RATIO_STEP = 1 / NUM_STARS;

    parent
        .css("cursor", "pointer")
        .on("mousemove", function(e) {
            Redraw(e.offsetX / pw);
        })
        .on("mouseleave", function(e) {
            front.css("width", prevWidth);
            label.text(prevLabel);
        })
        .on("click", function() {
            if (value < 0) { return; }
            console.log("send: " + value);

            var url = window["saveVoteUrl"] || null;
            if (url === null) { return; }

            $.post(url, {
                id: projectId,
                vote: value
            })
                .done(function (response) {
                    // todo:

                    /*clearInterval(blinkInterval);
                     $floppy.hide();
                     if (response.error) {
                     $errorMessage.text(response.error).show();
                     } else {
                     $errorMessage.hide();
                     $saved.show();
                     }
                     */
                })
                .fail(function () {
                    /*
                     clearInterval(blinkInterval);
                     $errorMessage.show();
                     $floppy.hide();
                     */
                });
        });

    function Redraw(ratio) {
        var num = Math.floor(1 + ratio / RATIO_STEP);
        num = ratio < 0.1 ? 0 : num;
        var fw = num * STAR_STEP;
        fw = fw > pw - 1 ? pw - 1 : fw;
        value = num;
        front.css("width", fw);
        label.text(num + ".0");
    }
}
