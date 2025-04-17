jQuery(document).ready(function($) {
    $('.sticker-item').on('click', function() {
        $('.sticker-item').removeClass('selected');
        $(this).addClass('selected');
        $('#selected_sticker').val($(this).data('sticker-id'));
    
        $(this).find('img').css('transform', 'scale(0.95)');
        setTimeout(function() {
            $(this).find('img').css('transform', 'scale(1)');
        }.bind(this), 200);
    });
    $('.sticker-item img').on('dragstart', function(e) {
        e.preventDefault();
    });
});