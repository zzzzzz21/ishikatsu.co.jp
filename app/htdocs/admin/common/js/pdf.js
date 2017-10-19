
$(document).ready(function(){
	$("#file-upload").click(function(){
        $('#file-uploader').click();
        return false;
	});
	$("#file-uploader").change(function(){
	
		var file = this.files[0];        // files配列にファイルが入っています
		var fileTypes = file.name.split(".");
		var len = fileTypes.length;
		if (len === 0) {
	    $('.detail-open-save').addClass('btn-disabled');
	    $('.detail-open-save').attr('disabled', 'disabled');
			custom_alert_error('pdfファイルを選択してください。');
			return false;
		}
		var ext = fileTypes[len - 1];
		if (ext.toLowerCase() != 'pdf') {
	    $('.detail-open-save').addClass('btn-disabled');
	    $('.detail-open-save').attr('disabled', 'disabled');
			custom_alert_error('pdfファイルを選択してください。');
		  return false;
		}

    $('.detail-open-save').removeClass('btn-disabled');
    $('.detail-open-save').removeAttr('disabled');
    return false;
	});
});
