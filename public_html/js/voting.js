!function () {
    var $vote = $('.ProjectStar'),
        $voteInner = $('.ProjectStarInner'),
        $voteInput = $('input[name=vote]'),
        voteValue = 0 + $voteInput.val(),
        changing = true;

    var $projectVoteForm = $('.ProjectVoteForm'),
        $warning = $('#ChooseVote')
        ;

    function setStars(stars) {
        $voteInner.css('width', '' + 32 * Math.floor(stars) + 'px');
    }

    setStars(voteValue);

    $vote.mouseenter(function () {
        changing = true;
    });

    $vote.mousemove(function (e) {
        if (!changing) {
            return;
        }
        var x = e.pageX - $(this).offset().left;
        setStars(1 + x / 32);
    });

    $vote.click(function (e) {
        var x = e.pageX - $(this).offset().left;
        voteValue = Math.floor(1 + x / 32);
        $voteInput.val(voteValue);
        changing = false;
        $warning.hide();
    });

    $vote.mouseleave(function () {
        setStars(voteValue);
    });

    $projectVoteForm.submit(function (e) {
        if (0 + voteValue == 0) {
            $warning.show();
            e.preventDefault();
            return false;
        }

    });
}();

