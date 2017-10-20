var isSP = false;
if (typeof(window.matchMedia) == "function") {
  var widthQuery = window.matchMedia("(max-width:767px)");
  if (typeof (widthQuery.matches) != "undefined" && widthQuery.matches == true) {
    isSP = true;
  }
}
/* �g�b�v�y�[�W�̃J���[�Z���ݒ� */
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
/* ��ЊT�v�g�b�v�y�[�W��googleMap�ݒ� */
function inlitialize01() {
    var latlng_01 = new google.maps.LatLng(35.5694442, 139.5599166);
    var myOptions = {
        zoom: 11,
        center: latlng_01,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map_01 = new google.maps.Map(document.getElementById("map_canvas_01"), myOptions);
    var marker = new google.maps.Marker({position : latlng_01, map : map_01});
    /* ��ЊT�v�y�[�W2���ڂ̒n�}�ݒ� */
    var map_02;
    var marker_02 = [];
    var address_02 = [ // �}�[�J�[�𗧂Ă�ܓx�E�y�x
        {lat: 35.6120478, lng: 139.627915}, // �����s���c�J��ʐ�2-2-1
        {lat: 35.5724415, lng: 139.5575634}, // �_�ސ쌧���l�s�t��V�ΐ�3-34-1
        {lat: 35.6743956, lng: 140.0425365}, // ��t����t�s�Ԍ���斋���{��6-24-31 �����{���V�e�B�v���U2F
        {lat: 38.3024135, lng: 140.8153866}, // �{�錧���s�t�撆�R��2-27 YSK�R�[�|���R��103
        {lat: 34.7609542, lng: 137.9153385}, // �É����܈�s�v�\1748-5
        {lat: 34.7572835, lng: 135.4990204}, // ���{���c�s�L�Œ�3-29 �G�b�O�r����O�]��301��
        {lat: 33.5288224, lng: 130.4783761}, // ����������s���،�1-1-41 �A�������A���،���K
        {lat: 24.7479549, lng: 125.2744878}, // ���ꌧ�{�Ó��s���n����n410-1
    ]
    latlng_02 = new google.maps.LatLng(address_02[0]['lat'], address_02[0]['lng']);
    var myOptions = {
        zoom: 5,
        center: latlng_02,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map_02 = new google.maps.Map(document.getElementById("map_canvas_02"), myOptions);
    // �}�[�J�[�𕡐��ݒu����
    for (var i = 0; i < address_02.length; i++) {
        latlng_02 = new google.maps.LatLng(address_02[i]['lat'], address_02[i]['lng']);
        marker_02[i] = new google.maps.Marker({
            position: latlng_02,
            map: map_02,
            label : (i+1).toString()
        });
    };
}
/* �O���[�v��Ѓy�[�W��googleMap�ݒ� */
var map_03;
var marker_03 = [];
var address_03 = [ // �}�[�J�[�𗧂Ă�ܓx�E�y�x
    {lat: 35.5694442, lng: 139.5599166}, // �_�ސ쌧���l�s�t��V�ΐ�3-34-1
    {lat: 37.069964, lng: 140.0265269}, // �Ȗ،��ߐ{�S�ߐ{���厚���v��1793-3
    {lat: 36.3175297, lng: 139.5150652}, // �Ȗ،������s��꒬1234
    {lat: 36.248692, lng: 140.2249071}, // ��錧�Ή��s�×ǎ��������c154
    {lat: 36.1137979, lng: 139.8321075}, // ��錧�Ⓦ�s���J2268�Ԓn
    {lat: 36.1770505, lng: 140.0483675}, // ��錧���Ύs��J862-1
    {lat: 35.5007744, lng: 140.2682818}, // ��t����t�s�΋揬�R��359-6
    {lat: 35.8683527, lng: 140.5701819}, // ��t������s���c418-1
    {lat: 35.5501802, lng: 140.297852}, // ��t����Ԕ����s�G���̐X��2-4
    {lat: 35.7037446, lng: 140.4089686}, // ��t���R���S�ŎR�����2347
    {lat: 35.3710962, lng: 140.1756224}, // ��t���s���s�c��1293
    {lat: 35.2674648, lng: 140.2362405}, // ��t���΋��S�命�쒬�㌴1090�Ԓn
    {lat: 35.1557642, lng: 140.2730604}, // ��t�����Y�s�A��1353-5
    {lat: 35.6413244, lng: 140.2218686}, // ��t�����q�s���c743
    {lat: 35.7782549, lng: 140.4690373}, // ��t������S���Ò����659�Ԓn
    {lat: 35.2376219, lng: 140.0263434}, // ��t���N�Îs���1228-1
    {lat: 35.9915403, lng: 139.2576304}, // ��ʌ����S�Ƃ����풬�啍689
    {lat: 35.6050378, lng: 139.5488626}, // �_�ސ쌧���s������e�`7-1-18
    {lat: 34.9551599, lng: 135.5122409}, // ���s�{�T���s���ʉ@���M�����[�J9�Ԓn
    {lat: 34.8706822, lng: 135.1295667}, // ���Ɍ��O�؎s�g�쒬�ēc������701
    {lat: 34.8706822, lng: 135.1295667}, // ���Ɍ��O�؎s���g�쒬�P�ˎ����{�J72-5
    {lat: 34.7693129, lng: 135.0625239}, // ���Ɍ��O�؎s�u�����O�Óc1525-8
    {lat: 34.9306384, lng: 135.0027423}, // ���Ɍ������s��O�������R1132-3
    {lat: 34.8404321, lng: 134.8987685}, // ���Ɍ�����s���Z��1225
    {lat: 24.7268788, lng: 125.279693} // ���ꌧ�{�Ó��s���n���^�ߔe1591-1
]
function inlitialize02() {
    latlng_03 = new google.maps.LatLng(address_03[0]['lat'], address_03[0]['lng']);
    var myOptions = {
        zoom: 5,
        center: latlng_03,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map_03 = new google.maps.Map(document.getElementById("map_canvas_03"), myOptions);
    // �}�[�J�[�𕡐��ݒu����
    for (var i = 0; i < address_03.length; i++) {
        latlng_03 = new google.maps.LatLng(address_03[i]['lat'], address_03[i]['lng']);
        marker_03[i] = new google.maps.Marker({
            position: latlng_03,
            map: map_03,
            label : (i+1).toString()
        });
    };
}