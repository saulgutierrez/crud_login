$(document).ready(function(){
    $('.ir-arriba').click(function(){
        $('.card-container').animate({
            scrollTop: '0px'
        }, 300);
    });

    $('.card-container').scroll(function() {
        if( $(this).scrollTop() > 0 ){
            $('.ir-arriba').slideDown(300);
        } else {
            $('.ir-arriba').slideUp(300);
        }
    });
});