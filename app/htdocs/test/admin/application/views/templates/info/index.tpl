<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>お知らせ更新</title>
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
{{include file="parts/left_navi.tpl" page="news"}}
		<!-- /left navi -->

		<!-- contents -->
		<div id="contents">
{{include file="parts/message.tpl"}}
			<div class="pageTtlBg">
				<div class="page">お知らせ更新</div>
				<div class="txt">お知らせや編成の追加/更新を行います</div>
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
																<input type="text" name="search_info_id" value="{{$form.search_info_id|default:''}}">
															</td>
														</tr>
														<tr>
															<th>
																タイプ
															</th>
															<td>
																<select name="search_contents_type">
																	<option value=""></option>
{{foreach from=$config.contents_type item=item key=key}}
																	<option value="{{$key}}"{{if $form.search_contents_type|default:'' == $key}} selected="selected"{{/if}}>{{$item}}</option>
{{/foreach}}
																</select>
															</td>
														</tr>
														<tr>
															<th>
																タイトル
															</th>
															<td>
																<input type="text" name="search_title" style="width:600px" value="{{$form.search_title|default:''}}">
															</td>
														</tr>
														<tr>
															<th>
																掲載開始日 - 掲載終了日
															</th>
															<td>

																<input type="text" class="text-input w80" id="datepicker" name="search_disp_date_ymd" value="{{$form.search_disp_date_ymd|default:''}}">
																<a herf="#" id="limitDateCalendar"><img src="{{$smarty.const.BASE_PATH}}common/images/date_icon.png" alt="" style="display:inline-block;" class="date_icon"></a>
																<select name="search_disp_date_hour">
																	<option value=""></option>
{{foreach from=$select_hour key=key item=item}}
																	<option value="{{$key}}"{{if $form.search_disp_date_hour|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
																</select>時
																<select name="search_disp_date_min">
																	<option value=""></option>
{{foreach from=$select_min key=key item=item}}
																	<option value="{{$key}}"{{if $form.search_disp_date_min|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
																</select>分
～

																<input type="text" class="text-input w80" id="datepicker2" name="search_end_date_ymd" value="{{$form.search_end_date_ymd|default:''}}">
																<a herf="#" id="limitDateCalendar2"><img src="{{$smarty.const.BASE_PATH}}common/images/date_icon.png" alt="" style="display:inline-block;" class="date_icon"></a>
																<select name="search_end_date_hour">
																	<option value=""></option>
{{foreach from=$select_hour key=key item=item}}
																	<option value="{{$key}}"{{if $form.search_end_date_hour|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
																</select>時
																<select name="search_end_date_min">
																	<option value=""></option>
{{foreach from=$select_min key=key item=item}}
																	<option value="{{$key}}"{{if $form.search_end_date_min|default:'999' == $key}} selected="selected"{{/if}}>{{$item}}</optin>
{{/foreach}}
																</select>分
															</td>
														</tr>
														<tr>
															<th>
																状態
															</th>
															<td>
																<select name="search_status">
{{foreach from=$config.search_select_status item=item key=key}}
																	<option value="{{$key}}"{{if $form.search_status|default:'' == $key}} selected="selected"{{/if}}>{{$item}}</option>
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
						<input type="hidden" name="search_info_id" value="{{$form.search_info_id|default:''}}">
						<input type="hidden" name="search_title" value="{{$form.search_title|default:''}}">
						<input type="hidden" name="search_disp_date_ymd" value="{{$form.search_disp_date_ymd|default:''}}">
						<input type="hidden" name="search_disp_date_hour" value="{{$form.search_disp_date_hour|default:''}}">
						<input type="hidden" name="search_disp_date_min" value="{{$form.search_disp_date_min|default:''}}">
						<input type="hidden" name="search_end_date_ymd" value="{{$form.search_end_date_ymd|default:''}}">
						<input type="hidden" name="search_end_date_hour" value="{{$form.search_end_date_hour|default:''}}">
						<input type="hidden" name="search_end_date_min" value="{{$form.search_end_date_min|default:''}}">
						<input type="hidden" name="search_status" value="{{$form.search_status|default:''}}">
					</div>
				</div>

		<div class="f-right"><input type="button" name="detail" class="btn-del" value="新規作成" onclick="location.href='./detail/'"></div>
		<div class="clearfix"></div>

		<div class="list-area">
		<div>お知らせ一覧</div>
		<form action="" method="POST" name="list_form" class="list-form">
				<table border="0" cellpadding="0" cellspacing="0" class="vlne">
					<tbody>
						<tr>
							<th class="ld-lg-l w50">{{include file="parts/sort.tpl" name="#" key="info_id"}}</th>
							<th class="ld-lg-l w70">{{include file="parts/sort.tpl" name="タイプ" key="info_type"}}</th>
							<th class="ld-lg-l min-w120">{{include file="parts/sort.tpl" name="タイトル" key="title"}}</th>
							<th class="ld-lg-l w70">{{include file="parts/sort.tpl" name="状態" key="is_hide"}}</th>
							<th class="ld-lg-l w150">{{include file="parts/sort.tpl" name="掲載開始" key="disp_date"}}</th>
							<th class="ld-lg-l w150">{{include file="parts/sort.tpl" name="掲載終了" key="end_date"}}</th>
							<th class="ld-lg-l w150">{{include file="parts/sort.tpl" name="最終更新" key="upddate"}}</th>
						</tr>
{{foreach from=$list item=item name="maillist"}}
						<tr class="list-row">
							<td class="ldd-wh-l"><a href="./detail/?info_id={{$item.info_id}}" class="rowlink">{{$item.info_id}}</a></td>
							<td class="ldd-wh-l">{{$config.contents_type[$item.contents_type]}}</td>
							<td class="ldd-wh-l">{{$item.title}}</td>
							<td class="ldd-wh-l">{{if $item.status}}表示{{else}}非表示{{/if}}</td>
							<td class="ldd-wh-l">{{$item.disp_date|date_format:'%Y/%m/%d %H:%M'}}</td>
							<td class="ldd-wh-l">{{if $item.end_date == $config.datetime_max}}無期限{{else}}{{$item.end_date|date_format:'%Y/%m/%d %H:%M'}}{{/if}}</td>
							<td class="ldd-wh-l">{{$item.upddate|date_format:'%Y/%m/%d %H:%M'}}</td>
						</tr>
{{foreachelse}}
						<tr><td colspan="7" style="text-align:center;padding-top:20px;padding-bottom:10px;">一覧結果はありません</td></tr>

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
