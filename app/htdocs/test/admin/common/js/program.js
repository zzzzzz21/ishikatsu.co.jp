
$(document).ready(function(){
	// search
	$('.do-search').click(function(){
		search();
	});

	// input
	$('.input-category-id').change(function(){
		var tmp_obj = $('.tmp-category-id', $(this).parent());
		var new_val = $(this).val();
		$(this).parent().parent().removeClass('category-'+tmp_obj.val());
		$(this).parent().parent().addClass('category-'+new_val);
		tmp_obj.val(new_val);
		return false;
	});

	$('.do-update').click(function(){
		custom_confirm('更新してよろしいでしょうか？', function(){document.list_form.submit();}, function(){}, '');
		return false;
	});
});
function search() {
	var category_id = $('#search-category-id').val();
	$('.program-row').hide();
	if (category_id) {
		$('.category-'+category_id).show();
		re_row_color($('.category-'+category_id));
	} else {
		$('.program-row').show();
		re_row_color($('.program-row'));
	}

	return false;
}

// 色つけ直し
function re_row_color(obj) {
	var loop = 1;
	$(obj).each(function(){
		if (((loop % 4) == 1) || ((loop % 4) == 2)) {
			$('td', this).css('background-color', '#eee');
		} else if (((loop % 4) == 3) || ((loop % 4) == 0)) {
			$('td', this).css('background-color', 'white');
		}
		loop++;
	});
}