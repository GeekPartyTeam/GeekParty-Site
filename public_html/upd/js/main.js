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
    });


    function ResizeButtons() {
        var container = $("#gdp-menu-buttons");
        var w = container.parent().width();
        var len = container.children().length;
        var offset = w < 940 ? len * (80 + 10) : len * (121 + 21);
        container.css("left", w / 2 - offset / 2);
    }
});


