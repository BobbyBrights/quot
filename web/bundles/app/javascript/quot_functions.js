$('.main-menu-link').click(function(e){
  	console.log("dude");
    e.preventDefault();
    var strAncla=$(this).attr('href');
    $('body,html').stop(true,true).animate({
        scrollTop: $(strAncla).offset().top - 118
    },1000);
});

$('.shirt-block.bk-ancla').click(function(e){
    e.preventDefault();
    var strAncla=$(this).attr('href');
    $('body,html').stop(true,true).animate({
        scrollTop: $(strAncla).offset().top - 118
    },1000);
});
