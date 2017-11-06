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
    // �w�b�_�[�ݒ�
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
    // �w�b�_�[�̃J�����g
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
    // �g�b�v�֖߂�{�^��
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
    var latlng_01 = new google.maps.LatLng(35.569439, 139.562095);
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
        {lat: 35.612044, lng: 139.630104}, // �����s���c�J��ʐ�2-2-1
        {lat: 35.569439, lng: 139.562095}, // �_�ސ쌧���l�s�t��V�ΐ�3-34-1
        {lat: 35.674387, lng: 140.044738}, // ��t����t�s�Ԍ���斋���{��6-24-31 �����{���V�e�B�v���U2F
        {lat: 38.302407, lng: 140.81754}, // �{�錧���s�t�撆�R��2-27 YSK�R�[�|���R��103
        {lat: 34.76095, lng: 137.917527}, // �É����܈�s�v�\1748-5
        {lat: 34.757291, lng: 135.50122}, // ���{���c�s�L�Œ�3-29 �G�b�O�r����O�]��301��
        {lat: 33.528803, lng: 130.480571}, // ����������s���،�1-1-41 �A�������A���،���K
        {lat: 24.74795, lng: 125.276677}, // ���ꌧ�{�Ó��s���n����n410-1
    ];
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
    }
}
/* �O���[�v��Ѓy�[�W��googleMap�ݒ� */
var map_03;
var marker_03 = [];
var address_03 = { // �}�[�J�[�𗧂Ă�ܓx�E�y�x
    '1': {lat: 35.569439, lng: 139.562095}, // �_�ސ쌧���l�s�t��V�ΐ�3-34-1
    '2': {lat: 37.06996, lng: 140.028716}, // �Ȗ،��ߐ{�S�ߐ{���厚���v��1793-3
    '3': {lat: 36.317525, lng: 139.517254}, // �Ȗ،������s��꒬1234
    '4': {lat: 36.248688, lng: 140.227096}, // ��錧�Ή��s�×ǎ��������c154
    '5': {lat: 36.117022, lng: 139.848117}, // ��錧�Ⓦ�s���J2268�Ԓn
    '6': {lat: 36.177046, lng: 140.050556}, // ��錧���Ύs��J862-1
    '7': {lat: 35.50077, lng: 140.270471}, // ��t����t�s�΋揬�R��359-6
    '8': {lat: 35.868348, lng: 140.572371}, // ��t������s���c418-1
    '9': {lat: 35.550224, lng: 140.299966}, // ��t����Ԕ����s�G���̐X��2-4
    '10': {lat: 35.7037446, lng: 140.4089686}, // ��t���R���S�ŎR�����2347
    '11': {lat: 35.371092, lng: 140.177811}, // ��t���s���s�c��1293
    '12': {lat: 35.26746, lng: 140.238429}, // ��t���΋��S�命�쒬�㌴1090�Ԓn
    '13': {lat: 35.15576, lng: 140.275249}, // ��t�����Y�s�A��1353-5
    '14': {lat: 35.64659, lng: 140.226618}, // ��t�����q�s���c743
    '15': {lat: 35.778251, lng: 140.471226}, // ��t������S���Ò����659�Ԓn
    '17': {lat: 35.98621, lng: 139.257865}, // ��ʌ����S�Ƃ����풬�啍689
    '18': {lat: 35.605034, lng: 139.551051}, // �_�ސ쌧���s������e�`7-1-18
    '19': {lat: 34.955156, lng: 135.51443}, // ���s�{�T���s���ʉ@���M�����[�J9�Ԓn
    '20': {lat: 34.870678, lng: 135.131755}, // ���Ɍ��O�؎s�g�쒬�ēc������701
    '21': {lat: 34.8706822, lng: 135.1295667}, // ���Ɍ��O�؎s���g�쒬�P�ˎ����{�J72-5
    '22': {lat: 34.769309, lng:  135.064713}, // ���Ɍ��O�؎s�u�����O�Óc1525-8
    '23': {lat: 34.930634, lng: 135.004931}, // ���Ɍ������s��O�������R1132-3
    '24': {lat: 34.840428, lng: 134.900957}, // ���Ɍ�����s���Z��1225
    '25': {lat: 24.726874, lng: 125.281882} // ���ꌧ�{�Ó��s���n���^�ߔe1591-1
}
function inlitialize02() {
    latlng_03 = new google.maps.LatLng(address_03['1']['lat'], address_03['1']['lng']);
    var myOptions = {
        zoom: 5,
        center: latlng_03,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };
    var map_03 = new google.maps.Map(document.getElementById("map_canvas_03"), myOptions);
    // �}�[�J�[�𕡐��ݒu����
    $.each(address_03, function(i, val){
        latlng_03 = new google.maps.LatLng(val['lat'], val['lng']);
        marker_03[i] = new google.maps.Marker({
            position: latlng_03,
            map: map_03,
            label : i
        });
    });
}