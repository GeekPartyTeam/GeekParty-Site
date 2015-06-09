function StarVote(voteValue, $stars, $vote)
{
    var STAR_WIDTH = 23.6;

    var onchange = null;
    this.change = function (callback) {
        onchange = callback;
    };

    function setStars(stars) {
        $stars.css('width', '' + STAR_WIDTH * Math.floor(stars) + 'px');
    }

    setStars(voteValue);

    var changing = true;

    $vote.mouseenter(function () {
        changing = true;
    })

    .mousemove(function (e) {
        var x = e.pageX - $(this).offset().left;
        if (!changing) {
            return;
        }
        var y = 1 + x / STAR_WIDTH;
        setStars(y);
    })

    .click(function (e) {
        var x = e.pageX - $(this).offset().left;
        voteValue = Math.floor(1 + x / STAR_WIDTH);
        onchange && onchange(voteValue);
        changing = false;
        setStars(voteValue);
    })

    .mouseleave(function () {
        setStars(voteValue);
    });
}

!function () {
    var $form = $('#ProjectVoteForm');

    //noinspection JSUnresolvedVariable
    if ($form.length == 0 || typeof(saveVoteUrl) == 'undefined') {
        return;
    }

    var voteValue = $form.data('vote'),
        projectId = $form.data('id')
    ;

    var voterWidget = new StarVote(voteValue,
        $form.find('.gdp-rating-front'),
        $form.find('.gdp-rating-back')
    );

    voterWidget.change(function (v) {
        voteValue = v;
        var blinkInterval,
            $floppy = $('.Loading').show(),
            $errorMessage = $('#ErrorMessage').hide(),
            $saved = $('#SavedMessage').hide()
        ;

        //noinspection JSUnresolvedVariable
        $.post(saveVoteUrl, {
            id: projectId,
            vote: voteValue
        })
            .done(function (response) {
                clearInterval(blinkInterval);
                $floppy.hide();

                if (response.error) {
                    $errorMessage.text(response.error)
                        .show();
                } else {
                    $errorMessage.hide();
                    $saved.show();
                }
            })
            .fail(function () {
                clearInterval(blinkInterval);
                $errorMessage.show();
                $floppy.hide();
            });

        blinkInterval = setInterval(function () {
                $floppy.toggle();
            }, 100);
    });
}();

