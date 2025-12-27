jQuery(function ($) {
    $('.loadmore').click(function () {

        var blog_container = jQuery('#blog-container');
        var button_data = jQuery('.loadmore');
        var posts_loop = jQuery('#posts-loop');

        var button = $(this),
                data = {
                    'action': 'loadmore',
                    'query': loadmore_params.posts,
                    'page': loadmore_params.current_page
                };

        $.ajax({
            url: loadmore_params.ajaxurl, // AJAX handler
            data: data,
            type: 'POST',
            beforeSend: function (xhr) {
                button.text('Ładowanie...');
            },
            success: function (data) {
                if (data) {
                    button.text('Załaduj więcej').prev().before(data); // insert new posts
                    loadmore_params.current_page++;
                    posts_loop.append(data);
                    if (loadmore_params.current_page == button_data.data('max'))
                    {
                        button.remove();
                    }
                } else {
                    button.remove(); // if no data, remove the button as well
                }
            }
        });
    });
});