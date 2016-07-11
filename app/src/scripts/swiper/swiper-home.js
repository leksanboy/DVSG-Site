$(document).ready(function() {
    var mySwiper = new Swiper("#swiper-container", {
        pagination: "#pagination",
        direction: 'vertical',
        loop: true
    });
    $('.swiper-arrow-left').on('click', function(e) {
        e.preventDefault();
        mySwiper.swipePrev();
    });
    $('.swiper-arrow-right').on('click', function(e) {
        e.preventDefault();
        mySwiper.swipeNext();
    });

    if (document.URL.length <= 36) { // Cambiar por el numero de caracteres que tenga el dominio (15)
        setInterval(function() {
            mySwiper.swipeNext();
            // }, 5000);
        }, 7000);
    }
});