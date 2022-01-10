jQuery(function ($) {
    $('.loadmore').click(function () {

        var blog_container = jQuery('#blog-container');
        var button_data = jQuery('.loadmore');

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
                button.text('Loading...');
            },
            success: function (data) {
                if (data) {
                    button.text('More posts').prev().before(data); // insert new posts
                    loadmore_params.current_page++;
                    blog_container.append(data);
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