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
    var Pages = {
        "main": "main.html",
        "works": "works.html",
        "works-single": "works-single.html",
        "news-single": "news-single.html"
    };

    window.onkeyup = OnKey;
    window.ShowPage = ShowPage;
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


    function OnKey(e) {
        console.log(e.keyCode);
        switch (e.keyCode) {
            // z = 90
            // x = 88
            // c = 67
            // v = 86
            // b = 66
            // n = 78
            //case 90: ShowPage("main"); break;
            //case 88: ShowPage("works"); break;
            //case 67: ShowPage("news-single"); break;
            //case 86: ShowPage("works-single"); break;

        }
    }


    /*function ShowPage(name, a) {
        var fileName = Pages[name];
        if (fileName === undefined) { return; }

        fileName += "?" + Date.now();
        $("#dynamic-content").load(fileName, function() {});

        if (a) {
            $("#gdp-menu-buttons").children().removeClass("gdp-menu-button-active");
            $(a).addClass("gdp-menu-button-active");
        }
    }*/

    //ShowPage("works-single");
});


