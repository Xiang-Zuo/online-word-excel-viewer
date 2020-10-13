$(document).ready(
    $('.file-dropdown .dropdown-item').click(function () {
        $.post('/?file', {name: $(this).data('name')}, function (data) {
            let html = data.data;
            $('.file-content').html(html);
        }, 'json');
        $('.download-btn').text('Download:' + $(this).data('name'));
        $('.download-btn').removeClass('disabled');
        $('.download-btn').attr('href', $(this).data('path'));
    })
);