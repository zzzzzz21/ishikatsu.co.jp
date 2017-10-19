$(document).ready(function(){
  $("#file-select").click(function(){
    $("#file-upload").click();
    return false;
  });
  $("#file-upload").change(function(){
    if ($("#file-upload").val()) {
			syori_flg_csv = 1;
			document.form_detail.target = "upload_frame_csv";
      document.form_detail.action = '/timetable/csv_validate/';
      document.form_detail.submit();
    }
  });

	// csv validate
	var syori_flg_csv = 0;
	var tmp_obj = null;
	$(".upload_frame_csv").load(function () {
		if (syori_flg_csv) {
			document.form_detail.action = '/timetable/validate/';
			var rtn = "";
			// エラー時
			if (this.contentDocument) {
				rtn = $(this.contentDocument.body).html();
			} else if (this.contentWindow.document) {
				rtn = $(this.contentWindow.document.body).html();
			}
			if (syori_flg_csv == 1) {
				var tmp_obj = $(rtn);
				$('#upload_msg').html($('#csv_check_msg', tmp_obj).html());
				$('#upload_table').html($('#csv_check_table', tmp_obj).html());

				if ($('#csv_check_error', '#upload_msg').val() == 1) {
						$('#upload-do').removeClass('btn-disabled');
						$('#upload-do').addClass('btn-disabled');
						$('#upload-do').attr('disabled', 'disabled');
				} else {
						$('#upload-do').removeClass('btn-disabled');
						$('#upload-do').removeAttr('disabled');
				}
			} else if (syori_flg_csv == 2) {
				$(tmp_obj).html(rtn);
			}

			// 更新ボタン制御
			control_update_btn_by_relate_program()

			return false;
		}
	});

	$('.re-upload').click(function(){
		$("#file-upload").change();
	});

	// 未知の番組ブロック系
	// 再検証ボタン
	$('.re-upload').click(function(){
		$("#file-upload").change();
	});

	// 過去番組と紐づけるリンク
	$('#upload_msg').on('click', '.program-relate', function() {
		var relate_block_obj = $('.program-relate-block', $(this).parent().parent());
		if ($(relate_block_obj).css('display') == 'none') {
		  $(relate_block_obj).show();
			$('.near-alert-block', $(this).parent().parent()).hide();
		} else {
		  $(relate_block_obj).hide();
			$('.near-alert-block', $(this).parent().parent()).show();
		}
		return false;
	});

	// 番組名検索
	$('#upload_msg').on('click', '.search-box-block-btn', function() {
	  var word = $('.search-box-block-text', $(this).parent()).val();

    if (word) {
			syori_flg_csv = 2;
			document.form_detail.target = "upload_frame_csv";
      document.form_detail.action = '/timetable/program_search/';
			document.form_detail.search_word.value = word;
      document.form_detail.submit();
			tmp_obj = $('.search-result-block', $(this).parent().parent());
    } else {
			custom_alert_error('検索文字列を入力してください。');
		}
		return false;
	});

	// 紐づけ(検索)
	$('#upload_msg').on('click', '.do-relate', function() {
		var top_block = $(this).parent().parent().parent().parent().parent().parent().parent().parent();
		relate_program(this, top_block)
		return false;
	});
	
	// 紐づけ(似た番組)
	$('#upload_msg').on('click', '.do-relate-near', function() {
		var top_block = $(this).parent().parent().parent().parent().parent();
		relate_program(this, top_block)
		return false;
	});
	
	// 解除
	$('#upload_msg').on('click', '.release-relate', function() {
		var top_block = $(this).parent().parent().parent();

		$('.program-relate-block .search-box-block-text', top_block).val('');
		$('.program-relate-block .search-result-block', top_block).html('');

		$('.non-program-relate-hidden', top_block).val('');

		$('.related-block', top_block).hide();
		$('.top-link-block', top_block).show();

		$('.near-alert-block', top_block).show();

		// 更新ボタン制御
		control_update_btn_by_relate_program()
		return false;
	});

	$('#upload_table').on('change', '.input-program-category-id', function() {
		control_update_btn_by_relate_program();
		return false;
	});
	$('#upload_table').on('change', '.input-program-title', function() {
		control_update_btn_by_relate_program();
		return false;
	});
  // 番組説明の編集
  $(document).on('click', '.open-edit', function(){
    var cls = $(this).attr("class").split(" ")[1].replace("t", "");
    var text = $(this).parent().parent().find('textarea').val();
    $('#description-edit, #description-edit-layer').remove();
    var float_layer = '<div style="position:fixed;top:0;left:0;bottom:0;right:0;background:rgba(0,0,0,0.7);z-index:99998;" id="description-edit-layer"></div>';
    var float = '<div style="position: fixed;top:50%;left:50%;margin: -200px 0 0 -220px;z-index:99999;padding: 20px;background:#fff;" id="description-edit"><textarea style="width:420px;height: 300px;">' + text + '</textarea><div class="align-c"><input type="button" name="open" class="btn-open submit s'+cls+'" value="反映">　<input type="button" name="open" class="btn-re-upload cancel" value="キャンセル"></div></div>';
    $("body").append(float_layer).append(float);
    $("#description-edit-layer, #description-edit .cancel").click(function(){
      $('#description-edit, #description-edit-layer').remove();
    });
    $("#description-edit .submit").click(function(){
      var cls = $(this).attr("class").split(" ")[2].replace("s", "");
      var text = $(this).parent().parent().find('textarea').val();
      $(".i"+cls).val(text);
      $('#description-edit, #description-edit-layer').remove();
    });
    return false;
  });
  $(document).on('click', '.program-list-all', function() {
    $(this).parent().parent().find('.program-list-more').show();
    $(this).parent().hide();
    return false;
  });
  $(document).on('click', '.program-list-close', function() {
    $(this).parent().parent().find('.program-list-default').show();
    $(this).parent().hide();
    return false;
  });
});

// 紐づけ
function relate_program(this_obj, top_block) {
		var sub_category_id = $(this_obj).attr('data-id');
		var category_name = $('.link-block a', $(this_obj).parent().parent()).text();
		$('.non-program-relate-hidden', top_block).val(sub_category_id);
		$('.top-link-block', top_block).hide();
		$('.program-relate-block', top_block).hide();

		$('.related-block a.release-relate', top_block).text(category_name);
		$('.related-block', top_block).show();

		// 更新ボタン制御
		control_update_btn_by_relate_program()
		return false;
}



// 更新ボタンを押せるかどうか(番組紐づけに関して)
function control_update_btn_by_relate_program() {
//	var is_in = false;
//	var is_all = true;
//	$('.non-program-relate-hidden').each(function(){
//		is_in = true;
//		if (!$(this).val()) {
//			is_all = false;
//		}
//	});
//
//	// 未登録番組があり
//	if (is_in) {
//		// すべて関連づけていれば、更新ボタンを有効
//		if (is_all) {
//			$('#upload-do').removeClass('btn-disabled');
//			$('#upload-do').removeAttr('disabled');
//		// すべて関連づいていない場合は、更新ボタン無効
//		} else {
//			$('#upload-do').removeClass('btn-disabled');
//			$('#upload-do').addClass('btn-disabled');
//			$('#upload-do').attr('disabled', 'disabled');
//		}
//	}

	// 番組の入力欄がすべて入力されているかどうか
	var is_in = false;
	var is_ok = true;
	$('.input-program-category-id').each(function(){
		is_in = true;
		if (!$(this).val()) {
			is_ok = false;
		}
	});
	$('.input-program-title').each(function(){
		is_in = true;
		if (!$(this).val()) {
			is_ok = false;
		}
	});

	// 未登録番組があり
	if (is_in) {
		// すべて入力されていれば、更新ボタンを有効
		if (is_ok && ($('#csv_check_error', '#upload_msg').val() != 1)) {
			$('#upload-do').removeClass('btn-disabled');
			$('#upload-do').removeAttr('disabled');
		// 更新ボタン無効
		} else {
			$('#upload-do').removeClass('btn-disabled');
			$('#upload-do').addClass('btn-disabled');
			$('#upload-do').attr('disabled', 'disabled');
		}
	}
	return false;
}
