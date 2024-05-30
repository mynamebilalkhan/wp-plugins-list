jQuery(document).ready(function($) {
    var page = 1; // Initial page number
    var perPage = 400; // Number of plugins per page

    // Function to load plugins for a specific page
    function loadPlugins(page) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'load_plugins',
                page: page,
                per_page: perPage
            },
            success: function (response) {
                if (response.success) {
                    var data = response.data;
                    $('.plugin-list-container').html(data.plugins_html);
                    $('.pagination-container').html(data.pagination_html);
                } else {
                    console.error('Error: ' + response.data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    // Initial loading of plugins
    loadPlugins(page);

    // Click event for pagination links
    $(document).on('click', '.pagination-container a', function(e) {
        e.preventDefault();
        page = $(this).data('page');
        loadPlugins(page);
    });
});
