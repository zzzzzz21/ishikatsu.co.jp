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
    var latlng_01 = new google.maps.LatLng(35.5694442, 139.5599166);
    var myOptions = {
        zoom: 11,
        center: latlng_01,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map_01 = new google.maps.Map(document.getElementById("map_canvas_01"), myOptions);
    var marker = new google.maps.Marker({position : latlng_01, map : map_01});
    /* 会社概要ページ2枚目の地図設定 */
    var map_02;
    var marker_02 = [];
    var address_02 = [ // マーカーを立てる緯度・軽度
        {lat: 35.6120478, lng: 139.627915}, // 東京都世田谷区玉川2-2-1
        {lat: 35.5724415, lng: 139.5575634}, // 神奈川県横浜市青葉区新石川3-34-1
        {lat: 35.6743956, lng: 140.0425365}, // 千葉県千葉市花見川区幕張本郷6-24-31 幕張本郷シティプラザ2F
        {lat: 38.3024135, lng: 140.8153866}, // 宮城県仙台市青葉区中山台2-27 YSKコーポ中山台103
        {lat: 34.7609542, lng: 137.9153385}, // 静岡県袋井市久能1748-5
        {lat: 34.7572835, lng: 135.4990204}, // 大阪府吹田市広芝町3-29 エッグビル第三江坂301号
        {lat: 33.5288224, lng: 130.4783761}, // 福岡県大野城市白木原1-1-41 アルメリア白木原二階
        {lat: 24.7479549, lng: 125.2744878}, // 沖縄県宮古島市下地字上地410-1
    ]
    latlng_02 = new google.maps.LatLng(address_02[0]['lat'], address_02[0]['lng']);
    var myOptions = {
        zoom: 5,
        center: latlng_02,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map_02 = new google.maps.Map(document.getElementById("map_canvas_02"), myOptions);
    // マーカーを複数設置する
    for (var i = 0; i < address_02.length; i++) {
        latlng_02 = new google.maps.LatLng(address_02[i]['lat'], address_02[i]['lng']);
        marker_02[i] = new google.maps.Marker({
            position: latlng_02,
            map: map_02,
            label : (i+1).toString()
        });
    };
}
/* グループ会社ページのgoogleMap設定 */
var map_03;
var marker_03 = [];
var address_03 = [ // マーカーを立てる緯度・軽度
    {lat: 35.5694442, lng: 139.5599166}, // 神奈川県横浜市青葉区新石川3-34-1
    {lat: 37.069964, lng: 140.0265269}, // 栃木県那須郡那須町大字高久丙1793-3
    {lat: 36.3175297, lng: 139.5150652}, // 栃木県足利市駒場町1234
    {lat: 36.248692, lng: 140.2249071}, // 茨城県石岡市嘉良寿理清水田154
    {lat: 36.1137979, lng: 139.8321075}, // 茨城県坂東市菅谷2268番地
    {lat: 36.1770505, lng: 140.0483675}, // 茨城県つくば市作谷862-1
    {lat: 35.5007744, lng: 140.2682818}, // 千葉県千葉市緑区小山町359-6
    {lat: 35.8683527, lng: 140.5701819}, // 千葉県香取市増田418-1
    {lat: 35.5501802, lng: 140.297852}, // 千葉県大網白里市季美の森南2-4
    {lat: 35.7037446, lng: 140.4089686}, // 千葉県山武郡芝山町大台2347
    {lat: 35.3710962, lng: 140.1756224}, // 千葉県市原市田尾1293
    {lat: 35.2674648, lng: 140.2362405}, // 千葉県夷隅郡大多喜町上原1090番地
    {lat: 35.1557642, lng: 140.2730604}, // 千葉県勝浦市植野1353-5
    {lat: 35.6413244, lng: 140.2218686}, // 千葉県佐倉市内田743
    {lat: 35.7782549, lng: 140.4690373}, // 千葉県香取郡多古町大門659番地
    {lat: 35.2376219, lng: 140.0263434}, // 千葉県君津市大坂1228-1
    {lat: 35.9915403, lng: 139.2576304}, // 埼玉県比企郡ときがわ町大附689
    {lat: 35.6050378, lng: 139.5488626}, // 神奈川県川崎市多摩区枡形7-1-18
    {lat: 34.9551599, lng: 135.5122409}, // 京都府亀岡市西別院町柚原東深谷9番地
    {lat: 34.8706822, lng: 135.1295667}, // 兵庫県三木市吉川町米田字平間701
    {lat: 34.8706822, lng: 135.1295667}, // 兵庫県三木市口吉川町善祥寺字本谷72-5
    {lat: 34.7693129, lng: 135.0625239}, // 兵庫県三木市志染町三津田1525-8
    {lat: 34.9306384, lng: 135.0027423}, // 兵庫県加東市上三草字中山1132-3
    {lat: 34.8404321, lng: 134.8987685}, // 兵庫県小野市来住町1225
    {lat: 24.7268788, lng: 125.279693} // 沖縄県宮古島市下地字与那覇1591-1
]
function inlitialize02() {
    latlng_03 = new google.maps.LatLng(address_03[0]['lat'], address_03[0]['lng']);
    var myOptions = {
        zoom: 5,
        center: latlng_03,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map_03 = new google.maps.Map(document.getElementById("map_canvas_03"), myOptions);
    // マーカーを複数設置する
    for (var i = 0; i < address_03.length; i++) {
        latlng_03 = new google.maps.LatLng(address_03[i]['lat'], address_03[i]['lng']);
        marker_03[i] = new google.maps.Marker({
            position: latlng_03,
            map: map_03,
            label : (i+1).toString()
        });
    };
}