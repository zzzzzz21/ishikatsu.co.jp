$(document).ready(function(){
	// 更新時エラーチェック
	$(".update-order").click(function(){
		var is_err = false;
//		$('.order_num_value').each(function(){
//			if ($(this).val().match(/[^0-9]/g)) {
//				custom_alert_error('並び順には数値を入力してください。');
//				is_err = true;
//				return false;
//			}
//		});

		if ( ! is_err) {
			custom_confirm('更新してもよろしいでしょうか？', function(){ document.list_form.submit(); }, cancelfunc_ordup);
		}
		return false;
	});

	// first
	$('.row-first a').click(function(){
		var obj = $(this).parent().parent();
		$(obj).insertBefore($('.order-row:first'));
		// 表示非表示制御
		control_up_down_link();
		return false;
	});

	// up
	$('.row-up a').click(function(){
		var obj = $(this).parent().parent();
		$(obj).insertBefore($(obj).prev());
		// 表示非表示制御
		control_up_down_link();
		return false;
	});

	// down
	$('.row-down a').click(function(){
		var obj = $(this).parent().parent();
		$(obj).insertAfter($(obj).next());
		// 表示非表示制御
		control_up_down_link();
		return false;
	});

	// last
	$('.row-last a').click(function(){
		var obj = $(this).parent().parent();
		$(obj).insertAfter($('.order-row:last'));
		// 表示非表示制御
		control_up_down_link();
		return false;
	});

});

function cancelfunc_ordup() {
	return false;
}

// 上へ下への表示制御
function control_up_down_link() {
	var loop = 1;

	// 一旦、全表示
	$('.row-last a').show();
	$('.row-down a').show();
	$('.row-up a').show();
	$('.row-first a').show();

	$('.order-row').each(function(){
		// 先頭
		if (loop == 1) {
			$('.row-up a', $(this)).css('display', 'none');
			$('.row-first a', $(this)).css('display', 'none');
		}

		// 最後
		if ($('.order-row').length == loop) {
			$('.row-down a', $(this)).css('display', 'none');
			$('.row-last a', $(this)).css('display', 'none');
		}
		loop = loop + 1;
	});
}
