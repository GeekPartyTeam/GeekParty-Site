function StarVote(voteValue, $stars, $vote)
{
    var onchange = null;
    this.change = function (callback) {
        onchange = callback;
    };

    function setStars(stars) {
        $stars.css('width', '' + 32 * Math.floor(stars) + 'px');
    }

    setStars(voteValue);

    var changing = true;

    $vote.mouseenter(function () {
        changing = true;
    })

    .mousemove(function (e) {
        if (!changing) {
            return;
        }
        var x = e.pageX - $(this).offset().left;
        setStars(1 + x / 32);
    })

    .click(function (e) {
        var x = e.pageX - $(this).offset().left;
        voteValue = Math.floor(1 + x / 32);
        onchange && onchange(voteValue);
        changing = false;
        setStars(voteValue);
    })

    .mouseleave(function () {
        setStars(voteValue);
    });
}

!function () {
    //noinspection JSUnresolvedVariable
    if ($('.ActiveVoting').length == 0 || typeof(saveVoteUrl) == 'undefined') {
        return;
    }

    var $form = $('#ProjectVoteForm'),
        voteValue = $form.data('vote'),
        projectId = $form.data('id')
    ;

    var voterWidget = new StarVote(voteValue, $('.ProjectStarInner'), $('.ProjectStar'));
    voterWidget.change(function (v) {
        voteValue = v;
        var blinkInterval,
            $floppy = $('.Loading').show(),
            $errorMessage = $('#ErrorMessage').hide(),
            $saved = $('#Saved').hide()
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

