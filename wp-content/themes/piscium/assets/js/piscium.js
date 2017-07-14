$(document).ready(function () {
    
    var screenHeight = $(window).height();
    $('#mainHeader').css('height', screenHeight);

    $(window).scroll(function () {
        var screenHeight = $(window).height();
        var scroll = $(document).scrollTop();

        if (scroll >= screenHeight) {
            $('body.home header#menu-fixo').css('position', 'fixed');
            $('body.home header#menu-fixo').css('width', '100%');
            $('body.home header#menu-fixo').css('top', '0px');
            $('body.home header#menu-fixo').css('z-index', '99999');
            $('body.home header#menu-fixo').css('background', '#fff');
            $('body.home header#main-header').css('margin-bottom', '99px');
        } else {
            $('body.home header#menu-fixo').css('position', 'relative');
            $('body.home header#menu-fixo').css('top', '0px');
            $('body.home header#menu-fixo').css('z-index', '1');
            $('body.home header#menu-fixo').css('background', '#fff');
            $('body.home header#main-header').css('margin-bottom', '0px');
        }
        
        
    });
    
    
});

