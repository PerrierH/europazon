requirejs(['jquery'], function( $ ) {
$(window).scroll(function () {
//variables
var getHeaderHeight = $('.page-header').innerHeight();
var scroll = $(window).scrollTop();
if(scroll >= getHeaderHeight) {
$(".page-header").addClass("sticky active");
}
else
{
$(".page-header").removeClass("sticky active");
}
});
});
