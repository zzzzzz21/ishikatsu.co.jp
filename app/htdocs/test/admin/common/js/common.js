/**
 * 管理ツール共通処理
 */
$(document).ready(function(){

	var height = $("#navi").height();
    var wheight = $(window).height();
	if (wheight < height) {
		$("#wrapper").css("height", height+25+'px');
	} else {
		$("#wrapper").css("height", wheight+'px');
	}
	// 左メニュー開閉
	$("#navi .name").css("cursor", "pointer");
	$("#navi .name").click(function(){
		$(this).next().slideToggle(100, function(){
			// 開閉状態を保存
			var open_conf = new Array();
			var lp = 0;
			$("#navi .name").each(function(){
				if ($(this).next().css("display") == "none") {
					open_conf[lp] = 0;
				} else {
					open_conf[lp] = 1;
				}
				lp++;
			});
			$.cookie("left_navi_open", open_conf, { expires: 7, path: '/' });

			var height = $("#navi").height();
			var wheight = $(window).height();
			if (wheight < height) {
				$("#wrapper").css("height", height+25+'px');
			} else {
				$("#wrapper").css("height", wheight+'px');
			}
		});
	});
	// datepicker
	if (typeof($("#datepicker").datepicker) == "function") {
		$("#datepicker").datepicker({
			dateFormat: 'yy-mm-dd',
			beforeShowDay: function(date) {
				var result;
				var dd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				result = [true];
				return result;
			}
		});
	}
    $("#limitDateCalendar").click(function(){
		$("#datepicker").datepicker({
			dateFormat: 'yy-mm-dd',
			beforeShowDay: function(date) {
				var result;
				var dd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				result = [true];
				return result;
			}
	    }).datepicker("show");
	});
	// datepicker
	if (typeof($("#datepicker2").datepicker) == "function") {
		$("#datepicker2").datepicker({
			dateFormat: 'yy-mm-dd',
			beforeShowDay: function(date) {
				var result;
				var dd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				result = [true];
				return result;
			}
		});
	}
    $("#limitDateCalendar2").click(function(){
		$("#datepicker2").datepicker({
			dateFormat: 'yy-mm-dd',
			beforeShowDay: function(date) {
				var result;
				var dd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				result = [true];
				return result;
			}
	    }).datepicker("show");
	});
	if (typeof($("#datepickerR").datepicker) == "function") {
		$("#datepickerR").datepicker({
			dateFormat: 'yy-mm-dd',
			beforeShowDay: function(date) {
				var result;
				var dd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				result = [true];
				return result;
			}
		});
	}
    $("#limitDateCalendarR").click(function(){
		$("#datepickerR").datepicker({
			dateFormat: 'yy-mm-dd',
			beforeShowDay: function(date) {
				var result;
				var dd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
				result = [true];
				return result;
			}
	    }).datepicker("show");
	});

	$('#global .issue-menu .user_main').hover(function(){
		// over
		$('#global .issue-menu .user_main .admin_menu').show();
		return false;
	}, 
	function(){
		$('#global .issue-menu .user_main .admin_menu').hide();
		return false;
	});

	$('.left-menu-main').hover(function(){
		// over
		$('.left-menu-sub', this).show();
		return false;
	}, 
	function(){
		$('.left-menu-sub', this).hide();
		return false;
	});

	// プログレスバー
	NProgress.start();
	setTimeout(function() { NProgress.done(); $('.fade').removeClass('out'); }, 1000);
});

// カスタム確認ダイアログ関数
function custom_confirm(msg, okfunc, cancelfunc, sub_msg) {
  if (sub_msg == undefined) {
    sub_msg = '';
  }
  var html = '<div id="alert_layer">';
  html += '<div id="alert_layer_inner"><div class="alert_layer_msg">'+msg+'</div><div class="alert_layer_msg_txt">'+sub_msg+'</div>';
  html += '<div class="alert_layer_btn"><input type="button" name="save" class="btn-save btn-del" value="キャンセル" id="alert_cancel">';
  html += '<input type="button" name="save" class="btn-save btn-del" value="実行" id="alert_ok"></div>';
  html += '</div>';
  html += '</div>';
  html = $(html);
  $("#alert_layer").remove();
  $("body").append(html);
  var height = $("#alert_layer_inner").height();
  $("#alert_layer_inner", html).css('margin-top', ($(window).height()/2)-(height/2)+"px").css('visibility', 'visible');
  $("#alert_layer_inner").slideDown("slow");

  $("#alert_ok").click(function(){
    $(this).parent().parent().parent().remove();
    okfunc();
  });

  $("#alert_cancel").click(function(){
    $(this).parent().parent().parent().remove();
    cancelfunc();
  });
}
// カスタム確認ダイアログ関数
function custom_confirm2(msg, okfunc, cancelfunc) {
  var html = '<div id="alert_layer" class="bgY">';
  html += '<div id="alert_layer_inner"><div class="alert_layer_msg">'+msg+'</div>';
  html += '<div class="alert_layer_btn"><input type="button" name="save" class="btn-save btn-del" value="キャンセル" id="alert_cancel">';
  html += '<input type="button" name="save" class="btn-save btn-del" value="実行" id="alert_ok"></div>';
  html += '</div>';
  html += '</div>';
  html = $(html);
  $("#alert_layer").remove();
  $("body").append(html);
  var height = $("#alert_layer_inner").height();
  $("#alert_layer_inner", html).css('margin-top', ($(window).height()/2)-(height/2)+"px").css('visibility', 'visible');

  $("#alert_ok").click(function(){
    $(this).parent().parent().parent().remove();
    okfunc();
  });

  $("#alert_cancel").click(function(){
    $(this).parent().parent().parent().remove();
    cancelfunc();
  });
}
// カスタム確認ダイアログ関数
function custom_alert_error(msg, okfunc) {
  var html = '<div id="alert_layer" class="bgR">';
  html += '<div id="alert_layer_inner"><div class="alert_layer_msg">エラーが発生しました。</div><div class="alert_layer_msg_txt">'+msg+'</div>';
  html += '<div class="alert_layer_btn"><input type="button" name="save" class="btn-save btn-del" value="閉じる" id="alert_ok"></div>';
  html += '</div>';
  html += '</div>';
  html = $(html);
  $("#alert_layer").remove();
  $("body").append(html);
  var height = $("#alert_layer_inner").height();


  $("#alert_ok").click(function(){
    $(this).parent().parent().parent().remove();
    return false;
  });
}

// カスタムアラート関数
function custom_alert(msg) {
  var html = '<div id="alert_layer" class="bgW">';
  html += '<div id="alert_layer_inner"><div class="alert_layer_msg">'+msg+'</div>';
  html += '<div class="alert_layer_btn"><input type="button" name="save" class="btn-save" value="閉じる" id="alert_cancel" onclick="$(this).parent().parent().parent().remove();return false;"></div>';
  html += '</div>';
  html += '</div>';
  html = $(html);
  $("#alert_layer").remove();
  $("body").append(html);
  var height = $("#alert_layer_inner").height();
  $("#alert_layer_inner", html).css('margin-top', ($(window).height()/2)-(height/2)+"px").css('visibility', 'visible');
}

