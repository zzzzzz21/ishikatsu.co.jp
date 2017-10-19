$(document).ready(function(){
// 一覧系ページ共通js
	$("tr.list-row td").hover(
	function(){
		$(this).css("cursor", "pointer");
		$(this).parent().find("td").each(function(){
			$(this).css("background", "#eee");
		});
	},
	function(){
		$(this).css("cursor", "normal");
		$(this).parent().find("td").each(function(){
			$(this).css("background", "");
		});
	});
	$("tr.list-row td").click(function(){
		location.href = $(this).parent().find(".rowlink").attr("href");
		return false;
	});
	$(".btn-more").click(function(){
    if ($(".btn-more").val() != '隠す') {
      $(".list.inq li.hidden").removeClass('hidden').addClass('hiddened');
//    $(".btn-more").addClass('hidden');
      $(".btn-more").val('隠す');
    } else {
      $(".list.inq li.hiddened").removeClass('hiddened').addClass('hidden');
      $(".btn-more").val('全て表示');
    }
    return false;
	});
/*
    $("table.view td .img_link").live("click", function(){
        var con = $("#photoPanel");
        var panel = $("#photoPanel div.panel");
        var url = $(this).children().attr("src");

        var imgBoxTag = panel.find("div.imgBox");

        imgBoxTag.empty();
        imgBoxTag.append($('<img src="' + url + '" />'));

        con.fadeIn(300, null, function() {
            panel.fadeIn(300);
        });

        con.css("height", $("body").height() + "px");
        panel.css("margin-top", ($(window).scrollTop() + 180) + "px");
		return false;
	});
*/
	$(".close-icon").click(function(){
        $(this).parent().parent().parent().find(".form-contents").slideToggle("fast");
        return false;
	});
	// 一覧の削除
	$(".list-del").click(function(){
		custom_confirm2('削除を実行してもよろしいでしょうか？', okfunc_delete, cancelfunc_delete);
	});
    // 結果をフェードイン表示
    $(".result").fadeIn(400, function() { 
			setTimeout(function() {
	    	$('.result').fadeOut(400);
		}, 3000);
	});
	$(".result a.close-btn").click(function(){
        $(this).parent().fadeOut(2000);
        return false;
	});
	// 一覧の削除
	$(".exec-hide-show").click(function(){
		custom_confirm2('有効/無効一括更新を実行してもよろしいでしょうか？', okfunc_hideshow, cancelfunc_hideshow);
	});
});

// 一覧の削除のOK
function okfunc_delete() {
	document.list_form.action = 'delete';
	document.list_form.submit();
}

// 一覧の削除のキャンセル
function cancelfunc_delete() {
	// 現状は何も処理しない
}

// 一覧の有効/無効一括更新のOK
function okfunc_hideshow() {
	document.list_form.action = 'allhideshow';
	document.list_form.submit();
}

// 一覧の有効/無効一括更新のキャンセル
function cancelfunc_hideshow() {
	// 現状は何も処理しない
}

// ページング
function paging_link(p) {
	// 検索内容をページングフォームへ移動
	if ($('.search-hidden')) {
		$('form[name=list_sort_paging_form]').append($('.search-hidden').html());
	}
	document.list_sort_paging_form.p.value = p;
	document.list_sort_paging_form.submit();
	return false;
}

// ソート
function sort_link(key) {
	// 検索内容をページングフォームへ移動
	if ($('.search-hidden')) {
		$('form[name=list_sort_paging_form]').append($('.search-hidden').html());
	}
	var type = 'ASC';
	if (document.list_sort_paging_form.sort_key.value == key) {
		if (document.list_sort_paging_form.sort_type.value == 'ASC') {
			type = 'DESC';
		} else if (document.list_sort_paging_form.sort_type.value == 'DESC') {
			type = '';
		}
	}

	document.list_sort_paging_form.sort_key.value = key;
	document.list_sort_paging_form.sort_type.value = type;
	document.list_sort_paging_form.p.value = 1;
	document.list_sort_paging_form.submit();
	return false;
}
