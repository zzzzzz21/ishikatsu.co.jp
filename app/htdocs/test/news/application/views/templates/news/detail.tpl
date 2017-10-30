<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<meta charset="utf-8">
	<title>{{$detail.title}} - お知らせ｜石勝エクステリア「都市緑化技術で新しい造園を創造」</title>
	<meta name="keywords" content="造園,石勝エクステリア,都市緑化技術">
	<meta name="description" content="{{$detail.title}} - お知らせ。石勝エクステリアは、樹木移植、壁面緑化・屋上緑化など、独自の都市緑化技術で魅力的なランドスケープを実現します。">
{{include_html file="common_include_02.html"}}
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:image" content="https://www.ishikatsu.co.jp/share/images/og_image.png">
	<meta property="og:type" content="article">
	<meta property="og:title" content="{{$detail.title}} - お知らせ｜石勝エクステリア「都市緑化技術で新しい造園を創造」">
	<meta property="og:url" content="http://www.ishikatsu.co.jp/news/detail/{{$detail.info_id}}/">
	<meta property="og:description" content="{{$detail.title}} - お知らせ。石勝エクステリアは、樹木移植、壁面緑化・屋上緑化など、独自の都市緑化技術で魅力的なランドスケープを実現します。">
{{include_html file="gtm01.html"}}
</head>
<body>
{{include_html file="gtm02.html"}}
	<div class="wrapper" id="wrapperTop">
{{include_html file="header_02.html"}}
		<div class="underMain">
			<section class="underTop underTop--small">
				<div class="underTop__wrapper underTop__wrapper--small">
					<h1 class="underTop__title">お知らせ</h1>
					<p class="underTop__subTitle">Information</p>
				</div>
			</section>
			<ul class="breadcrumb">
				<li class="breadcrumb__item"><a href="../../" class="breadcrumb__link">TOP</a><span class="breadcrumb__arrow">＞</span></li>
				<li class="breadcrumb__item"><a href="../../news/" class="breadcrumb__link">お知らせ</a><span class="breadcrumb__arrow">＞</span></li>
				<li class="breadcrumb__item">{{$detail.title}}</li>
			</ul>
			<article class="newsArticle">
				<p class="newsArticle__time"><time class="newsArticle__date" datetime="{{$detail.disp_date|custom_date_format:'Y-m-d'}}">{{$detail.disp_date|custom_date_format:'Y年m月d日'}}</time></p>
				<h2 class="newsArticle__title">
					{{$detail.title}}
				</h2>
				<div class="newsArticle__content">
					{{$detail_ne.body}}
				</div>
			</article>
			<ul class="pagerButton">
				<li class="pagerButton__item pagerButton__item--prev">{{if $prev|default:""}}<a href="../{{$prev.info_id}}/" class="pagerButton__link pagerButton__link--prev">PREV</a>{{/if}}</li>
				<li class="pagerButton__item pagerButton__item--next">{{if $next|default:""}}<a href="../{{$next.info_id}}/" class="pagerButton__link pagerButton__link--next">NEXT</a>{{/if}}</li>
			</ul>

			<p class="articleButton"><a href="../" class="articleButton__link">一覧にもどる</a></p>
		</div>
{{include_html file="footer_02.html"}}
	</div>
</body>
</html>
