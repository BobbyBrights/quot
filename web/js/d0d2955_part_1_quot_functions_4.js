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

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}