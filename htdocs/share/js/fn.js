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
    // ヘッダー設定
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
    // ヘッダーのカレント
    var url = location.href;
    $('.nav__item .nav__link').removeClass('nav__link--current');
    if (url.match(/company/)) {
        $('#nav__company').addClass('nav__link--current');
    }
    if (url.match(/technology/)) {
        $('#nav__technology').addClass('nav__link--current');
    }
    if (url.match(/works/)) {
        $('#nav__works').addClass('nav__link--current');
    }
    if (url.match(/management/)) {
        $('#nav__management').addClass('nav__link--current');
    }
    if (url.match(/attempt/)) {
        $('#nav__attempt').addClass('nav__link--current');
    }
    if (url.match(/recruit/)) {
        $('#nav__recruit').addClass('nav__link--current');
    }
    // トップへ戻るボタン
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
    var latlng_01 = new google.maps.LatLng(35.569439, 139.562095);
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
        {lat: 35.612044, lng: 139.630104}, // 東京都世田谷区玉川2-2-1
        {lat: 35.569439, lng: 139.562095}, // 神奈川県横浜市青葉区新石川3-34-1
        {lat: 35.674387, lng: 140.044738}, // 千葉県千葉市花見川区幕張本郷6-24-31 幕張本郷シティプラザ2F
        {lat: 38.302407, lng: 140.81754}, // 宮城県仙台市青葉区中山台2-27 YSKコーポ中山台103
        {lat: 34.76095, lng: 137.917527}, // 静岡県袋井市久能1748-5
        {lat: 34.757291, lng: 135.50122}, // 大阪府吹田市広芝町3-29 エッグビル第三江坂301号
        {lat: 33.528803, lng: 130.480571}, // 福岡県大野城市白木原1-1-41 アルメリア白木原二階
        {lat: 24.74795, lng: 125.276677}, // 沖縄県宮古島市下地字上地410-1
    ];
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
    }
}
/* グループ会社ページのgoogleMap設定 */
var map_03;
var marker_03 = [];
var address_03 = { // マーカーを立てる緯度・軽度
    '1': {lat: 35.569439, lng: 139.562095}, // 神奈川県横浜市青葉区新石川3-34-1
    '2': {lat: 37.06996, lng: 140.028716}, // 栃木県那須郡那須町大字高久丙1793-3
    '3': {lat: 36.317525, lng: 139.517254}, // 栃木県足利市駒場町1234
    '4': {lat: 36.248688, lng: 140.227096}, // 茨城県石岡市嘉良寿理清水田154
    '5': {lat: 36.117022, lng: 139.848117}, // 茨城県坂東市菅谷2268番地
    '6': {lat: 36.177046, lng: 140.050556}, // 茨城県つくば市作谷862-1
    '7': {lat: 35.50077, lng: 140.270471}, // 千葉県千葉市緑区小山町359-6
    '8': {lat: 35.868348, lng: 140.572371}, // 千葉県香取市増田418-1
    '9': {lat: 35.550224, lng: 140.299966}, // 千葉県大網白里市季美の森南2-4
    '10': {lat: 35.7037446, lng: 140.4089686}, // 千葉県山武郡芝山町大台2347
    '11': {lat: 35.371092, lng: 140.177811}, // 千葉県市原市田尾1293
    '12': {lat: 35.26746, lng: 140.238429}, // 千葉県夷隅郡大多喜町上原1090番地
    '13': {lat: 35.15576, lng: 140.275249}, // 千葉県勝浦市植野1353-5
    '14': {lat: 35.64659, lng: 140.226618}, // 千葉県佐倉市内田743
    '15': {lat: 35.778251, lng: 140.471226}, // 千葉県香取郡多古町大門659番地
    '17': {lat: 35.98621, lng: 139.257865}, // 埼玉県比企郡ときがわ町大附689
    '18': {lat: 35.605034, lng: 139.551051}, // 神奈川県川崎市多摩区枡形7-1-18
    '19': {lat: 34.955156, lng: 135.51443}, // 京都府亀岡市西別院町柚原東深谷9番地
    '20': {lat: 34.870678, lng: 135.131755}, // 兵庫県三木市吉川町米田字平間701
    '21': {lat: 34.8706822, lng: 135.1295667}, // 兵庫県三木市口吉川町善祥寺字本谷72-5
    '22': {lat: 34.769309, lng:  135.064713}, // 兵庫県三木市志染町三津田1525-8
    '23': {lat: 34.930634, lng: 135.004931}, // 兵庫県加東市上三草字中山1132-3
    '24': {lat: 34.840428, lng: 134.900957}, // 兵庫県小野市来住町1225
    '25': {lat: 24.726874, lng: 125.281882} // 沖縄県宮古島市下地字与那覇1591-1
}
function inlitialize02() {
    latlng_03 = new google.maps.LatLng(address_03['1']['lat'], address_03['1']['lng']);
    var myOptions = {
        zoom: 5,
        center: latlng_03,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map_03 = new google.maps.Map(document.getElementById("map_canvas_03"), myOptions);
    // マーカーを複数設置する
    $.each(address_03, function(i, val){
        latlng_03 = new google.maps.LatLng(val['lat'], val['lng']);
        marker_03[i] = new google.maps.Marker({
            position: latlng_03,
            map: map_03,
            label : i
        });
    });
}