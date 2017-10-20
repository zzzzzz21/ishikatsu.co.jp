
var isChange = false;
$(document).ready(function(){
	$(".row .cancel").click(function(){
        $(this).parent().hide();
        return false;
	});
	$(".sync-cancel-icon").click(function(){
        $(this).parent().parent().children().children().hide();
        return false;
	});
	$("#sync-all-cancel").click(function(){
        $(".row-tbl.target td span, .row-tbl.target td a").hide();
        return false;
	});

	var preview_window;

	// validate
	var syori_flg = 0;
	$(".upload_frame").load(function () {
		if (syori_flg) {
			var rtn = "";
			// エラー時
			if (this.contentDocument) {
				rtn = $(this.contentDocument.body).html();
				if (rtn != "") {
					if (document.form_detail.preview_flg && document.form_detail.preview_flg.value) {
						preview_window.close();
					}
					custom_alert_error($(this.contentDocument.body).html());
					syori_flg = 0;
					return false;
				}
			} else if (this.contentWindow.document) {
				rtn = $(this.contentWindow.document.body).html();
				if (rtn != "") {
					if (document.form_detail.preview_flg && document.form_detail.preview_flg.value) {
						preview_window.close();
					}
					custom_alert_error($(this.contentWindow.document.body).html());
					syori_flg = 0;
					return false;
				}
			}

			// エラーなし
			syori_flg = 0;

			// preview
			if (document.form_detail.preview_flg && document.form_detail.preview_flg.value) {
				document.form_detail.preview_flg.value = "";
				document.form_detail.target = 'preview_popup';
				var tmp_action = document.form_detail.action;
				document.form_detail.action = 'http://'+document.domain+'/preview/';
				document.form_detail.submit();

				document.form_detail.action = tmp_action;
				document.form_detail.target = '';
      } else {
				custom_confirm('更新してもよろしいでしょうか？', okfunc_save, cancelfunc_save);
			}
			return false;
		}
	});

	// プレビューボタン
	$('.preview-action').click(function(){
		syori_flg = 1;
		document.form_detail.target = "upload_frame";
		document.form_detail.preview_flg.value = 1;
		document.form_detail.submit();
		preview_window = window.open('', 'preview_popup');
		return false;
	});

	// 保存ボタン
	$('.detail-save').click(function(){
		syori_flg = 1;
		document.form_detail.target = "upload_frame";
		document.form_detail.submit();
		return false;
	});

	// 保存して公開予約ボタン
	$('.detail-open-save').click(function(){
		syori_flg = 1;
		document.form_detail.target = "upload_frame";
		document.form_detail.submit();
		return false;
	});

	// choiceの行追加削除処理
	$('.choice-add-row').click(function(){
		// 内容を空にして、キャンセルボタンを有効にする
		$('.choice-add-row-block').before('<div class="block-l">'+$('.choice-base-row').html()+'</div>');
		var tmp = $('.choice-add-row-block').prev();
		$('.choice-inputs', tmp).val('');
		$('.choice-no', tmp).html($('.choice-no').length);
		$('.img-input-src', tmp).html('');
		$('.img-input-src', tmp).hide();
		$('.choice-delete-row', tmp).show();
		return false;
	});
	$(document).on('click', '.choice-delete-row' , function(){
		$(this).parent().parent().remove();
		// no 振りなおし
		var i = 1;
		$('.choice-no').each(function(){
			$(this).html(i);
			i = i + 1;
		});
		return false;
	});

	// 削除
	$('.detail-delete').click(function(){
		custom_confirm2('削除してもよろしいでしょうか？', okfunc_del, cancelfunc_del);
		return false;
	});

	// 無効
	$('.detail-hide').click(function(){
		custom_confirm2('無効にしてもよろしいでしょうか？', okfunc_hide, cancelfunc_hide);
		return false;
	});

	// 有効
	$('.detail-show').click(function(){
		custom_confirm2('有効にしてもよろしいでしょうか？', okfunc_show, cancelfunc_show);
		return false;
	});

	// 無効
	$('.detail-hide2').click(function(){
		custom_confirm2('非表示にしてもよろしいでしょうか？', okfunc_hide, cancelfunc_hide);
		return false;
	});

	// 有効
	$('.detail-show2').click(function(){
		custom_confirm2('表示してもよろしいでしょうか？', okfunc_show, cancelfunc_show);
		return false;
	});

	// 画像参照
	$(document).on('click', '.image-upload', function(){
		$('.image-uploader', $(this).parent()).click();
	});

	// 画像参照
	$(document).on('change', '.image-uploader', function(){
		// 選択されたファイルがない場合は何もせずにreturn
		if (!this.files.length) {
			return;
		}

		var file = this.files[0],        // files配列にファイルが入っています
		fileReader = new FileReader();   // ファイルを読み込むFileReaderオブジェクト

		var src_img = $('.img-input-src', $(this).parent().parent());

		if (!file.type.match('image.*')) {
			src_img.html('');
		  return;
		}

		var src_img_del = $('.image-upload-del', $(this).parent().parent());
		var src_img_hidden_del = $('.image-uploader-del', $(this).parent().parent());

		// 読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
		fileReader.onload = function(event) {
			// 読み込んだデータをimgに設定
			src_img.html('');
			src_img.html('<img  class="image-target-display" src="'+event.target.result+'">');
			src_img.show();

			src_img_del.show();
			src_img_hidden_del.val(0);
		};

		// 画像読み込み
		fileReader.readAsDataURL(file);
		return false;
	});

	$('.image-upload-del').click(function(){
		$('.image-uploader-del', $(this).parent()).val(1);
		$('.img-input-src', $(this).parent()).html('');
	});

	$('.btn-disabled').each(function(){
		$(this).attr('disabled', 'disabled');
	});

	$('.image-target-display').on('click', function(){
		var h = $(this).attr('src');
		window.open(h);
		return false;
	});

});

// 更新OK
function okfunc_save() {
	var tmp = document.form_detail.action;
	tmp = tmp.replace(/\/validate\/?/, '/complete/');
	document.form_detail.action = tmp;
	document.form_detail.target = "";
	isChange = false;
	document.form_detail.submit();
}

// 更新のキャンセル
function cancelfunc_save() {
	// 現状は何も処理しない
}

// 削除OK
function okfunc_del() {
	var tmp = document.form_detail.action;
	tmp = tmp.replace(/\/validate\/?/, '/delete/');
	document.form_detail.action = tmp;
	document.form_detail.target = "";
	isChange = false;
	document.form_detail.submit();
}

// 削除のキャンセル
function cancelfunc_del() {
	// 現状は何も処理しない
}

// 削除OK
function okfunc_hide() {
	var tmp = document.form_detail.action;
	tmp = tmp.replace(/\/validate\/?/, '/hide/');
	document.form_detail.action = tmp;
	document.form_detail.target = "";
	isChange = false;
	document.form_detail.submit();
}

// 削除のキャンセル
function cancelfunc_hide() {
	// 現状は何も処理しない
}

// 有効OK
function okfunc_show() {
	var tmp = document.form_detail.action;
	tmp = tmp.replace(/\/validate\/?/, '/show/');
	document.form_detail.action = tmp;
	document.form_detail.target = "";
	isChange = false;
	document.form_detail.submit();
}

// 有効のキャンセル
function cancelfunc_show() {
	// 現状は何も処理しない
}
