$(document).ready(function(){
  $("#file-upload").change(function(){
    if ($("#file-upload").val()) {
			syori_flg_csv = 1;
			document.form_detail.target = "upload_frame_csv";
      document.form_detail.action = '/program_csv/csv_validate/';
      document.form_detail.submit();
    }
  });

	// csv validate
	var syori_flg_csv = 0;
	var tmp_obj = null;
	$(".upload_frame_csv").load(function () {
		if (syori_flg_csv) {
			document.form_detail.action = '/program_csv/validate/';
			var rtn = "";
			// エラー時
			if (this.contentDocument) {
				rtn = $(this.contentDocument.body).html();
			} else if (this.contentWindow.document) {
				rtn = $(this.contentWindow.document.body).html();
			}
			if (syori_flg_csv == 1) {
				var tmp_obj = $(rtn);
				$('#upload_table').html($('#csv_check_table', tmp_obj).html());

				if ($('#csv_check_error', '#upload_table').val() == 1) {
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

// 更新ボタンを押せるかどうか(番組紐づけに関して)
function control_update_btn_by_relate_program() {

	// 番組の入力欄がすべて入力されているかどうか
	var is_in = false;
	var is_ok = true;
	$('.input-program-title').each(function(){
		is_in = true;
		if (!$(this).val()) {
			is_ok = false;
		}
	});

	// 未登録番組があり
	if (is_in) {
		// すべて入力されていれば、更新ボタンを有効
		if (is_ok && ($('#csv_check_error', '#upload_table').val() != 1)) {
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
