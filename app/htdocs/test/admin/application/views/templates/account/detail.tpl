<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>管理者追加/編集</title>
{{include file="parts/common_include.tpl"}}
	<script type="text/javascript" src="{{$smarty.const.BASE_PATH}}common/js/input.js" charset="utf-8"></script>
	<link href="{{$smarty.const.BASE_PATH}}common/css/input.css" rel="stylesheet" type="text/css">
</head>
<body>
		<!-- global header -->
{{include file="parts/global_header.tpl"}}
		<!-- /global header -->
	<div id="wrapper" class="clearfix">

		<!-- left navi -->
{{include file="parts/left_navi.tpl" page="account"}}
		<!-- /left navi -->

		<!-- contents -->
		<div id="contents">
			<div class="pageTtlBg">
				<div class="page">管理者追加/編集</div>
				<div class="txt">管理者の追加/更新を行います</div>
			</div>

			<div class="list-title"></div>
			<div id="inputtbl">
				<form action="{{$smarty.const.BASE_PATH}}account/validate/" method="post" name="form_detail" enctype="multipart/form-data">
				<input type="hidden" name="hash" value="{{$form.hash|default:''}}">
				<input type="hidden" name="account_id" value="{{$form.account_id|default:''}}">
				<!-- 左エリア -->
				<div id="left-area">
{{if isset($form.account_id) && $form.account_id}}
					<div class="mb15">{{$form.name}}の編集</div>
{{/if}}
					<table class="tbl">
						<tbody>
							<tr>
								<th>
									名前
								</th>
								<td>
									<input type="text" name="name" class="text-input" style="width:600px" value="{{$form.name|default:''}}">
								</td>
							</tr>
							<tr>
								<th>
									ログインID
								</th>
								<td>
									<input type="text" name="login_id" class="text-input" style="width:600px" value="{{$form.login_id|default:''}}">
								</td>
							</tr>
							<tr>
								<th>
									パスワード
								</th>
								<td>
									<input type="text" name="password" class="text-input" style="width:600px" value="{{$form.password|default:''}}">
								</td>
							</tr>

							<tr class="link-block">
								<th>
									権限
								</th>
								<td>
									<label><input type="radio" name="is_admin" value="1" {{if $form.is_admin|default:'' == 1}} checked="checked"{{/if}}>管理者</label>
									<label><input type="radio" name="is_admin" value="0" {{if $form.is_admin|default:'' == 0}} checked="checked"{{/if}}>一般ユーザ</label>
								</td>
							</tr>

						</tbody>
					</table>
				</div>
				<!-- /左エリア -->

{{if isset($form.account_id) && $form.account_id}}
				<div class="del-btn-area"><input type="button" name="hide" class="btn-del detail-delete" style="margin-bottom:10px;" value="削除"></div>
{{/if}}
				<div class=""><input type="button" name="open" class="btn-open detail-open-save" style="margin-left:10px;margin-bottom:10px;" value="更新"></div>

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
