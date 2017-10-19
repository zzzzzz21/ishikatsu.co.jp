		<header id="global" class="clearfix">
			<a href="/"><img src="{{$smarty.const.BASE_PATH}}common/images/logo.png?20160323" alt=""></a>
			<ul class="issue-menu Cf">
				<li class="user">
					<div class="user_main{{if $session.login.is_admin|default:'' == 1}} cursor_pointer{{/if}}">
						<div class="left">
					{{$session.login.name}}<br>
					{{$config.account_auth[$session.login.is_admin]}}
						</div>
{{if $session.login.is_admin|default:'' == 1}}
						<div class="right">
							∨
						</div>
{{/if}}
						<div class="clearfix"></div>
{{if $session.login.is_admin|default:'' == 1}}
						<div class="admin_menu" style="display:none;">
							<div class="menu_title">設定</div>
							<div><a href="{{$smarty.const.BASE_PATH}}account/">管理メニュー</a></div>
						</dvi>
{{/if}}
					</div>
				</li>
				<li class="logout">
					<a href="{{$smarty.const.BASE_PATH}}login/">
						ログアウト
					</a>
				</li>
			</ul>
		</header>
