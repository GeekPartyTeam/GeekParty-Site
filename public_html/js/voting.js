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
        var blinkInterval;
        var $floppy = $('.Loading').show();

        //noinspection JSUnresolvedVariable
        $.post(saveVoteUrl, {
            id: projectId,
            vote: voteValue
        })
            .done(function () {
                clearInterval(blinkInterval);
                $('#ErrorMessage').hide();
                $floppy.hide();
                $('#Saved').show();
            })
            .fail(function () {
                clearInterval(blinkInterval);
                $('#ErrorMessage').show();
                $floppy.hide();
            });

        blinkInterval = setInterval(function () {
                $floppy.toggle();
            }, 100);
    });
}();

