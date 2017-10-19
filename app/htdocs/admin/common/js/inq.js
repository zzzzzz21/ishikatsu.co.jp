
$(document).ready(function(){
	// ステータス更新
	$('.update-status').click(function(){
		custom_confirm('ステータスを更新してもよろしいでしょうか？', okfunc_update_statsu, cancelfunc_update_statsu);
		return false;
	});

});

// OK
function okfunc_update_statsu() {
	var tmp = document.form_detail.action;
	tmp = tmp.replace(/\/validate\/?/, '/update_status/');
	document.form_detail.action = tmp;
	document.form_detail.target = "";
	isChange = false;
	document.form_detail.submit();
}

// キャンセル
function cancelfunc_update_statsu() {
	// 現状は何も処理しない
}
