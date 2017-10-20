<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>管理メニュー</title>
{{include file="parts/common_include.tpl"}}
	<link href="{{$smarty.const.BASE_PATH}}common/css/list.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="{{$smarty.const.BASE_PATH}}common/js/list.js"></script>
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
{{include file="parts/message.tpl"}}
			<div class="pageTtlBg">
				<div class="page">管理メニュー</div>
				<div class="txt">管理者の追加/更新を行います</div>
			</div>
			<div class="list-title"></div>

			<div id="listtbl">

				<div class="search-block">
					<div class="title">結果絞り込み<a href=""><img src="{{$smarty.const.BASE_PATH}}common/images/close.png" alt="" width="20" class="close-icon"></a></div>
					<div class="form-contents">
						<form action="./" method="POST">
							<input type="hidden" name="is_post" value="1">
							<input type="hidden" name="p" value="1">
							<input type="hidden" name="sort_key" value="{{$form.sort_key|default:''}}">
							<input type="hidden" name="sort_type" value="{{$form.sort_type|default:''}}">
							<div class="inner">
								<table class="tbl">
									<tbody>
										<tr>
											<td>
												<table class="tbl f-left mr35">
													<tbody>
														<tr>
															<th>
																ID
															</th>
															<td>
																<input type="text" name="search_account_id" value="{{$form.search_account_id|default:''}}">
															</td>
														</tr>
														<tr>
															<th>
																名前
															</th>
															<td>
																<input type="text" name="search_name" style="width:600px" value="{{$form.search_name|default:''}}">
															</td>
														</tr>
														<tr>
															<th>
																権限
															</th>
															<td>
																<select name="search_is_admin">
																	<option value=""{{if !$form.search_is_admin|default:''|strlen && ($form.search_is_admin|default:'' == '')}} selected="selected"{{/if}}>全て</option>
{{foreach from=$config.account_auth item=item key=key}}
																	<option value="{{$key}}"{{if $form.search_is_admin|default:''|strlen && ($form.search_is_admin|default:'' == $key)}} selected="selected"{{/if}}>{{$item}}</option>
{{/foreach}}
																</select>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="align-c"><input type="submit" name="list_search" class="btn-search" value="絞り込み"></div>
						</form>
					</div>
					<div class="search-hidden">
						<input type="hidden" name="search_account_id" value="{{$form.search_account_id|default:''}}">
						<input type="hidden" name="search_name" value="{{$form.search_name|default:''}}">
						<input type="hidden" name="search_is_admin" value="{{$form.search_is_admin|default:''}}">
					</div>
				</div>

		<div class="f-right"><input type="button" name="detail" class="btn-del" value="新規作成" onclick="location.href='./detail/'"></div>
		<div class="clearfix"></div>

		<div class="list-area">
		<div>管理者一覧</div>
		<form action="" method="POST" name="list_form" class="list-form">
				<table border="0" cellpadding="0" cellspacing="0" class="vlne">
					<tbody>
						<tr>
							<th class="ld-lg-l w50">{{include file="parts/sort.tpl" name="#" key="account.account_id"}}</th>
							<th class="ld-lg-l w70">{{include file="parts/sort.tpl" name="名前" key="account.name"}}</th>
							<th class="ld-lg-l w70">{{include file="parts/sort.tpl" name="権限" key="account.is_admin"}}</th>
							<th class="ld-lg-l w150">{{include file="parts/sort.tpl" name="最終更新" key="account.upddate"}}</th>
							<th class="ld-lg-l w150">{{include file="parts/sort.tpl" name="更新者" key="account.upd_account_id"}}</th>
						</tr>
{{foreach from=$list item=item name="maillist"}}
						<tr class="list-row">
							<td class="ldd-wh-l"><a href="./detail/?account_id={{$item.account_id}}" class="rowlink">{{$item.account_id}}</a></td>
							<td class="ldd-wh-l">{{$item.name}}</td>
							<td class="ldd-wh-l">{{$config.account_auth[$item.is_admin]}}</td>
							<td class="ldd-wh-l">{{$item.upddate|date_format:'%Y/%m/%d %H:%M'}}</td>
							<td class="ldd-wh-l">{{$item.upd_account_name}}</td>
						</tr>
{{foreachelse}}
						<tr><td colspan="5" style="text-align:center;padding-top:20px;padding-bottom:10px;">一覧結果はありません</td></tr>

{{/foreach}}
					</tbody>
				</table>
		</form>

		<form action="{{$page_info.base_url}}" name="list_sort_paging_form" method="POST">
			<input type="hidden" name="p" value="{{$form.p|default:''}}">
			<input type="hidden" name="sort_key" value="{{$form.sort_key|default:''}}">
			<input type="hidden" name="sort_type" value="{{$form.sort_type|default:''}}">
			<input type="hidden" name="is_post" value="1">
		</form>

		</div>

			<!-- pager -->
{{include file="parts/pager.tpl"}}
			<!-- / pager -->
		</div>
		</div>
		<!-- / contents -->

		<!-- footer -->
		<footer></footer>
		<!-- /footer -->
	</div>
</body>
</html>
