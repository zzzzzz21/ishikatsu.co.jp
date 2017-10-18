var isSP = false;
if (typeof(window.matchMedia) == "function") {
  var widthQuery = window.matchMedia("(max-width:767px)");
  if (typeof (widthQuery.matches) != "undefined" && widthQuery.matches == true) {
    isSP = true;
  }
}
/* トップページのカルーセル設定 */
$(document).ready(function(){
	if (isSP == true) {
		$(".carousel__image").each(function(){
			$(this).attr("src", $(this).attr("src").replace('.png', '_sp.png'));
		});
	}
    
    $('.nav__item').hover(function(){
        $(this).parent().parent().parent().parent().toggleClass('header--active');
    });
	$('a[href^="#"]').click(function(){
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top;
		$("html, body").animate({scrollTop:position}, 250, "swing");
		return false;
	});
    var toTopButton = $('.footer__button');
    toTopButton.hide();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 490) {
        toTopButton.fadeIn();
        } else {
        toTopButton.fadeOut();
        }
    });
	if (isSP == true) {
    	$('.nav__item').click(function() {
            if ($(this).hasClass('nav__item--open')) {
                $(this).removeClass('nav__item--open');
            } else {
                $('.nav__item').removeClass('nav__item--open');
                $(this).toggleClass('nav__item--open');
            }
    	});
    }
	$('.header__button').click(function() {
        $(this).toggleClass('header__button--open');
		$(this).parent().next().fadeToggle('fast');
		$('.megaDrop').removeClass('.megaDrop--open');
		$('.nav__item').removeClass('nav__item--open');
	});
});
/* 会社概要トップページのgoogleMap設定 */
function inlitialize01() {
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
        zoom: 2
      , center: latlng
      , mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map_01 = new google.maps.Map(
        document.getElementById("map_canvas_01")
      , myOptions
    );

    latlng = new google.maps.LatLng(34.6416538, 134.3369159);
    var myOptions = {
        zoom: 5
      , center: latlng
      , mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map_02 = new google.maps.Map(
        document.getElementById("map_canvas_02")
      , myOptions
    );
}
function inlitialize02() {
    latlng = new google.maps.LatLng(34.6416538, 134.3369159);
    var myOptions = {
        zoom: 5
      , center: latlng
      , mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map_03 = new google.maps.Map(
        document.getElementById("map_canvas_03")
      , myOptions
    );
}