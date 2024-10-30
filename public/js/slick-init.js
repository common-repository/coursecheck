jQuery(document).ready(function(){
    jQuery('.cchk-slick').slick({
        autoplay: true,
        //autoplaySpeed: 5000,
        dots: true,
        slidesToShow: 2,
        slidesToScroll: 2,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                slidesToShow: 2,
                slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                slidesToShow: 1,
                slidesToScroll: 1
                }
            }
        ]
    });
});