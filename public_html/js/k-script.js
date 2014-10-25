/**
 * Created by kee.real on 25.10.14.
 */

"use strict";


$(document).ready(function() {
    $('.k-news-block').each(function() {
        $(this)
            .find('img')
            .addClass('img-responsive')
            .wrap('<div class="row"><div class="col-md-offset-2 col-md-8"></div></div>');
    });
});


