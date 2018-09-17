$(function() {
    var $upvote   = $(".upvote-btn");
    var $downvote = $(".downvote-btn");

    var $upvoteIcon   = $("<i/>", {class: "bx bxs-upvote"});
    var $downvoteIcon = $("<i/>", {class: "bx bxs-downvote"});
    var $loadingIcon  = $("<i/>", {class: "bx bx-loader bx-spin"});

    var increaseNbPoint = function(memeId){
        var $nbPoint = $(".nb-point[data-meme='"+memeId+"']");
        $nbPoint.html(parseInt($nbPoint.text())+1);
    };

    var decreaseNbPoint = function(memeId){
        var $nbPoint = $(".nb-point[data-meme='"+memeId+"']");
        $nbPoint.html(parseInt($nbPoint.text())-1);
    };

    $upvote.on("click", function (e) {
        var csrfToken = $(this).data('csrf');
        var memeId = $(this).data('meme');

        // Replace icon by a loading
        $(this).html($loadingIcon);

        $.ajax({
            url: incrementAjaxUrl,
            data: { csrf_token: csrfToken, meme_id: memeId },
            type: 'POST',
            dataType: 'json',
            success: function (data, status) {
                console.info(data, status);

                // Remove active class on upvote + downvote
                var $currentUpvote = $('.upvote-btn[data-meme="'+memeId+'"]');
                var $currentDownvote = $('.downvote-btn[data-meme="'+memeId+'"]');
                $currentUpvote.removeClass("active");
                $currentDownvote.removeClass("active");

                // Restore icon
                $currentUpvote.html($upvoteIcon);

                if(data.score == 1){
                    $currentUpvote.addClass("active");
                    increaseNbPoint(memeId);
                }
                else {
                    decreaseNbPoint(memeId);
                }
            },
            error: function (data, status, error) {
                console.error(data, status, error);
            }
        });
    });

    $downvote.on("click", function (e) {
        var csrfToken = $(this).data('csrf');
        var memeId = $(this).data('meme');

        // Replace icon by a loading
        $downvote.html($loadingIcon);

        $.ajax({
            url: decrementAjaxUrl,
            data: { csrf_token: csrfToken, meme_id: memeId },
            type: 'POST',
            dataType: 'json',
            success: function (data, status) {
                console.info(data, status);

                // Remove active class on upvote + downvote
                var $currentUpvote = $('.upvote-btn[data-meme="'+memeId+'"]');
                var $currentDownvote = $('.downvote-btn[data-meme="'+memeId+'"]');
                $currentUpvote.removeClass("active");
                $currentDownvote.removeClass("active");

                // Restore icon
                $currentDownvote.html($downvoteIcon);

                if(data.score == -1){
                    $currentDownvote.addClass("active");
                    decreaseNbPoint(memeId);
                }
                else {
                    increaseNbPoint(memeId);
                }
            },
            error: function (data, status, error) {
                console.error(data, status, error);
            }
        });
    });
});
