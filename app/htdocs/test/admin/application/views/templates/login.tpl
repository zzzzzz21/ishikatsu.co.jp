<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>ログイン</title>
{{include file="parts/common_include.tpl"}}
	<link href="{{$smarty.const.BASE_PATH}}common/css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="wrapper">
		<!-- contents -->
		<div id="contents">
			<div id="login-wrap">
				<section id="login">
					<div class="logo"><img src="{{$smarty.const.BASE_PATH}}common/images/logo.png"></div>
					<div class="msg">ログインしてください</div>
					<div class="login-form">
{{if $login_fail}}
						<div class="error">ログインに失敗しました。</div>
{{/if}}
						<form method="post" action="{{$smarty.const.BASE_PATH}}login/" name="login">
							<table>
								<tbody>
									<tr>
										<td>
											<input type="text" class="text-input" size="30" name="login_id" placeholder="ログインID">
										</td>
									</tr>
									<tr>
										<td>
											<input type="password" class="text-input" size="20" name="password" placeholder="パスワード">
										</td>
									</tr>
									<tr>
										<td colspan="2" class="centered">
											<input type="submit" name="login" class="cmn-btn green" value="ログイン">
											<input type="hidden" name="do" value="1">
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					<!-- End LoginForm -->
				</section>
				<!-- End login -->
			</div>
		</div>
		<!-- / contents -->
	</div>
</body>
</html>
