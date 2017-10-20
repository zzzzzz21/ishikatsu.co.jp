<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ニュース追加/編集</title>
{{include file="parts/common_include.tpl"}}
	<script type="text/javascript" src="{{$smarty.const.BASE_PATH}}common/js/input.js" charset="utf-8"></script>
	<link href="{{$smarty.const.BASE_PATH}}common/css/input.css" rel="stylesheet" type="text/css">
	<link href="{{$smarty.const.BASE_PATH}}common/css/editor.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="{{$smarty.const.BASE_PATH}}common/ckeditor/ckeditor.js" charset="utf-8"></script>
	<script type="text/javascript">

	// ファイルアップロードのURL
	CKEDITOR.config.filebrowserUploadUrl = '{{$smarty.const.BASE_PATH}}info/upload/';

	CKEDITOR.config.allowedContent = true;

	// スペルチェック機能OFF
	CKEDITOR.config.scayt_autoStartup = false;

	// Enterを押した際に改行タグを挿入
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;

		// テキストエリアの幅
//		CKEDITOR.config.width  = '948px';
		CKEDITOR.config.width  = '880px';
		// テキストエリアの高さ
		CKEDITOR.config.height = '300px';
CKEDITOR.config.toolbar = [
['Source']
//,['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print','SpellChecker']
//,['Undo','Redo','-','Find','Replace','-','SelectAll']
//,['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField']
//,'/'
,['Image']
,['Bold','Underline','Strike','-','RemoveFormat']
,['Outdent','Indent']
,['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
,['Link']
//,['Anchor']
//,['HorizontalRule','Smiley','SpecialChar','PageBreak']
//,'/'
,['TextColor','BGColor']
,['Styles','Format','Font','FontSize']
//,['ShowBlocks']
];
$(document).ready(function(){

	$("#contents_type").bind("change", function(){
		if ($(this).val() == "1") {
			$(".link-block").hide();
			$(".article-block").show();
			$(".file-block").hide();
		} else if ($(this).val() == "2") {
			$(".article-block").hide();
			$(".link-block").show();
			$(".file-block").hide();
		} else {
			$(".article-block").hide();
			$(".link-block").hide();
			$(".file-block").show();
		}
	});
	$("#contents_type").change();
});
	</script>
<style>
#cke_upload_164 {
  display: none;
}
</style>
</head>
<body>
		<!-- global header -->
{{include file="parts/global_header.tpl"}}
		<!-- /global header -->
	<div id="wrapper" class="clearfix">

		<!-- left navi -->
{{include file="parts/left_navi.tpl" page="news"}}
		<!-- /left navi -->

		<!-- contents -->
		<div id="contents">
			<div class="pageTtlBg">
				<div class="page">ニュース追加/編集</div>
				<div class="txt">お知らせや編成の追加/更新を行います</div>
			</div>

			<div class="list-title"></div>
			<div id="inputtbl">
				<form action="{{$smarty.const.BASE_PATH}}info/validate/" method="post" name="form_detail" enctype="multipart/form-data">
				<input type="hidden" name="hash" value="{{$form.hash|default:''}}">
				<input type="hidden" name="info_id" value="{{$form.info_id|default:''}}">
				<!-- 左エリア -->
				<div id="left-area">
{{if isset($form.info_id) && $form.info_id}}
					<div class="mb15">{{$form.title}}の編集</div>
{{/if}}
					<table class="tbl" style="margin: 0;">
						<tbody>
							<tr>
								<th>
									タイトル
								</th>
								<td>
									<input type="text" name="title" class="text-input" style="width:600px" value="{{$form.title|default:''}}">
								</td>
							</tr>

							<tr>
								<td>
									<table class="tbl f-left mr10">
										<tbody>
											<tr>
												<th>
													掲載開始
												</th>
												<td>
													<input type="text" class="text-input w80" id="datepicker" name="disp_date_ymd" value="{{$form.disp_date_ymd|default:''}}">
													<a herf="#" id="limitDateCalendar"><img src="{{$smarty.const.BASE_PATH}}common/images/date_icon.png" alt="" style="display:inline-block;" class="date_icon"></a>
													<select name="disp_date_hour">
														<option value=""></option>
{{foreach from=$select_hour key=key item=item}}
														<option value="{{$key}}"{{if $form.disp_date_hour|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
													</select> 時
													<select name="disp_date_min">
														<option value=""></option>
{{foreach from=$select_min key=key item=item}}
														<option value="{{$key}}"{{if $form.disp_date_min|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
													</select> 分
												</td>
											</tr>
										</tbody>
									</table>
									<table class="tbl f-left">
										<tbody>
											<tr>
												<th>
													掲載終了
												</th>
												<td>
													<input type="text" class="text-input w80" id="datepicker2" name="end_date_ymd" value="{{$form.end_date_ymd|default:''}}">
													<a herf="#" id="limitDateCalendar2"><img src="{{$smarty.const.BASE_PATH}}common/images/date_icon.png" alt="" style="display:inline-block;" class="date_icon"></a>
													<select name="end_date_hour">
														<option value=""></option>
{{foreach from=$select_hour key=key item=item}}
														<option value="{{$key}}"{{if $form.end_date_hour|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
													</select> 時
													<select name="end_date_min">
														<option value=""></option>
{{foreach from=$select_min key=key item=item}}
														<option value="{{$key}}"{{if $form.end_date_min|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
													</select> 分
													<label><input type="checkbox" name="is_not_limit_end" value="1" {{if $form.is_not_limit_end|default:''}} checked="checked"{{/if}}>掲載を無期限にする</label>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<th>
									タイプ
								</th>
								<td>
									<select name="contents_type" id="contents_type">
{{foreach from=$config.contents_type key=key item=item}}
									<option value="{{$key}}"{{if $key == $form.contents_type|default:""}} selected="selected"{{/if}}>{{$item}}</option>
{{/foreach}}
									</select>
								</td>
							</tr>

							<tr class="article-block">
								<th>
									内容
								</th>
								<td>
									<textarea name="body" rows="4" cols="40" class="ckeditor">{{$form.body|default:''}}</textarea>
								</td>
							</tr>

							<tr class="link-block">
								<th>
									リンク先URL
								</th>
								<td>
									<input name="link_url" value="{{$form.link_url|default:''}}" size="120">
								</td>
							</tr>

							<tr class="link-block">
								<th>
									新規ウィンドウで開く
								</th>
								<td>
									<select name="is_target">
										<option value="1" {{if $form.is_target|default:'' == 1}} selected="selected"{{/if}}>はい</option>
										<option value="0" {{if $form.is_target|default:'' == 0}} selected="selected"{{/if}}>いいえ</option>
									</select>
								</td>
							</tr>

							<tr class="file-block">
								<th>
									ファイル
								</th>
								<td>
									<input type="file" name="file" value="">
									{{if $form.content_ext|default:""}}
									<div style="margin-top: 10px; margin-left: 10px;">
										<strong>現在アップロードされているファイル</strong><br>
										{{$form.content_ext|strtoupper}} {{$form.content_filesize|filesize_format}} <a href="{{$smarty.const.BASE_PATH|dirname}}/news/files/{{$form.info_id}}.{{$form.content_ext}}" target="_blank">表示</a>
									</div>
									{{/if}}
								</td>
							</tr>

						</tbody>
					</table>
				</div>
				<!-- /左エリア -->

{{if isset($form.info_id) && $form.info_id}}
				<div class="del-btn-area"><input type="button" name="hide" class="btn-del detail-delete" style="margin-bottom:10px;" value="削除"></div>
{{/if}}
				<div class="">
					<input type="button" name="open" class="btn-open detail-open-save" style="margin-left:10px;margin-bottom:10px;" value="更新">
				</div>

				</form>
			</div>
		</div>
{{include file="parts/block_iframe.tpl"}}
		<!-- / contents -->

		<!-- footer -->
		<footer></footer>
		<!-- /footer -->
	</div>
</body>
</html>
