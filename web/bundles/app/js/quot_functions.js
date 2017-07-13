//$(document).ready(function() {
    console.log('holaaa');
    $('ul li a').click(function(e){				
        e.preventDefault();
        var strAncla=$(this).attr('href');
        $('body,html').stop(true,true).animate({				
            scrollTop: $(strAncla).offset().top - 118
        },1000);	
    });
//});