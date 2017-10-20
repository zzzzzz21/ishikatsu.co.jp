<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<meta charset="utf-8">
	<title>お知らせ一覧｜石勝エクステリア「都市緑化技術で新しい造園を創造」</title>
	<meta name="keywords" content="造園,石勝エクステリア,都市緑化技術">
	<meta name="description" content="お知らせ一覧。石勝エクステリアは、樹木移植、壁面緑化・屋上緑化など、独自の都市緑化技術で魅力的なランドスケープを実現します。">
{{include_html file="common_include_01.html"}}
	<meta property="og:type" content="article">
	<meta property="og:title" content="お知らせ一覧｜石勝エクステリア「都市緑化技術で新しい造園を創造」">
	<meta property="og:url" content="http://www.ishikatsu.co.jp/news/">
	<meta property="og:description" content="お知らせ一覧。石勝エクステリアは、樹木移植、壁面緑化・屋上緑化など、独自の都市緑化技術で魅力的なランドスケープを実現します。">
{{include_html file="gtm01.html"}}
</head>
<body>
{{include_html file="gtm02.html"}}
	<div class="wrapper" id="wrapperTop">
{{include_html file="header_01.html"}}
		<div class="underMain">
			<section class="underTop underTop--small">
				<div class="underTop__wrapper underTop__wrapper--small">
					<h1 class="underTop__title">お知らせ</h1>
					<p class="underTop__subTitle">Information</p>
				</div>
			</section>
			<ul class="breadcrumb">
				<li class="breadcrumb__item"><a href="../" class="breadcrumb__link">TOP</a><span class="breadcrumb__arrow">＞</span></li>
				<li class="breadcrumb__item"><a href="../news/" class="breadcrumb__link">お知らせ</a></li>
			</ul>
			<ul class="newsList newsList--detail">
{{foreach from=$open_news_list item=item}}
				<li class="newsList__item newsList__item--detail">
					<p class="newsList__Timetext"><time class="newsList__date" datetime="{{$item.disp_date|date_format:'Y-m-d'}}">{{$item.disp_date|date_format:'Y年m月d日'}}</time></p>
					<p class="newsList__Linktext newsList__Linktext--detail">{{include file="parts/news_link.tpl" item=$item}}{{$item.title}}</a></p>
				</li>
{{/foreach}}
			</ul>
{{include file="parts/pager.tpl"}}
		</div>
{{include_html file="footer_01.html"}}
	</div>
</body>
</html>
